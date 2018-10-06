<?php
namespace Skopenow\Reports\CombinationGenerators\Generators;

use Skopenow\Reports\Models\Report;
use App\DataTypes\DataType;

/**
*
*/
class PeopleDataCombinationsGenerator extends AbstractCombinationsGenerator
{
    public function make()
    {
        $this->makeProfilesCombinations();
    }

    protected function makeProfilesCombinations()
    {
        $urls = [];
        foreach ($this->data as $data) {
            if (!empty($data['url'])) {
                $urls[] = $data['url'];
            }
        }
        \Log::info('BRAIN: creating peopledata profile combinations');
        $this->combinationsMaker->set('profiles', [$urls]);
        $combinations = $this->combinationsMaker
            ->withEach(['profiles'])
            ->get();

        \Log::info('BRAIN: creating peopledata profile combinations ... number of combs: ' . count($combinations));
        foreach ($combinations as $combination) {
            $criteria = $this->buildSearchCriteria($combination);
            $level = ['source' => 'peopledata', 'data' => $criteria->toCombinationData(), 'level_number' => 1];
            $combId = $this->combinationsService->store('peopledata', [$level]);
        }
    }
}
