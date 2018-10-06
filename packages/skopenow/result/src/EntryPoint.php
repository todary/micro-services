<?php

/**
 * Entry point for the per result process .
 * @author Ahmed Samir <ahmedsamir732@gmail.com>
 */
namespace Skopenow\Result;

use Skopenow\Result\Save\ResultSave;
use Skopenow\Result\Save\UrlPrepare;
use Skopenow\Result\DataSourceBridge;
use Skopenow\Result\ResultDelete;
use Skopenow\Result\Verify\CheckVerifiedResults;
use Skopenow\Result\Verify\VerifiedResults;
use Skopenow\Result\Verify\VerifiedFlags;
use Skopenow\Result\AfterSave\AfterSave;
use Skopenow\Result\Save\RejectedResult;
use Skopenow\Result\Save\PendingResults;
use App\Libraries\DBCriteria;
use Skopenow\Result\Siblings\Create;
use Skopenow\Result\Siblings\Update;
use App\Models\ResultData;
use App\Models\Result;
use App\Models\MainSource;
use App\Events\AfterSaveEvent ;
use App\Libraries\ResultCache;
use Skopenow\Result\DataSourceLuman;
use App\Libraries\DBCriteriaInterface;
use App\Libraries\ScoringFlags;

class EntryPoint
{
    protected $dataSource = null;

    public function __construct(Result $results = null)
    {
        $this->result = $results ;
        // $this->dataSource = new DataSourceBridge;
        $this->dataSource = new DataSourceLuman;
    }

    public function save($resultData)
    {
        $scoreObj = loadService("scoring");
        $rejected = new RejectedResult;
        
        $savingResult = new ResultSave(
            $this->dataSource,
            $scoreObj,
            $rejected
        );

        $output = $savingResult->saveResult($resultData);
        $this->addLog($resultData, $output);

        return $output;
    }

    public function update(array $data, $resultId = null, $resultUrl = null)
    {
        $scoreObj = loadService("scoring");
        $rejected = new RejectedResult;

        $savingResult = new ResultSave(
            $this->dataSource,
            $scoreObj,
            $rejected
        );

        $output = $savingResult->updateResult($data, $resultId, $resultUrl);
        $logData = [
            "input" => $data,
            "method" => "update",
            "status" => "update",
            "result_id" => $output,
            "url" => $resultUrl
        ];
        $this->addLog($logData);
        return $output;
    }

    public function updateIdentities(array $results, array $identities)
    {
        $output = [];
        $scoreObj = loadService("scoring");
        
        foreach ($results as $result) {
            $data = ["identities" => $identities];

            $updated = $this->update($data, $result->id, $result->url);
            $output[$result->id] = $updated;
        }

        return $output;
    }

    public function updateByCriteria($data, $criteria)
    {
        if (!empty($data)) {
            return $this->dataSource->updateByCriteria($data, $criteria);
        }
        return 0;
    }

    public function afterSave(Result $result)
    {
        $status = event(new AfterSaveEvent($result));
        return $status;
    }
    
    public function delete(array $resultsIds, int $deleteType)
    {
        $deleteObj = new DeleteResult($this->dataSource);
        $output = $deleteObj->deleteResult($resultsIds, $deleteType);

        for ($i=0; $i<count($resultsIds); $i++) {
            $logData = [
                "input" => ["delete_type" => $deleteType],
                "method" => "delete",
                "status" => "delete",
                "result_id" => $resultsIds[$i],
                "url" => null
            ];
            $this->addLog($logData);
        }
        return $output;
    }

    #Can be Removed
    public function updateDisplayLevel(array $resultsIds, int $displayLevel)
    {
        $deleteObj = new DeleteResult($this->dataSource);

        return $deleteObj->updateDisplayLevel($resultsIds, $displayLevel);
    }

    public function visibleResults(array $resultsIds, int $isVisible)
    {
        $visibleObj = new VisibleResult($this->dataSource);

        return $visibleObj->visibleResults($resultsIds, $isVisible);
    }

