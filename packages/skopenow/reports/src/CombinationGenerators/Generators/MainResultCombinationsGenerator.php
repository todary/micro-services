<?php
namespace Skopenow\Reports\CombinationGenerators\Generators;

use Skopenow\Reports\Models\Report;
use App\Libraries\DBCriteria;

/**
*
*/
class MainResultCombinationsGenerator extends AbstractCombinationsGenerator
{
    protected $data;
    const MAX_COMBINATIONS_COUNT = 10;

    public function make()
    {
        if ($this->data['main_source'] == 'facebook' && $this->relativesCount()) {
            $this->makeNameCombinations();
        }

        if ($this->isEligibleToReportNameComb()) {
            $this->makeNameCombinations(true);
        }

        if ($this->data['verified']) {
            if (!empty($this->data['image'])) {
                $this->makeImageCombinations();
            }
        }
    }

    private function makeNameCombinations(bool $isReport = false)
    {
        \Log::info("BRAIN: MainResult Profile Id {$this->data['social_profile_id']}");
        $source = 'facebook_in_friends';
        $combinationService = loadService('combinations');
        $combinationsCounter = $combinationService->getSourceCombinationsCount($source);
        if ($combinationsCounter > self::MAX_COMBINATIONS_COUNT) {
            \Log::info('BRAIN: MainResult limit reached');
            \Log::debug('BRAIN: MainResult limit reached', [$combinationsCounter, 'social_profile_id' => $this->data['social_profile_id']]);
            return;
        }
        \Log::debug('BRAIN: MainResult Create Combination', ['social_profile_id' => $this->data['social_profile_id']]);
        if (!$isReport) {
            $names = $this->data['names'];
            $names = loadService('reports')->getReportInputNames();
            $names = names_parts($names);
            $this->combinationsMaker->set('name', $names, 'relative_name');
        }
        $this->combinationsMaker->set('social_profile_id', [$this->data['social_profile_id']]);
        $this->combinationsMaker->set('is_relative', [$this->data['is_relative']]);
        $this->combinationsMaker->set('result_id', [$this->data['result_id']]);
        // TODO
        // $this->combinationsMaker->set('related_to', $this->data['related_to']);

        if (!$isReport) {
            $combinations = $this->combinationsMaker
            ->withEach(['relative_name', 'social_profile_id', 'is_relative', 'result_id'])
            ->get();
        } else {
            $combinations = $this->combinationsMaker
            ->withEach(['name', 'social_profile_id', 'is_relative', 'result_id'])
            ->get();
        }


        foreach ($combinations as $combination) {
            $criteria = $this->buildSearchCriteria($combination);
            if (!$isReport) {
                $criteria->middle_name = '';
                $criteria->first_name = '';
            }

            $level = ['source' => $source, 'data' => $criteria->toCombinationData(), 'level_number' => 1];
            $combId = $this->combinationsService->store($source, [$level]);
            $params = ['type' => 'r2c', 'is_relative' => $this->data['is_relative']];
            $this->insertRelationship($this->data['result_id'], $combId, $params);
        }
        \Log::info('BRAIN: Main result relative name combination generator');
    }

    private function makeImageCombinations()
    {
        $source = 'google';
        $this->combinationsMaker->set('profile_image', [$this->data['image']]);
        $this->createSimpleCombination(['profile_image'], $source);

/*        $combinations = $this->combinationsMaker
            ->withEach(['profile_image'])
            ->get();

        foreach ($combinations as $combination) {
            $criteria = $this->buildSearchCriteria($combination);
            $level = ['source' => $source, 'data' => $criteria->toCombinationData(), 'level_number' => 1];
            $combId = $this->combinationsService->store($source, [$level]);
        }*/
        \Log::info('BRAIN: Main result profile Image combination generator');
    }

    protected function relativesCount()
    {
        $reportId = config('state.report_id');
        $dataPointService = loadService('datapoint')->datasource();
        $DBCriteriaReport = new DBCriteria();
        $DBCriteriaReport->compare('report_id', $reportId);
        $DBCriteriaReport->addInCondition('type', ['relatives']);
        $datapoints = $dataPointService->loadData($DBCriteriaReport);
        return count($datapoints);
    }

    protected function isEligibleToReportNameComb()
    {
        $resultService = loadService('result');
        $verifiedProfiles = $resultService->checkForVerifiedResults(['facebook']);
        $fbVerifiedProfiles = $verifiedProfiles['facebook'];
        $scoringFlags = loadData("scoringFlags");
        $flags = $this->data['flags'];
        $matchingFlags = [
            'exct_small_city' => $scoringFlags['exct-sm']['value'],
        ];
        $resultService->checkWithFlags($flags, $matchingFlags);

        return ($this->data['main_source'] == 'facebook' &&
            $this->relativesCount() <= 4 &&
            $this->data['is_relative'] &&
            !$fbVerifiedProfiles);
    }
}
