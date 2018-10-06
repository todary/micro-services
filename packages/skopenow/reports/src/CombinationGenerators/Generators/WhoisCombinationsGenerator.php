<?php
namespace Skopenow\Reports\CombinationGenerators\Generators;

use Skopenow\Reports\Models\Report;
use App\Models\EmailBlacklist;
use \Illuminate\Database\Eloquent\ModelNotFoundException;

/**
*
*/
class WhoisCombinationsGenerator extends AbstractCombinationsGenerator
{
    public function make()
    {
        $emails = $this->report->email;
        foreach ($emails as $email) {
            if ($this->checkPrivateEmail($email)) {
                $this->makeDomainCombinations($email);
            }
        }
        $this->makeNameLocationCombinations();
        $this->makeEmailCombinations();
    }

    public function makeNameLocationCombinations()
    {
        $this->createSimpleCombination(['name', 'location'], 'websites');
    }

    public function makeEmailCombinations()
    {
        $this->createSimpleCombination(['email'], 'websites');
    }

    public function makeDomainCombinations($email)
    {
        preg_match("/(.*)@(.*)/", $email, $match);
        $domain = $match[2];
        $this->combinationsMaker->set('domain', [$domain]);
        $this->createSimpleCombination(['domain'], 'websites');
        /*$combinations = $this->combinationsMaker
            ->withEach(['domain'])
            ->get();

        foreach ($combinations as $combination) {
            $criteria = $this->buildSearchCriteria($combination);
            $level = ['source' => 'websites', 'data' => $criteria->toCombinationData(), 'level_number' => 1];
            $this->combinationsService->store('websites', [$level]);
        }*/
    }
}