    public function getNeverDeletedResults($results)
    {
        $VerifiedResults = new VerifiedResults($results) ;
        $neverDeletedResults = $VerifiedResults->getNeverDeletedResults() ;
        return $neverDeletedResults ;
    }

    public function getVerifiedResults($results)
    {
        $VerifiedResults = new VerifiedResults($results) ;
        $verifiedResults = $VerifiedResults->getVerifiedResults() ;
        return $verifiedResults ;
    }

    public function getOnlyOneRelatives($results)
    {
        $VerifiedResults = new VerifiedResults($results) ;
        $onlyOneRelatives = $VerifiedResults->getOnlyOneRelatives() ;
        return $onlyOneRelatives ;
    }

    public function getResult($resultId = null, $url = null)
    {
        if (is_null($resultId) && is_null($url)) {
            return false;
        }

        $result = $this->dataSource->getResult($resultId, $url);

        return $result;
    }

    public function getResultById($resultId)
    {
        $closure = function() use ($resultId) {
            $result = Result::find($resultId);
            if ($result) {
                return ResultData::fromModel($result);
            } else {
                return null;
            }
        };
        $result = ResultCache::get($resultId, $closure);
        // $result = ResultData::fromModel(Result::find($resultId));
        return $result;
    }

    public function getResultByUrl($url)
    {
        $urlInfo = loadService("urlInfo");
        $url = $urlInfo->prepareContent($url);

        $closure = function () use($url) {
            $report_id = config('state.report_id');
            $result = Result::where('report_id',$report_id)->where('unique_content',$url)->first();
            return ResultData::fromModel($result);
        };

        $result = ResultCache::getByLink($url, $closure);
        
        return $result;
    }

    public function afterResultSave(\App\Models\ResultData $result)
    {
        $afterSave = new AfterSave();
        $afterSave->runAfterResultSave($result);
    }

    public function afterResultUpdate(\App\Models\ResultData $result)
    {
        $afterSave = new AfterSave();
        $afterSave->runAfterResultUpdate($result);
    }

    /**
     *[saveRejected description]
     *
     *@param \App\Models\ResultData $resultData [description]
     *@param int                    $reason     [description]
     *
     *@return bool                              [description]
     */
    public function saveRejected(\App\Models\ResultData $resultData, int $reason)
    {
        $rejectedResult = new RejectedResult();
        $rejectedResult->reason = $reason;
        $output = $rejectedResult->save($resultData);
        
        $logData = [
            "input" => $resultData,
            "method" => "saveRejected",
            "status" => "rejected",
            "result_id" => null,
            "url" => $resultData->url
        ];
        $this->addLog($logData);

        return $output;
    }

    public function saveToPending(\App\Models\ResultData $result)
    {
        $PendingResults = new PendingResults();
        $output = $PendingResults->save($result);

        $logData = [
            "input" => $result,
            "method" => "saveRejected",
            "status" => "pending",
            "result_id" => null,
            "url" => $result->url
        ];
        $this->addLog($logData);

        return $output;
    }

    public function getPendingResults(array $criteria)
    {
        $PendingResults = new PendingResults();
        $results = $PendingResults->get($criteria);

        return $results;
    }

    public function getAllFromPending(): array
    {
        $report_id = config('state.report_id');
        $PendingResults = new PendingResults();
        $results = $PendingResults->getAll($report_id);

        return $results;
    }

    /**
     *[getRejectedResults description]
     *
     *@param int|null $reportId [description]
     *@param int|null $reason   [description]
     *
     *@return array             [description]
     */
    public function getRejectedResults(int $reportId = null, int $reason = null)
    {
        $rejectedResult = new RejectedResult();
        $output = $rejectedResult->getRejectedResults($reportId, $reason);
        
        return $output;
    }

