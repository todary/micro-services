<?php
namespace Skopenow\SearchTest\Managing\Managers;

use Skopenow\Search\Fetching\Fetchers\FoursquareFetcher;
use Skopenow\Search\Managing\Managers\FoursquareManager;
use Skopenow\Search\Models\Criteria;
use Skopenow\Search\Models\SearchList;
use Skopenow\Search\Models\SearchResult;

class FoursquareManagerTest extends \TestCase
{
    public function testExecution()
    {
        $apiAccount = getApiAccount("foursquare");
        setUrlMock("https://api.foursquare.com/v2/users/search?name=tom+cruise&oauth_token=".$apiAccount->password."&v=20160726", file_get_contents(__DIR__ . '/../../data/Foursquare-Search-TomCruise.json'));

        $criteria = new Criteria;
        $criteria->first_name = "tom";
        $criteria->last_name = "cruise";

        $fetcher = new FoursquareFetcher($criteria);

        $manager = new FoursquareManager($fetcher);
        $actualList = $manager->execute();

        $expectedList = new SearchList('foursquare');
        $expectedList->setUrl("https://api.foursquare.com/v2/users/search?name=tom+cruise&oauth_token=".$apiAccount->password."&v=20160726");

        $result = new SearchResult('https://api.foursquare.com/v2/users/search?name=tom+cruise&v=20160726');
        
        $result->id = 39093674;
        $result->orderInList = 0;
        $result->addName('tom cruise tim beta');
        $result->addLocation('Salvador');
        $expectedList->addResult($result);
        
        $result = new SearchResult('https://api.foursquare.com/v2/users/search?name=tom+cruise&v=20160726');
        
        $result->id = 127513550;
        $result->orderInList = 1;
        $result->addName('Tom Cruise');
        $result->addLocation('İzmir, Turkey');
        $expectedList->addResult($result);
        
        
        $result = new SearchResult('https://api.foursquare.com/v2/users/search?name=tom+cruise&v=20160726');
        
        $result->id = 4911633;
        $result->orderInList = 2;
        $result->addName('Tom Cruise');
        $result->addLocation('Bang Po, Bang Sue Thailand');
        $expectedList->addResult($result);
        
        $result = new SearchResult('https://api.foursquare.com/v2/users/search?name=tom+cruise&v=20160726');
        
        $result->id = 122892842;
        $result->orderInList = 3;
        $result->addName('Tom Cruise');
        $result->addLocation('Syracuse (NY)');
        $expectedList->addResult($result);
        
        $result = new SearchResult('https://api.foursquare.com/v2/users/search?name=tom+cruise&v=20160726');
        
        $result->id = 6877401;
        $result->orderInList = 4;
        $result->addName('tom cruise');
        $result->addLocation('千代田区, 日本');
        $expectedList->addResult($result);
        
        $this->assertEquals($expectedList, $actualList);
    }

    public function testNoResponse()
    {
        $apiAccount = getApiAccount("foursquare");
        setUrlMock("https://api.foursquare.com/v2/users/search?name=tom+cruise&oauth_token=".$apiAccount->password."&v=20160726", file_get_contents(__DIR__ . '/../../data/Foursquare-Search-TomCruise.json'), "HTTP/1.1 404 Not Found");

        $criteria = new Criteria;
        $criteria->first_name = "tom";
        $criteria->last_name = "cruise";


        $fetcher = new FoursquareFetcher($criteria);

        $manager = new FoursquareManager($fetcher);
        $actualList = $manager->execute();

        $expectedList = new SearchList('foursquare');

        $this->assertEquals($expectedList, $actualList);
    }
}
