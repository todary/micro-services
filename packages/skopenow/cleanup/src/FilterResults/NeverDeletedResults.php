<?php

namespace Skopenow\Cleanup\FilterResults;

/**
 * return the results will never be deleted .
 *
 * @author Ahmed Samir <ahmedsamir732@gmail.com>
 */

use Skopenow\Cleanup\FilterResults\FilterResultsAbstract;
use Skopenow\Cleanup\FilterResults\FilterResultsInterface;

class NeverDeletedResults extends FilterResultsAbstract implements FilterResultsInterface
{

    const ExtractRelatedResults = true;

    const FilterResultsFromSameSource = false;

    public function process(): array
    {
        $neverDeletedResults = $this->resultService->getNeverDeletedResults($this->results);
        $neverDeletedResults = $this->cleanRelativeProfiles($neverDeletedResults);
        $this->matchedResults = $neverDeletedResults;
        return $this->matchedResults;
    }
}
