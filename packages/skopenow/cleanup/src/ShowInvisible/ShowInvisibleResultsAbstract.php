<?php

namespace Skopenow\Cleanup\ShowInvisible;

use Skopenow\Cleanup\FiltrationModel;
use Skopenow\Result\PurifyResults\PurifyResults;
use Skopenow\Cleanup\Helpers\CommonHelpers;

/**
 * Description of ShowInvisibleResultsAbstract
 *
 * @author ahmedsamir
 */
abstract class ShowInvisibleResultsAbstract
{

    use CommonHelpers;

    protected $filtrationModel;

    public function __construct(FiltrationModel $filtrationModel)
    {
        $this->filtrationModel = $filtrationModel;
    }

    protected function getInvisibleResults($results)
    {
        $invisibleResults = array();
        foreach ($results as $result) {
            if ($result['invisible'] == 1) {
                $invisibleResults[] = $result;
            }
        }
        return $invisibleResults;
    }

    protected function getVisibleResults($results)
    {
        $visibleResults = array();
        foreach ($results as $result) {
            if ($result['invisible'] == 0) {
                $visibleResults[] = $result;
            }
        }
        return $visibleResults;
    }


    protected function filterInvisibleResultsByIds($invisibleResults, $visibleResultsIds)
    {
        $results = array();
        foreach ($invisibleResults as $invisibleResult) {
            if (!in_array($invisibleResult['id'], $visibleResultsIds)) {
                $results[] = $invisibleResult;
            }
        }
        return $results;
    }

    protected function filterInvisibleResultsBySource($invisibleResults, $sources)
    {
        $results = array();
        foreach ($invisibleResults as $invisibleResult) {
            if (!in_array($invisibleResult['source_id'], $sources, true)) {
                $results[] = $invisibleResult;
            }
        }
        return $results;
    }

    protected function getPurifingRules(): \Iterator
    {
        return new \ArrayIterator();
    }

    public function purifyResults(\Iterator $results): \Iterator
    {
        $purifiedResults = $results;
        $rules = $this->getPurifingRules();
        if ($rules->count()) {
            $PurifyResults = new PurifyResults($rules);
            $purifiedResults = $PurifyResults->purify($results);
        }

        return $purifiedResults;
    }
}
