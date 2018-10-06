<?php
namespace Skopenow\Reports\CombinationGenerators;

use Skopenow\Reports\Models\Report;

/**
*
*/
class LinkedinCombinationsGenerator extends CombinationsGenerator
{
    public function make()
    {
        $this->combinationsMaker->set('country_code', ['us']);
       
        $combinations = $this->combinationsMaker
             ->withEach(['name','location'])
             ->withEach(['school', 'company'], true)
             ->with(['country_code'], true)
             ->get();
        
        foreach ($combinations as $combination) {
            $combinationId = $this->combinationsService->store('linkedin');
            $criteria = $this->buildSearchCriteria($combination);

            $this->combinationsService->addCombinationLevel($combinationId, $criteria->toCombinationData());

            if (!empty($criteria->mn)) {
                $criteria->mn = '';
                $this->combinationsService->addCombinationLevel($combinationId, $criteria->toCombinationData());
            }

            //store combination
            if ($combination->has('school')) {
                $level = $combination->exclude(['school']);
                $criteria = $this->buildSearchCriteria($level);
                $this->combinationsService->addCombinationLevel($combinationId, $criteria->toCombinationData());
            }

            if ($combination->has('company')) {
                $level = $combination->exclude(['company']);
                $criteria = $this->buildSearchCriteria($level);
                $this->combinationsService->addCombinationLevel($combinationId, $criteria->toCombinationData());
            }

            if ($combination->has('location')) {
                $level = $combination->exclude(['location']);
                $criteria = $this->buildSearchCriteria($level);
                $this->combinationsService->addCombinationLevel($combinationId, $criteria->toCombinationData());
            }
            
            if ($combination->has('country_code')) {
                $level = $combination->exclude(['country_code']);
                $criteria = $this->buildSearchCriteria($level);
                $this->combinationsService->addCombinationLevel($combinationId, $criteria->toCombinationData());
            }
        }
    }
}
