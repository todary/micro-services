<?php
namespace Skopenow\SearchTest\Fetching\Fetchers;

use Skopenow\Search\Fetching\Fetchers\GoogleGCSEFetcher;
use Skopenow\Search\Models\Criteria;
use Skopenow\Search\Models\SearchList;
use Skopenow\Search\Models\SearchResult;

class GoogleGCSEFetcherTest extends \TestCase
{
    public function testFetching()
    {
        setUrlMock("https://www.googleapis.com/customsearch/v1?key=AIzaSyB-ZavpRm9ia1QSMHMQYexXjw50z0FX5fU&cx=014046186005632967208%3Ae5ct1qrq4j8&fields=items%28link%2Ctitle%29%2Cqueries%2CsearchInformation&q=%22rob+douglas%22", file_get_contents(__DIR__ . '/../../data/GCSE-Search-RobDouglas.json'));
        
        $criteria = new Criteria;
        $criteria->full_name = "rob douglas";
        
        $fetcher = new GoogleGCSEFetcher($criteria);
        $fetcher->execute();
        $actualList = $fetcher->getOutput();

        $expectedList = new SearchList('googleGCSE');
        $expectedList->setUrl('https://www.googleapis.com/customsearch/v1?key=AIzaSyB-ZavpRm9ia1QSMHMQYexXjw50z0FX5fU&cx=014046186005632967208%3Ae5ct1qrq4j8&fields=items%28link%2Ctitle%29%2Cqueries%2CsearchInformation&q=%22rob+douglas%22');
        
        $result = new SearchResult('https://en.wikipedia.org/wiki/Rob_Douglas');
        
        $result->unique_url = "http://en.wikipedia.org/wiki/rob_douglas";
        $result->orderInList = 0;
        $result->title = 'Rob Douglas - Wikipedia';
        $expectedList->addResult($result);
        
        
        $result = new SearchResult('http://www.identitytheft.info/robdouglas.aspx');
        
        $result->orderInList = 1;
        $result->title = "Identity Theft Expert Rob Douglas' Biography";
        $expectedList->addResult($result);
        
        
        $result = new SearchResult('https://www.linkedin.com/in/robdouglas01');
        
        $result->orderInList = 2;
        $result->title = "Rob Douglas | Professional Profile";
        $expectedList->addResult($result);


        $result = new SearchResult('https://www.facebook.com/public/Rob-Douglas');
        
        $result->orderInList = 3;
        $result->title = "Rob Douglas Profiles | Facebook";
        $expectedList->addResult($result);
        
        
        $result = new SearchResult('https://ca.linkedin.com/in/robmdouglas');
        
        $result->orderInList = 4;
        $result->title = "Rob Douglas | Professional Profile";
        $expectedList->addResult($result);
        
        
        $this->assertEquals($expectedList, $actualList);
    }

    public function testExceedingLimit()
    {
        setUrlMock("https://www.googleapis.com/customsearch/v1?key=AIzaSyB-ZavpRm9ia1QSMHMQYexXjw50z0FX5fU&cx=014046186005632967208%3Ae5ct1qrq4j8&fields=items%28link%2Ctitle%29%2Cqueries%2CsearchInformation&q=%22rob+douglas%22", file_get_contents(__DIR__ . '/../../data/GCSE-Search-RobDouglas.json'));
        
        $criteria = new Criteria;
        $criteria->full_name = "rob douglas";
        
        $fetcher = new GoogleGCSEFetcher($criteria);
        $fetcher->maxResults = 1;
        $fetcher->execute();
        $actualList = $fetcher->getOutput();
        
        $expectedList = new SearchList('googleGCSE');
        $expectedList->setUrl('https://www.googleapis.com/customsearch/v1?key=AIzaSyB-ZavpRm9ia1QSMHMQYexXjw50z0FX5fU&cx=014046186005632967208%3Ae5ct1qrq4j8&fields=items%28link%2Ctitle%29%2Cqueries%2CsearchInformation&q=%22rob+douglas%22');
        
        $result = new SearchResult('https://en.wikipedia.org/wiki/Rob_Douglas');
        
        $result->orderInList = 0;
        $result->title = 'Rob Douglas - Wikipedia';
        $expectedList->addResult($result);
        
        $this->assertEquals($expectedList, $actualList);
    }

