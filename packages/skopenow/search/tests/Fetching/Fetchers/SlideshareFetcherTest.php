<?php
namespace Skopenow\SearchTest\Fetching\Fetchers;

use Skopenow\Search\Fetching\Fetchers\SlideshareFetcher;
use Skopenow\Search\Models\Criteria;
use Skopenow\Search\Models\SearchList;
use Skopenow\Search\Models\SearchResult;
use App\DataTypes\Name;

class SlideshareFetcherTest extends \TestCase
{
    public function testFetching()
    {
        setUrlMock("http://www.slideshare.net/search/?q=rob+douglas", file_get_contents(__DIR__ . '/../../data/Slideshare-Search-RobDouglas.html'));
        
        $criteria = new Criteria;
        $criteria->first_name = "rob";
        $criteria->last_name = "douglas";
        
        $fetcher = new SlideshareFetcher($criteria);
        $fetcher->execute();
        $actualList = $fetcher->getOutput();
        
        $expectedList = new SearchList('slideshare');
        $expectedList->setUrl('http://www.slideshare.net/search/?q=rob+douglas');
        
        $result = new SearchResult('http://www.slideshare.net/robd750');
        
        $result->username = 'robd750';
        $result->orderInList = 0;
        $result->addName(Name::create(["full_name"=>"Rob Douglas"],$result->mainSource));
        $expectedList->addResult($result);
        
        $result = new SearchResult('http://www.slideshare.net/RobDouglas4');
        
        $result->username = 'RobDouglas4';
        $result->orderInList = 1;
        $result->addName(Name::create(["full_name"=>"Rob Douglas"],$result->mainSource));
        $expectedList->addResult($result);
        
        $result = new SearchResult('http://www.slideshare.net/Flashdomain');
        
        $result->username = 'Flashdomain';
        $result->orderInList = 2;
        $expectedList->addResult($result);

        $result = new SearchResult('http://www.slideshare.net/alexiskold');
        
        
        $result->username = 'alexiskold';
        $result->orderInList = 3;
        $result->addName(Name::create(["full_name"=>"Alex Iskold"],$result->mainSource));
        $expectedList->addResult($result);
        
        $result = new SearchResult('http://www.slideshare.net/IntergenNZ');
        
        $result->username = 'IntergenNZ';
        $result->orderInList = 4;
        $expectedList->addResult($result);
        
        
        $this->assertEquals($expectedList, $actualList);
    }
    
    public function testExceedingLimit()
    {
        setUrlMock("http://www.slideshare.net/search/?q=rob+douglas", file_get_contents(__DIR__ . '/../../data/Slideshare-Search-RobDouglas.html'));        
        
        $criteria = new Criteria;
        $criteria->first_name = "rob";
        $criteria->last_name = "douglas";
        
        $fetcher = new SlideshareFetcher($criteria);
        $fetcher->maxResults = 1;
        $fetcher->execute();
        $actualList = $fetcher->getOutput();
        
        $expectedList = new SearchList('slideshare');
        $expectedList->setUrl('http://www.slideshare.net/search/?q=rob+douglas');
        
        $result = new SearchResult('http://www.slideshare.net/robd750');
        
        $result->username = 'robd750';
        $result->orderInList = 0;
        $result->addName(Name::create(["full_name"=>"Rob Douglas"],$result->mainSource));
        $expectedList->addResult($result);
        
        $this->assertEquals($expectedList, $actualList);
    }
    
    public function testBadResponse()
    {
        setUrlMock("http://www.slideshare.net/search/?q=rob+douglas", file_get_contents(__DIR__ . '/../../data/Slideshare-Search-RobDouglas.html'), "HTTP/1.1 404 Not Found");

        $criteria = new Criteria;
        $criteria->first_name = "rob";
        $criteria->last_name = "douglas";

        $fetcher = new SlideshareFetcher($criteria);
        $fetcher->execute();
        $actualList = $fetcher->getOutput();

        $expectedList = new SearchList('slideshare');
        $expectedList->setUrl('http://www.slideshare.net/search/?q=rob+douglas');

        $this->assertEquals($expectedList, $actualList);
    }
    
    public function testEmptyResponse()
    {
        setUrlMock("http://www.slideshare.net/search/?q=robbbbbbbbbbbb+douglassssssssssss", "<html></html>");
        
        $criteria = new Criteria;
        $criteria->first_name = "robbbbbbbbbbbb";
        $criteria->last_name = "douglassssssssssss";
        
        $fetcher = new SlideshareFetcher($criteria);
        $fetcher->execute();
        $actualList = $fetcher->getOutput();

        $expectedList = new SearchList('slideshare');
        $expectedList->setUrl('http://www.slideshare.net/search/?q=robbbbbbbbbbbb+douglassssssssssss');

        $this->assertEquals($expectedList, $actualList);
    }
}
