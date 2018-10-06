<?php
namespace Skopenow\SearchTest\Managing\Managers;

use Skopenow\Search\Fetching\Fetchers\GoogleplusFetcher;
use Skopenow\Search\Managing\Managers\GoogleplusManager;
use Skopenow\Search\Models\Criteria;
use Skopenow\Search\Models\SearchList;
use Skopenow\Search\Models\SearchResult;

class GoogleplusManagerTest extends \TestCase
{
    public function testExecution()
    {
        setUrlMock("https://plus.google.com/_/PlusAppUi/data", file_get_contents(__DIR__ . '/../../data/Googleplus-Search-RobDouglas.html'));

        $criteria = new Criteria;
        $criteria->first_name = "rob";
        $criteria->last_name = "douglas";

        $fetcher = new GoogleplusFetcher($criteria);

        $manager = new GoogleplusManager($fetcher);
        $actualList = $manager->execute();

        $expectedList = new SearchList('googleplus');
        $expectedList->setUrl('https://plus.google.com/s/rob%20douglas/people');

        $result = new SearchResult('https://plus.google.com/102538261020709081239');
        
        $result->username = '102538261020709081239';
        $result->orderInList = 0;
        $result->image = 'https://lh6.googleusercontent.com/-kxFhN-qTuGY/AAAAAAAAAAI/AAAAAAAAAIw/aN2nEA2Pb4Q/photo.jpg';
        $result->addName('Rob Douglas');
        $expectedList->addResult($result);
        
        
        $result = new SearchResult('https://plus.google.com/110752933937045501933');
        
        $result->username = '110752933937045501933';
        $result->orderInList = 1;
        $result->image = 'https://lh5.googleusercontent.com/-hyKfEVqcGao/AAAAAAAAAAI/AAAAAAAAGew/sDkBfJoRDS8/photo.jpg';
        $result->addName('Rob Douglas');
        $expectedList->addResult($result);    
        
        
        $result = new SearchResult('https://plus.google.com/118384634136034439609');
        
        $result->username = '118384634136034439609';
        $result->orderInList = 2;
        $result->image = 'https://lh6.googleusercontent.com/-qnQsdlKQPtk/AAAAAAAAAAI/AAAAAAAAAHc/7p3lFeYlVZ8/photo.jpg';
        $result->addName('Rob Douglas');
        $expectedList->addResult($result);    
        
        
        $result = new SearchResult('https://plus.google.com/114621836901208891303');
        
        $result->username = '114621836901208891303';
        $result->orderInList = 3;
        $result->image = 'https://lh6.googleusercontent.com/-gGXJjmaT6lY/AAAAAAAAAAI/AAAAAAAAAIE/9kwrxI15eYM/photo.jpg';
        $result->addName('Rob Douglas');
        $expectedList->addResult($result);    
        
        
        $result = new SearchResult('https://plus.google.com/110766775168069116539');
        
        $result->username = '110766775168069116539';
        $result->orderInList = 4;
        $result->image = 'https://lh6.googleusercontent.com/-3Eh9jf--5Uc/AAAAAAAAAAI/AAAAAAAAALU/2yu1i8OSoks/photo.jpg';
        $result->addName('Rob Douglas');
        $expectedList->addResult($result);   
        
        $this->assertEquals($expectedList, $actualList);
    }

    public function testNoResponse()
    {
        setUrlMock("https://plus.google.com/_/PlusAppUi/data", file_get_contents(__DIR__ . '/../../data/Googleplus-Search-RobDouglas.html'), "HTTP/1.1 404 Not Found");

        $criteria = new Criteria;
        $criteria->first_name = "rob";
        $criteria->last_name = "douglas";


        $fetcher = new GoogleplusFetcher($criteria);

        $manager = new GoogleplusManager($fetcher);
        $actualList = $manager->execute();

        $expectedList = new SearchList('googleplus');

        $this->assertEquals($expectedList, $actualList);
    }
}
