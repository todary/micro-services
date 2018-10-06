<?php

namespace Skopenow\Search\Managing\Managers;

use Skopenow\Search\Managing\AbstractManager;
use Skopenow\Search\Fetching\FetcherInterface;
use Skopenow\Search\Models\DataPointInterface;
use Skopenow\Search\Models\SearchResultInterface;
use App\Libraries\BridgeCriteria;

class UsernamesManager extends AbstractManager
{
    /**
     * @const string The selected account for search
     */
    const MAIN_SOURCE_NAME = "usernames";
    
    /**
     * @var FetcherInterface Source fetcher
     */
    protected $fetcher;


    protected function checkResult(SearchResultInterface $result)
    {
        if ($this->fetcher->criteria->username_source == "input") {
            $result->addIdentityShouldHave('input_un');
        }elseif ($this->fetcher->criteria->username_source == 'peopleData') {
            $result->addIdentityShouldHave('people_un');
        } else if ($this->fetcher->criteria->username_status == "verified") {
            $result->addIdentityShouldHave('verified_un');
        }

        return parent::checkResult($result);
    }

    protected function onEnd(bool $hasData)
    {
        ## check the all username results once again on group;
        // $count = 0;
        // if ($this->searchOutput) {
        //     foreach ($this->searchOutput->getResults() as $result) {
        //         if ($this->isMatchName($result)) {
        //             $count++;
        //         }
        //     }
        // }
        // ## check if not any profile rejected.
        // ## check if not any profiles visible.

        // if ($count >= 2) {
        //     ## show username results.
        //     $this->showAllCombinationResults();
        // }
        
        ## run parent onEnd.
        parent::onEnd($hasData);
    }

    protected function isMatchName(SearchResultInterface $result): bool
    {
        $status = false;
        $matchStatus = $result->getMatchStatus();
        if (!empty($matchStatus['matchingData']['name']['status'])) {
            $status = true;
        }

        return $status;
    }

    protected function showAllCombinationResults()
    {
        $combination_id = config('state.combination_id');
        $resultService = loadService('result');
        $criteria = new BridgeCriteria();
        $criteria->compare('combination_id', $combination_id);
        $status = $resultService->updateByCriteria(['invisible' => 0], $criteria);

        return $status;
    }

}
