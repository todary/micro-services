<?php
namespace Skopenow\Reports\CombinationGenerators\Generators;

use Skopenow\Reports\Models\Report;
use \Illuminate\Database\Eloquent\ModelNotFoundException;

/**
*
*/
class FacebookCombinationsGenerator extends AbstractCombinationsGenerator
{
    public function make()
    {
        $this->makeNameWorkCombinations();
        $this->makeNameSchoolCombinations();
        $this->makeEmailCombinations();
        $this->makePhoneCombinations();
    }

    public function makePhoneCombinations()
    {
        foreach ($this->report->phone as $phone) {
            $this->combinationsMaker->set('phone', [
                '001' . $phone,
                '+1' . $phone
            ]);

            $combinations = $this->combinationsMaker
                ->withEach(['phone'])
                ->get();
            $levelNo = 1;
            foreach ($combinations as $combination) {
                $criteria = $this->buildSearchCriteria($combination);
                $level = ['source' => 'facebook_people_search', 'data' => $criteria->toCombinationData(), 'level_number' => $levelNo];
                $this->combinationsService->store('facebook_people_search', [$level]);
                $levelNo++;
            }
        }
    }

    public function makeNameSchoolCombinations()
    {
        $combinations = $this->combinationsMaker
            ->withEach(['name', 'school'])
            ->get();

        foreach ($combinations as $combination) {
            $criteria = $this->buildSearchCriteria($combination);
            $level = ['source' => 'facebook_people_search', 'data' => $criteria->toCombinationData(), 'level_number' => 1];
            $this->combinationsService->store('facebook_people_search', [$level]);

            // $combinationId = $this->combinationsService->store('facebook_people_search');
            // $this->combinationsService->addCombinationLevel($combinationId, $criteria->toCombinationData());
        }
    }

    public function makeNameWorkCombinations()
    {
        $combinations = $this->combinationsMaker
            ->withEach(['name', 'company'])
            ->get();

        foreach ($combinations as $combination) {
            $criteria = $this->buildSearchCriteria($combination);

            $level = ['source' => 'facebook_people_search', 'data' => $criteria->toCombinationData(), 'level_number' => 1];
            $this->combinationsService->store('facebook_people_search', [$level]);

            // $combinationId = $this->combinationsService->store('facebook_people_search');
            // $this->combinationsService->addCombinationLevel($combinationId, $criteria->toCombinationData());
        }

    }

    public function makeEmailCombinations()
    {
        $this->createSimpleCombination(['email'], 'facebook_people_search');
    }
}
