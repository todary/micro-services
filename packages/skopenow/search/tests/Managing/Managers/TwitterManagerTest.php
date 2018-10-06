<?php
namespace Skopenow\SearchTest\Managing\Managers;

use Skopenow\Search\Fetching\Fetchers\TwitterFetcher;
use Skopenow\Search\Managing\Managers\TwitterManager;
use Skopenow\Search\Models\Criteria;
use Skopenow\Search\Models\SearchList;
use Skopenow\Search\Models\SearchResult;

class TwitterManagerTest extends \TestCase
{
    public function testExecution()
    {
        setUrlMock("https://twitter.com/search?q=tom+cruise&src=typd&f=users", file_get_contents(__DIR__ . '/../../data/Twitter-Search-TomCruise-FirstLast.json'));

        $criteria = new Criteria;
        $criteria->first_name = "tom";
        $criteria->last_name = "cruise";

        $fetcher = new TwitterFetcher($criteria);

        $manager = new TwitterManager($fetcher);
        $actualList = $manager->execute();

        $expectedList = new SearchList('twitter');
        $expectedList->setUrl("https://twitter.com/search?q=tom+cruise&src=typd&f=users");

        $result = new SearchResult('http://twitter.com/TomCruise');
        
        $result->username = "TomCruise";
        $result->orderInList = 0;
        $expectedList->addResult($result);
        
        $result = new SearchResult('http://twitter.com/TomCruiseFanCom');
        
        $result->username = "TomCruiseFanCom";
        $result->orderInList = 1;
        $expectedList->addResult($result);
        
        $result = new SearchResult('http://twitter.com/MissionFilm');
        
        $result->username = "MissionFilm";
        $result->orderInList = 2;
        $expectedList->addResult($result);
        
        $result = new SearchResult('http://twitter.com/TomCruiseBRCom');
        
        $result->username = "TomCruiseBRCom";
        $result->orderInList = 3;
        $expectedList->addResult($result);
        
        $result = new SearchResult('http://twitter.com/TheAmyNicholson');
        
        $result->username = "TheAmyNicholson";
        $result->orderInList = 4;
        $expectedList->addResult($result);
        
        $this->assertEquals($expectedList, $actualList);
    }

    public function testNoResponse()
    {
        setUrlMock("https://twitter.com/search?q=tom+cruise&src=typd&f=users", file_get_contents(__DIR__ . '/../../data/Twitter-Search-TomCruise-CityState.json'), "HTTP/1.1 404 Not Found");

        $criteria = new Criteria;
        $criteria->first_name = "tom";
        $criteria->last_name = "cruise";


        $fetcher = new TwitterFetcher($criteria);

        $manager = new TwitterManager($fetcher);
        $actualList = $manager->execute();

        $expectedList = new SearchList('twitter');

        $this->assertEquals($expectedList, $actualList);
    }
}
