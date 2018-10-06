<?php

/**
 * Input search
 * @author Wael Salah
 * @package Search
 * @subpackage Fetching
 */

namespace Skopenow\Search\Fetching\Fetchers;

use Skopenow\Search\Fetching\AbstractFetcher;
use Skopenow\Search\Models\SearchList;
use Skopenow\Search\Models\SearchListInterface;
use Skopenow\Search\Models\DataPoint;
use Skopenow\Search\Models\SearchResult;
use App\DataTypes\Name;

class PeopledataFetcher extends AbstractFetcher
{

    /**
     * @const string The selected account for search
     */
    const MAIN_SOURCE_NAME = "peopledata";

    public $availableProfileInfo = ['name'];

    protected function prepareRequest()
    {
    }
    protected function makeRequest()
    {
        $results = [];

        foreach ($this->criteria->profiles as $profile) {
            $resultDataObj = new SearchResult($profile);
            $resultDataObj->setIsPeopleData(true);
            $results[] = $resultDataObj;
        }

        return $results;
    }
    protected function processResponse($results) : SearchListInterface
    {
        $list = new SearchList(self::MAIN_SOURCE_NAME);

        foreach ($results as $result) {
            if ($this->onResultFound($result)) {
                $result->addIdentityShouldHave('people_un');
                $list->addResult($result);
            }
        }

        return $list;
    }
}
