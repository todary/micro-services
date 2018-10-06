<?php

namespace Skopenow\Result\AfterSave\Helpers;

use App\Models\ResultData as Result;
use Skopenow\Result\AfterSave\Helpers\DataPoints;
use Skopenow\Result\AfterSave\Helpers\Runner;
use Skopenow\Result\AfterSave\MatchDataPoints\MatchDataPointInterface;
use Skopenow\Result\EntryPoint as ResultService;
use Skopenow\Result\Verify\CheckVerifiedResults;
use Skopenow\Result\Save\ResultSave;
use App\Libraries\DBCriteria;
use App\Models\Result as ResultLumen;
use Skopenow\Result\AfterSave\MatchDataPoints\MatchWork;
use Skopenow\Result\AfterSave\MatchDataPoints\MatchSchool;
use Skopenow\Result\AfterSave\MatchDataPoints\MatchUsername;
use App\Models\Entity;

trait Runner
{

	/**
     * Here we will start all the data points match types, and
     * return all the matched results .
     */
    protected function startDataPointMatch(array $dataPoints)
    {
        if (empty($dataPoints)) {
            return;
        }

        $relationships = [];
        $matchedResults = [];
        $nonMatchedResults = [];
        foreach ($dataPoints as $type => $typeDataPoints) {
            $matcher = $this->getDataPointMatcher($type);
            if ($matcher && $matcher instanceof MatchDataPointInterface) {
                $relationships[] = $matcher->match($typeDataPoints, $this->resultsDataPoints);
                $matchedResults[] = $matcher->getMatchedResults();
                $nonMatchedResults[] = $matcher->getNonMathedResults();
            }
        }
        $this->relationships = array_merge($this->relationships, ...$relationships);
        $this->matchedResults = array_merge($this->matchedResults, ...$matchedResults);
        $this->nonMatchedResults = array_merge($this->nonMatchedResults, ...$nonMatchedResults);
    }

    /**
     * [startRelatingResults description]
     * @param  array  $relationships [description]
     * @return [type]                [description]
     */
    protected function startRelatingResults(array $relationships)
    {
        // Load Relationship service .
        $status = false;
        if (!empty($relationships)) {
            foreach ($relationships as $relationship) {
                $params = ["type" => "R2R", "reason" => $relationship['reason']];
                $firstEntity = $relationship['firstEntity']??$this->result->id;
                $secondEntity = $relationship['secondEntity']??$relationship['id'];

                if(empty($firstEntity) || empty($secondEntity)) continue;

                try {
                    $firstEntity = Entity::findOrFail($firstEntity);
                    $secondEntity = Entity::findOrFail($secondEntity);
                    $this->relationshipService->insert()->setRelationship($firstEntity, $secondEntity, $params);
                    $status = true;
                } catch (\Exception $e) {
                    notifyDevForException($e);

                    $message = json_encode($firstEntity)." & ".json_encode($secondEntity).": ".$this->result->url;
                    dd($message);
                    notifyDev($message);
                    \Log::error($e);
                }
            }
        }
        return $status;
    }

    protected function startFilterationProcess($verifiedLevel)
    {
        ## upgrade the matched results .
        if (in_array($verifiedLevel, ["first", "second"], true)) {
            $this->processStatus['upgradeResults'] = $this->upgradeMatchedResult($this->matchedResults);
            ## visible The related profiles
            $this->visibleRelatedResults();
        }
        

        ## remove the matched result from non matched results .
        $resultsIds = array_column($this->matchedResults, "result_id");
        $this->removeFromNonMatchedList($resultsIds);

        ## start Filtrations .
        if ($verifiedLevel == "first") {
            $this->processStatus['hideResults'] = $this->hideResults($this->nonMatchedResults);
            ## hide non related profiles from the same source.
            $this->hideNonRelatedResults();
        }
        return true;
    }

    protected function upgradeMatchedResult($results)
    {
        if (empty($results)) return false;

        ## Group results with each other using the result_id
        $results = $this->groupResultWithId($results);
        foreach ($results as $key => $value) {
        	
        	$value['scoreFlags'] = array_filter(array_unique($value['scoreFlags']));
            if (!empty($value['scoreFlags'])) {
                $newScore = [
                    "identities" => $value['scoreFlags'],
                    "identifiers" => $value['identifiers'],
                ];
                $this->rescoreResult($value['result_id'], $newScore);
                $this->rescoreResult($this->result->id, $newScore);                
            }
        }
        return true;
    }

    protected function rescoreResult($result_id, $newScore)
    {
        if (! $result_id) return false;
        $resultService = new ResultService();
        $result = $resultService->getResultById($result_id);
        if (! $result) return false;
        $newScore = $this->scoringService->rescore($newScore, $result->unique_url, false);
        $resultData = [
            'score_identity'    =>  json_encode($newScore['identities']),
            'identifiers'       =>  json_encode($newScore['identifiers']),
            'flags'             =>  $newScore['flags'],
            'matching_flags'    =>  $newScore['matching_flags'],
            'input_flags'       =>  $newScore['input_flags'],
            'extra_flags'       =>  $newScore['extra_flags'],
            'score'             =>  $newScore['finalScore'],
            'invisible'         =>  0,
            'is_deleted'        =>  0,
        ];
        $status = $resultService->update($resultData,$result_id);
    }

