<?php
namespace Skopenow\SearchTest\Managing\Managers;

use Skopenow\Search\Fetching\Fetchers\SlideshareFetcher;
use Skopenow\Search\Managing\Managers\SlideshareManager;
use Skopenow\Search\Models\Criteria;
use Skopenow\Search\Models\SearchList;
use Skopenow\Search\Models\SearchResult;

class SlideshareManagerTest extends \TestCase
{
    public function testExecution()
    {
        setUrlMock("http://www.slideshare.net/search/?q=rob+douglas", file_get_contents(__DIR__ . '/../../data/Slideshare-Search-RobDouglas.html'));

        $criteria = new Criteria;
        $criteria->first_name = "rob";
        $criteria->last_name = "douglas";

        $fetcher = new SlideshareFetcher($criteria);

        $manager = new SlideshareManager($fetcher);
        $actualList = $manager->execute();

        $expectedList = new SearchList('slideshare');
        $expectedList->setUrl('http://www.slideshare.net/search/?q=rob+douglas');

        $result = new SearchResult('http://www.slideshare.net/robd750');
        
        $result->username = 'robd750';
        $result->orderInList = 0;
        $result->addName('Rob Douglas');
        $expectedList->addResult($result);
        
        
        $result = new SearchResult('http://www.slideshare.net/RobDouglas4');
        
        $result->username = 'RobDouglas4';
        $result->orderInList = 1;
        $result->addName('Rob Douglas');
        $expectedList->addResult($result);
        
        
        $result = new SearchResult('http://www.slideshare.net/Flashdomain');
        
        $result->username = 'Flashdomain';
        $result->orderInList = 2;
        $expectedList->addResult($result);

        
        $result = new SearchResult('http://www.slideshare.net/alexiskold');
        
        $result->username = 'alexiskold';
        $result->orderInList = 3;
        $result->addName('Alex Iskold');
        $expectedList->addResult($result);
        
        $result = new SearchResult('http://www.slideshare.net/IntergenNZ');
        
        $result->username = 'IntergenNZ';
        $result->orderInList = 4;
        $expectedList->addResult($result);

        $this->assertEquals($expectedList, $actualList);
    }

    public function testNoResponse()
    {
        setUrlMock("http://www.slideshare.net/search/?q=rob+douglas", "<html></html>", "HTTP/1.1 404 Not Found");

        $criteria = new Criteria;
        $criteria->first_name = "rob";
        $criteria->last_name = "douglas";


        $fetcher = new SlideshareFetcher($criteria);

        $manager = new SlideshareManager($fetcher);
        $actualList = $manager->execute();

        $expectedList = new SearchList('slideshare');

        $this->assertEquals($expectedList, $actualList);
    }
}
