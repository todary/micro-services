<?php

namespace Skopenow\Cleanup\ShowInvisible;

/**
 * Description of ShowInvisible
 *
 * @author ahmedsamir
 */
use Skopenow\Cleanup\FiltrationModel;
use Skopenow\Cleanup\ShowInvisible\ShowInvisibleInterface;
use Skopenow\Cleanup\ShowInvisible\ShowInvisibleResultsAbstract;

class ShowLessThanThree extends ShowInvisibleResultsAbstract implements ShowInvisibleInterface
{
    const GroupCount = 3;

    public function process(): array
    {
        $results = $this->filtrationModel->getAttribute("allResults");
        $visibleResults = $this->getVisibleResults($results);
        $invisibleResults = $this->getInvisibleResults($results);
        $invisibleResults = $this->filterInvisibleResultsByIds($invisibleResults, array_column($visibleResults, "id"));
        $invisibleResults = $this->filterInvisibleResultsBySource($invisibleResults, array_column($visibleResults, "source_id"));

        $groupedInvisibleResults = $this->groupBySource($invisibleResults);
        $resultsToBeVisible = $this->getResultsToBeVisible($groupedInvisibleResults);
        return $resultsToBeVisible;
    }

    protected function groupBySource(array $invisibleResults)
    {
        $results = [];
        foreach ($invisibleResults as $invisibleResult) {
            $results[$invisibleResult['source_id']][] = $invisibleResult;
        }
        return $results;
    }

    protected function getResultsToBeVisible($groupedInvisibleResults)
    {
        $results = [];
        foreach ($groupedInvisibleResults as $group) {
        	$purifiedResults = $this->purifyResults(new \ArrayIterator($group));
            if ($purifiedResults->count() <= self::GroupCount) {
            	$group = iterator_to_array($purifiedResults);
                $results = array_merge($results, $group);
            }
        }
        return $results;
    }

    protected function getPurifingRules(): \Iterator
    {
    	$scoringFlags = loadData('scoringFlags');
    	$flMatch = $scoringFlags['fn']['value'] | $scoringFlags['ln']['value'];
    	$uniqueMatch = $scoringFlags['unq_name']['value'];
    	$smallCityMatch = $scoringFlags['exct-sm']['value'];
    	$bigCityMatch = $scoringFlags['exct-bg']['value'];
    	$partialCityMatch = $scoringFlags['pct']['value'];
    	$stateMatch = $scoringFlags['st']['value'];
    	return new \ArrayIterator([
    		$flMatch|$uniqueMatch|$smallCityMatch		=>	1,
    		$flMatch|$uniqueMatch|$bigCityMatch			=>	2,
    		$flMatch|$uniqueMatch|$partialCityMatch		=>	3,
    		$flMatch|$uniqueMatch|$stateMatch			=>	4,
    		$flMatch|$smallCityMatch					=>	5,
    		$flMatch|$bigCityMatch						=>	6,
    		$flMatch|$partialCityMatch					=>	7,
    		$flMatch|$stateMatch						=>	8,
    		$flMatch|$uniqueMatch						=>	9,
    	]);
    }

}
