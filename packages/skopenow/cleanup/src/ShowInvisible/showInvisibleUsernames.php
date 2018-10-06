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

class ShowInvisibleUsernames extends ShowInvisibleResultsAbstract implements ShowInvisibleInterface
{

	public function process():array
	{
		$resultsToBeVisible = [];
		$allResults = $this->filtrationModel->getAttribute("allResults");
        $visibleResults = $this->getVisibleResultsMatchedUsername($allResults);
        $invisibleResults = $this->getInvisibleResultsMatchedUsername($allResults);

        $relationships = $this->filtrationModel->getAttribute('relationships');
        if (!empty($visibleResults)) {
        	$relationsFlags = loadData('relationsFlags');
        	$usernameFlag = $relationsFlags['username']['value'];
        	foreach ($invisibleResults as $invisibleResult) {
				$relatedResultsIds = $relationships->getRelatedResults([$invisibleResult['id']], $usernameFlag);
				$relatedResults = $this->getResultsByIds($visibleResults, $relatedResultsIds);
				$matchedCount = $this->checkMatchedCount($relatedResults);
				if ($matchedCount >= 2) {
					$resultsToBeVisible[] = $invisibleResult;
				}
        	}
        }
        
        // dd($visibleResults, $invisibleResults, $resultsToBeVisible);
        return $resultsToBeVisible;
	}

	protected function getVisibleResultsMatchedUsername($results)
    {
        $invisibleResults = array();
        $scoringFlags = loadData('scoringFlags');
        $usernameFlags = $scoringFlags['un']['value'];
        foreach ($results as $result) {
            if ($result['invisible'] == 0 && ($result['flags'] & $usernameFlags) == $usernameFlags) {
                $invisibleResults[] = $result;
            }
        }
        return $invisibleResults;
    }

	protected function getInvisibleResultsMatchedUsername($results)
    {
        $invisibleResults = array();
        $scoringFlags = loadData('scoringFlags');
        $usernameFlags = $scoringFlags['un']['value'];
        foreach ($results as $result) {
            if ($result['invisible'] == 1 && ($result['flags'] & $usernameFlags) == $usernameFlags) {
                $invisibleResults[] = $result;
            }
        }
        return $invisibleResults;
    }

    protected function checkMatchedCount(array $results): int
    {
    	$count = 0;
    	$scoringFlags = loadData('scoringFlags');
        $nameFlags = $scoringFlags['fn']['value'] | $scoringFlags['ln']['value']; 
    	foreach ($results as $result) {
    		if (($result->flags&$nameFlags) == $nameFlags) {
    			$count++;
    		}
    	}

    	return $count;
    }
}