    /**
     * [startFilteration run the filtration process if the result is verified]
     * @return [type] [description]
     */
    protected function hideResults($results)
    {
        if (empty($results))	return;

        $resultService = new ResultService();
        $resultsIds = array_unique(array_filter(array_column($results, "result_id")));
        return $resultService->visibleResults($resultsIds,0);
    }

    protected function isVerified(Result $result)
    {
        if ($result->getIsRelative()) {
            return ['status' => false , 'level' => null];
        }


        $CheckVerified = new CheckVerifiedResults();
        $status = $CheckVerified->checkIdentities($result);
        return $status;
    }

    protected function checkIsVerified($flags)
    {
        $CheckVerified = new CheckVerifiedResults();
        $status = $CheckVerified->check($flags);
        return $status;
    }

    /**
     * [getDataPointMatcher description]
     * @param  string $type [description]
     * @return [type]       [description]
     */
    protected function getDataPointMatcher(string $type)
    {
        $matcher = null;
        switch ($type) {
            case "work":
                $this->resultsDataPoints = $this->loadData('work_experiences');
                $matcher = new MatchWork($this->result);
                break;
            case "school":
                $this->resultsDataPoints = $this->loadData('schools');
                $matcher = new MatchSchool($this->result);
                break;
            case "username";
                $this->resultsDataPoints = $this->loadData('added_usernames');
                $matcher = new MatchUsername($this->result);
                break;
            default:
                $matcher = null;
        }
        return $matcher;
    }

    protected function removeFromNonMatchedList($resultsIds)
    {
        foreach ($this->nonMatchedResults as $key => $value) {
            if (in_array($value['result_id'], $resultsIds, true)) {
                unset($this->nonMatchedResults[$key]);
            }
        }

    }

    protected function groupResultWithId(array $results)
    {
        $groupedResults = array();
        foreach ($results as $key => $result) {
            $result_id = $result['result_id'];
            if (isset($groupedResults[$result_id])) {
                $groupedResults[$result_id]['reason'] = $groupedResults[$result_id]['reason'] | $result['reason'];
                $groupedResults[$result_id]['scoreFlags'][] = $result['scoreFlag'];
                $groupedResults[$result_id]['identifiers'][] = $result['identifier'];
                continue;
            }
            $groupedResults[$result_id] = array(
                "result_id" => $result['result_id'],
                "reason" => $result['reason'],
                "scoreFlags" => [$result['scoreFlag']],
                "identifiers" => [$result['identifier']],
            );

        }
        return $groupedResults;
    }

    protected function visibleRelatedResults()
    {
        $results = $this->getRelatedResults([$this->result->id]);

        if (!empty($results)) {
            $resultService = new ResultService();
            return $resultService->visibleResults($results,0);
        }
        return false;

    }

    protected function hideNonRelatedResults()
    {
        $resultService = loadService('result');
        $resultsIds = $this->getRelatedResults([$this->result->id]);

        $results = ResultLumen::where('report_id', config('state.report_id'))
                            ->whereIn('id',$resultsIds)
                            ->where('is_profile', 1)
                            ->get()->toArray();

        $sources = array_column($results, 'source_id');

        $nonRelatedResults = ResultLumen::where('report_id', config('state.report_id'))
                                        ->whereNotIn('id', $resultsIds)
                                        ->whereIn('source_id', $sources)
                                        ->where('is_profile', 1)
                                        ->get()->toArray();
        $nonRelatedResults = $this->filterVerifiedResults($nonRelatedResults);
        if (!empty($nonRelatedResults)) {
            $nonRelatedResults = array_column($nonRelatedResults, "id");
            $invisibleResults = [];
            foreach ($nonRelatedResults as $nonRelatedResult) {
                $relatedTo = $this->getRelatedResults([$nonRelatedResult]);
                $relatedToResults = ResultLumen::where('report_id', config('state.report_id'))
                                    ->whereIn('id',$relatedTo)
                                    ->where('is_profile', 1)
                                    ->get()->toArray();

                $filterVerified = $this->filterVerifiedResults($relatedToResults);
                if (count($relatedTo) == count($filterVerified)) {
                    $invisibleResults = array_merge($invisibleResults, $relatedTo);
                }
            }
            if ($invisibleResults) {
                return $resultService->visibleResults($invisibleResults,1);
            }
        }
        return false;
    }

    protected function getRelatedResults(array $relatedTo)
    {
        $linears = $this->relationshipService->retrieve()->getLinearRelationships(['first_party' => $relatedTo , 'second_party' => $relatedTo]);
        $results = $relatedTo;
        foreach ($linears as $key => $linear) {
            if (in_array($linear['first_party'], $relatedTo)) {
                $results[] = $linear['second_party'];
            }elseif(in_array($linear['second_party'], $relatedTo)) {
                $results[] = $linear['first_party'];
            }
        }
        return $results;
    }

    protected function filterVerifiedResults(array $results): array
    {
        $returnedResults = [];
        foreach ($results as $result) {
            $isVerified = $this->checkIsVerified($result['flags']??0);
            if (!$isVerified['status']) {
                $returnedResults[] = $result;
            }
        }

        return $returnedResults;
    }

}
