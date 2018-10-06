<?php

namespace Skopenow\Cleanup\Helpers;

/**
 * Description of FilterProcessor
 *
 * @author ahmedsamir
 */

use Skopenow\Cleanup\FilterResults\FilterResultsInterface;
use Skopenow\Cleanup\Helpers\CommonHelpers;
use Skopenow\Cleanup\Helpers\Relationships;

class FilterProcessor
{

    use CommonHelpers;

    protected $filterResultsObj;

    protected $matchedResults = array();

    protected $nonMatchedResults = array();

    protected $relationshipsObj;

    public function __construct(FilterResultsInterface $filterResultsObj, Relationships $relationshipsObj)
    {
        $this->filterResultsObj = $filterResultsObj;
        $this->relationshipsObj = $relationshipsObj;
    }

    public function process()
    {
        $this->filterResultsObj->process();
        $this->matchedResults = $this->filterResultsObj->getMatchedResults();
        $this->nonMatchedResults = $this->filterResultsObj->getNonMatchedResults();

        if ($this->filterResultsObj::ExtractRelatedResults) {
            $relatedResults = $this->extractRelatedResults();
            $this->matchedResults = array_merge($this->matchedResults, $relatedResults);
        }

        ## get nonMatched Results from the same source ..
        if ($this->filterResultsObj::FilterResultsFromSameSource) {
            $this->nonMatchedResults = $this->extractResultFromSameSource();
        }

        return $this->formatReturnData();
    }

    protected function formatReturnData(): array
    {
    	dump([get_class($this->filterResultsObj), "matchedResults" => $this->matchedResults, "nonMatchedResults" => $this->nonMatchedResults]);
        return array(
            "matchedResults" => $this->matchedResults,
            "nonMatchedResults" => $this->nonMatchedResults,
        );
    }

    protected function extractRelatedResults()
    {
        $matchedResultsIds = array_column($this->matchedResults, "id");
        $relatedResultsIds = $this->relationshipsObj->getRelatedResults($matchedResultsIds);
        $relatedResults = $this->getResultsByIds($this->filterResultsObj->getAllResults(), $relatedResultsIds);
        return $relatedResults;
    }

    public function extractResultFromSameSource()
    {
        $resultsObj = new Results($this->filterResultsObj->getAllResults());
        $nonMatchedResults = $resultsObj->getResultsFromSameSource($this->matchedResults);
        $matchedResultsIds = array_column($nonMatchedResults, "id");
        $relatedResultsIds = $this->relationshipsObj->getRelatedResults($matchedResultsIds);
        $relatedResults = $this->getResultsByIds($this->filterResultsObj->getAllResults(), $relatedResultsIds);
        if (!empty($relatedResults)) {
            $nonMatchedResults = array_merge($nonMatchedResults, $relatedResults);
        }
        return $nonMatchedResults;
    }

}
