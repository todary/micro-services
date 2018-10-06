<?php
namespace Skopenow\SearchTest\Managing\Managers;

use Skopenow\Search\Fetching\Fetchers\TwitterstatusFetcher;
use Skopenow\Search\Managing\Managers\TwitterstatusManager;
use Skopenow\Search\Models\Criteria;
use Skopenow\Search\Models\SearchList;
use Skopenow\Search\Models\SearchResult;

class TwitterstatusManagerTest extends \TestCase
{
    public function testExecution()
    {
        setUrlMock("https://twitter.com/search?q=tom+cruise&src=typd", file_get_contents(__DIR__ . '/../../data/Twitterstatus-Search-TomCruise-FirstLastNames.json'));

        $criteria = new Criteria;
        $criteria->first_name = "tom";
        $criteria->last_name = "cruise";

        $fetcher = new TwitterstatusFetcher($criteria);

        $manager = new TwitterstatusManager($fetcher);
        $actualList = $manager->execute();

        $expectedList = new SearchList('twitterstatus');
        $expectedList->setUrl("https://twitter.com/search?q=tom+cruise&src=typd");

        $result = new SearchResult('http://twitter.com/larsen_payton/status/911209746438414339');
        $result->setIsProfile(false);
        $result->title = "Young Tom cruise will always be a panty dropper.";
        $result->orderInList = 0;
        $result->addName('Tom Cruise');
        $expectedList->addResult($result);
        
        
        $result = new SearchResult('http://twitter.com/Lawrence_tko/status/911550959616217088');
        $result->setIsProfile(false);
        $result->title = "When Tom Cruise asked for a pic with &#39;The Sauce&#39;";
        $result->orderInList = 1;
        $expectedList->addResult($result);
        
        
        $result = new SearchResult('http://twitter.com/Skydance/status/911591332291989510');
        $result->setIsProfile(false);
        $result->title = "Ethan Hunt or Jack Reacher: which Tom Cruise character is your favorite?";
        $result->orderInList = 2;
        $result->addName('Payton Larsen');
        $expectedList->addResult($result);
        
        $result = new SearchResult('http://twitter.com/MovieGrafTR/status/911905036506210305');
        $result->setIsProfile(false);
        $result->title = "Brad Pitt, Nicole Kidman &amp; Tom Cruise, 1998.";
        $result->orderInList = 3;
        $result->addName('Lawrence Okolie');
        $expectedList->addResult($result);
        
        
        $result = new SearchResult('http://twitter.com/cristiamos8586/status/913262171437465601');
        $result->setIsProfile(false);
        $result->title = "Conan drives with  Tom Cruise&#39;s highlights. Part 5: Can&#39;t bear being stuck in this uncomfortable situation, Tom asked for help.";
        $result->orderInList = 4;
        $expectedList->addResult($result);
        
        $this->assertEquals($expectedList, $actualList);
    }

    public function testNoResponse()
    {
        setUrlMock("https://twitter.com/search?q=tom+cruise&src=typd", file_get_contents(__DIR__ . '/../../data/Twitter-Search-TomCruise-FirstLast.json'), "HTTP/1.1 404 Not Found");

        $criteria = new Criteria;
        $criteria->first_name = "tom";
        $criteria->last_name = "cruise";


        $fetcher = new TwitterstatusFetcher($criteria);

        $manager = new TwitterstatusManager($fetcher);
        $actualList = $manager->execute();

        $expectedList = new SearchList('twitterstatus');

        $this->assertEquals($expectedList, $actualList);
    }
}
