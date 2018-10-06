<?php
namespace Skopenow\Reports\CombinationGenerators\Generators;

use Skopenow\Reports\Models\Report;

/**
*
*/
class YellowpagesCombinationsGenerator extends AbstractCombinationsGenerator
{
    public function make()
    {
        $this->makePhoneCombinations();
    }

    public function makePhoneCombinations()
    {
        $this->createSimpleCombination(['phone'], 'yellowpages');
    }
}
