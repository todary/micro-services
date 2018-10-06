<?php
namespace Skopenow\SearchTest\Managing\Managers;

use Skopenow\Search\Fetching\Fetchers\PinterestFetcher;
use Skopenow\Search\Managing\Managers\PinterestManager;
use Skopenow\Search\Models\Criteria;
use Skopenow\Search\Models\SearchList;
use Skopenow\Search\Models\SearchResult;

class PinterestManagerTest extends \TestCase
{
    public function testExecution()
    {
        setUrlMock("http://www.pinterest.com/search/people/?q=tom+cruise", file_get_contents(__DIR__ . '/../../data/Pinterest-Search-TomCruise.json'));

        $criteria = new Criteria;
        $criteria->first_name = "tom";
        $criteria->last_name = "cruise";

        $fetcher = new PinterestFetcher($criteria);

        $manager = new PinterestManager($fetcher);
        $actualList = $manager->execute();

       $expectedList = new SearchList('pinterest');
        $expectedList->setUrl('http://www.pinterest.com/search/people/?q=tom+cruise');
        
        $result = new SearchResult('http://pinterest.com/tomcruisecom');
        $result->setIsProfile(true);
        $result->username = 'tomcruisecom';
        $result->orderInList = 0;
        $result->addName('Tom Cruise');
        $result->image = 'https://s-media-cache-ak0.pinimg.com/avatars/tomcruisecom_1378947354_140.jpg';
        $expectedList->addResult($result);
        
        
        $result = new SearchResult('http://pinterest.com/gvcxvc');
        $result->setIsProfile(true);
        $result->username = 'gvcxvc';
        $result->orderInList = 1;
        $result->addName('Tom Cruise');
        $result->image = 'https://s-media-cache-ak0.pinimg.com/avatars/gvcxvc-1357277530_140.jpg';
        $expectedList->addResult($result);
        
        $result = new SearchResult('http://pinterest.com/tomcruise11');
        $result->setIsProfile(true);
        $result->username = 'tomcruise11';
        $result->orderInList = 2;
        $result->addName('Tom Cruise');
        $result->image = 'https://s-media-cache-ak0.pinimg.com/avatars/tomcruise11_1409554181_140.jpg';
        $expectedList->addResult($result);
        
        
        $result = new SearchResult('http://pinterest.com/mrstomc');
        $result->setIsProfile(true);
        $result->username = 'mrstomc';
        $result->orderInList = 3;
        $result->addName('Mrs Tom Cruise');
        $result->addLocation('Boston, MA');
        $result->image = 'https://s-media-cache-ak0.pinimg.com/avatars/mrstomc-1410727738_140.jpg';
        $expectedList->addResult($result);
        
        
        $result = new SearchResult('http://pinterest.com/cruise0614');
        $result->setIsProfile(true);
        $result->username = 'cruise0614';
        $result->orderInList = 4;
        $result->addName('Tom Cruise');
        $result->image = 'https://s-media-cache-ak0.pinimg.com/avatars/cruise0614_1402334306_140.jpg';
        $expectedList->addResult($result);
        
        $this->assertEquals($expectedList, $actualList);
    }

    public function testNoResponse()
    {
setUrlMock("http://www.pinterest.com/search/people/?q=tom+cruise", file_get_contents(__DIR__ . '/../../data/Pinterest-Search-TomCruise.json'), "HTTP/1.1 404 Not Found");
        $criteria = new Criteria;
        $criteria->first_name = "tom";
        $criteria->last_name = "cruise";


        $fetcher = new PinterestFetcher($criteria);

        $manager = new PinterestManager($fetcher);
        $actualList = $manager->execute();

        $expectedList = new SearchList('pinterest');

        $this->assertEquals($expectedList, $actualList);
    }
}