    public function testBadResponse()
    {

        setUrlMock("https://www.googleapis.com/customsearch/v1?key=AIzaSyB-ZavpRm9ia1QSMHMQYexXjw50z0FX5fU&cx=014046186005632967208%3Ae5ct1qrq4j8&fields=items%28link%2Ctitle%29%2Cqueries%2CsearchInformation&q=%22rob+douglas%22", "HTTP/1.1 404 Not Found");

        $criteria = new Criteria;
        $criteria->full_name = "rob douglas";

        $fetcher = new GoogleGCSEFetcher($criteria);
        $fetcher->execute();
        $actualList = $fetcher->getOutput();

        $expectedList = new SearchList('googleGCSE');
        $expectedList->setUrl('https://www.googleapis.com/customsearch/v1?key=AIzaSyB-ZavpRm9ia1QSMHMQYexXjw50z0FX5fU&cx=014046186005632967208%3Ae5ct1qrq4j8&fields=items%28link%2Ctitle%29%2Cqueries%2CsearchInformation&q=%22rob+douglas%22');
        
        $this->assertEquals($expectedList, $actualList);
    }

    public function testEmptyResponse()
    {
        setUrlMock("https://www.googleapis.com/customsearch/v1?key=AIzaSyB-ZavpRm9ia1QSMHMQYexXjw50z0FX5fU&cx=014046186005632967208%3Ae5ct1qrq4j8&fields=items%28link%2Ctitle%29%2Cqueries%2CsearchInformation&q=%22rob+douglas%22", "");
       
        $criteria = new Criteria;
        $criteria->full_name = "rob douglas";

        $fetcher = new GoogleGCSEFetcher($criteria);
        $fetcher->execute();
        $actualList = $fetcher->getOutput();
        
        $expectedList = new SearchList('googleGCSE');
        $expectedList->setUrl('https://www.googleapis.com/customsearch/v1?key=AIzaSyB-ZavpRm9ia1QSMHMQYexXjw50z0FX5fU&cx=014046186005632967208%3Ae5ct1qrq4j8&fields=items%28link%2Ctitle%29%2Cqueries%2CsearchInformation&q=%22rob+douglas%22');
        
        $this->assertEquals($expectedList, $actualList);
    }

    public function testItemsEmpty()
    {
        setUrlMock("https://www.googleapis.com/customsearch/v1?key=AIzaSyB-ZavpRm9ia1QSMHMQYexXjw50z0FX5fU&cx=014046186005632967208%3Ae5ct1qrq4j8&fields=items%28link%2Ctitle%29%2Cqueries%2CsearchInformation&q=%22rob+douglas%22", file_get_contents(__DIR__ . '/../../data/GCSE-Search-RobDouglas-Items-Empty.json'));

        $criteria = new Criteria;
        $criteria->full_name = "rob douglas";

        $fetcher = new GoogleGCSEFetcher($criteria);
        $fetcher->execute();
        $actualList = $fetcher->getOutput();
        
        $expectedList = new SearchList('googleGCSE');
        $expectedList->setUrl('https://www.googleapis.com/customsearch/v1?key=AIzaSyB-ZavpRm9ia1QSMHMQYexXjw50z0FX5fU&cx=014046186005632967208%3Ae5ct1qrq4j8&fields=items%28link%2Ctitle%29%2Cqueries%2CsearchInformation&q=%22rob+douglas%22');
        
        $this->assertEquals($expectedList, $actualList);
    }
}
