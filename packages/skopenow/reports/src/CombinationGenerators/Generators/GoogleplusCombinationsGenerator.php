<?php
namespace Skopenow\Reports\CombinationGenerators\Generators;

use Skopenow\Reports\Models\Report;
use \Illuminate\Database\Eloquent\ModelNotFoundException;

/**
*
*/
class GoogleplusCombinationsGenerator extends AbstractCombinationsGenerator
{
    public function make()
    {
        $this->makeEmailCombinations();
        $this->makeNameCombinations();
    }

    public function makeEmailCombinations()
    {
        $this->createSimpleCombination(['email'], 'googleplus');
    }

    public function makeNameCombinations()
    {
        $this->createSimpleCombination(['name'], 'googleplus');
    }
}
