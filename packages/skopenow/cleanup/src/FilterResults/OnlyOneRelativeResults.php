<?php

namespace Skopenow\Cleanup\FilterResults;

/**
 * return the Only One Relative Results .
 *
 * @author Ahmed Samir <ahmedsamir732@gmail.com>
 */

use Skopenow\Cleanup\FilterResults\FilterResultsAbstract;
use Skopenow\Cleanup\FilterResults\FilterResultsInterface;

class OnlyOneRelativeResults extends FilterResultsAbstract implements FilterResultsInterface
{

    const ExtractRelatedResults = true;

    const FilterResultsFromSameSource = false;

    public function process(): array
    {
        $OnlyOneRelatives = $this->resultService->getOnlyOneRelatives($this->results);
        $this->matchedResults = $OnlyOneRelatives;
        return $this->matchedResults;
    }
}
