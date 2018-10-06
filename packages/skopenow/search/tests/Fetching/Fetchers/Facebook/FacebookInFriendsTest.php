<?php
namespace Skopenow\Search\Fetching\Fetchers;

use Skopenow\Search\Fetching\Fetchers\Facebook\FacebookInFriends;
use Skopenow\Search\Models\Criteria;
use Skopenow\Search\Models\SearchList;
use Skopenow\Search\Models\SearchResult;

class FacebookInFriendsTest extends \TestCase
{

    public function testFetching()
    {
        $criteria = new Criteria;
        $criteria->last_name = "Douglas";
        $criteria->profile_id = 4713141;
        $fetcher = new FacebookInFriends($criteria);
        $fetcher->execute();
        $actualList = $fetcher->getOutput();
        dd($actualList);
    }
}
