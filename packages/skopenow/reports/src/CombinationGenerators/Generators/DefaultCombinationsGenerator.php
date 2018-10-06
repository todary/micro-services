<?php
namespace Skopenow\Reports\CombinationGenerators\Generators;

use Skopenow\Reports\Services\SourcesService;

/**
*
*/
class DefaultCombinationsGenerator extends AbstractCombinationsGenerator
{
    protected $source;

    protected function make()
    {
        // var_dump($this->source);
        return;
        $this->combinationsMaker->set('country_code', ['us']);
       
        $combinations = $this->combinationsMaker
             ->withEach(['name','location'])
             ->with(['country_code'], true)
             ->get();
        $sourcesService = new SourcesService();
        $sources = $sourcesService->getSources($this->source);
        foreach ($sources as $source) {
            foreach ($combinations as $combination) {
                $combinationId = $this->combinationsService->store($source);
                $criteria = $this->buildSearchCriteria($combination);

                $this->combinationsService->addCombinationLevel($combinationId, $criteria->toCombinationData());


                if ($combination->has('location')) {
                    $level = $combination->exclude(['location']);
                    $criteria = $this->buildSearchCriteria($level);
                    $this->combinationsService->addCombinationLevel($combinationId, $criteria->toCombinationData());
                }
            }
        }
    }

    public function setSource($source)
    {
        $this->source = $source;
    }
}