    /**
     *[deleteRejected description]
     *
     *@param int|null $reportId [description]
     *
     *@return bool             [description]
     */
    public function deleteRejected(int $reportId = null)
    {
        $rejectedResult = new RejectedResult();
        $output = $rejectedResult->deleteRejectedResults($reportId);
        
        return $output;
    }

    /**
     *[getResults description]
     *
     *@param BridgeCriteria $criteria [description]
     *
     *@return array                 [array of Result Model]
     */
    public function getResults($criteria)
    {
        $modelQuery = $criteria->prepareLumenQuery();
        $result = $this->dataSource->getResults($modelQuery);

        return $result;
    }

    public function createDefaultSiblings(ResultData $result)
    {
        $createSiblings = new Create();
        return $createSiblings->createDefaultSiblings($result);
    }

    public function saveParentChilds(Result $parent, array $childs)
    {
        $createSiblings = new Create();
        return $createSiblings->saveParentChilds($parent, $childs);
    }

    public function updateSiblings(array $data, DBCriteriaInterface $criteria)
    {
        $updateSiblings = new Update;
        $output = $updateSiblings->update($data, $criteria);
        if ($output) {
            return true;
        }
        return false;
    }

    public function checkWithFlags(int $mainFlags, array $checkedFlags)
    {
        $verifiedResults = new CheckVerifiedResults();
        return $verifiedResults->checkWithFlags($mainFlags, $checkedFlags);
    }

    /**
     * [checkforVerifiedResults check for verifed results]
     * @param  array  $sources [array of source we want to watch up with like ['facebook', 'linkedin']]
     * @return [type]          [return the count of verifed results]
     */
    public function checkForVerifiedResults(array $sources)
    {
        $defaultResults = array_combine($sources, array_fill(0, count($sources), 0));
        
        $verifiedflagsObj = new VerifiedFlags();
        $verifiedflags = array_merge($verifiedflagsObj->getNeverDeletedIdentities(), $verifiedflagsObj->getVerifiedIdentities());
        $scoringFlags = new ScoringFlags();
        $verifiedflagsQuery = $scoringFlags->convertFlagsIntoQuery($verifiedflags);
        $report_id = config('state.report_id');

        $results = \DB::table('result')->join('main_source', 'main_source.id', 'result.source_id')
                        ->select('main_source.name', \DB::raw('count(result.id)'))
                        ->where('report_id', $report_id)
                        ->where('is_profile', 1)
                        ->where('is_relative', 0)
                        ->whereIn('main_source.name', $sources)
                        ->whereRaw('('.$verifiedflagsQuery.')')
                        ->groupBy('main_source.name')
                        ->get()->toArray();

        $results = array_column($results, 'count(result.id)', 'name');

        return $results+$defaultResults;
    }

    protected function convertFlagsIntoQuery(array $flags)
    {
        $query = [];
        foreach ($flags as $flag) {
            $query[] = '(flags&'.$flag.' = '.$flag.')';
        }

        $query = implode(' or ', $query);

        return $query;
    }

    protected function addLog($resultData, $output = null)
    {
        return;
        
        $logger = loadService("logger", [150]);
        $state = [
            "report_id" => config("state.report_id"),
            "combination_id" => config("state.combination_id"),
            "combination_level_id" => config("state.combination_level_id"),
            "environment" => env("APP_ENV")
        ];

        $logData = [
            "input" => $resultData,
            "method" => "save",
            "status" => $output[$resultData->url]["action"]??null,
            "result_id" => $output[$resultData->url]["resultId"]??null,
            "url" => $resultData->url
        ];

        $logger->addLog($state, $logData);

        foreach ($resultData->getLinks() as $link) {
            $logData = [
                "input" => $link["result"],
                "method" => "save",
                "status" => $link["status"]??0,
                "result_id" => $link["id"],
                "url" => $link["url"]
            ];
            $logger->addLog($state, $logData);
        }
    }
}
