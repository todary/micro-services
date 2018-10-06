<?php
namespace Skopenow\SearchTest\Managing\Managers;

use Skopenow\Search\Fetching\Fetchers\GoogleGCSEFetcher;
use Skopenow\Search\Managing\Managers\GoogleGCSEManager;
use Skopenow\Search\Models\Criteria;
use Skopenow\Search\Models\SearchList;
use Skopenow\Search\Models\SearchResult;

class GoogleGCSEManagerTest extends \TestCase
{
    public function testExecution()
    {
        setUrlMock("https://www.googleapis.com/customsearch/v1?key=AIzaSyB-ZavpRm9ia1QSMHMQYexXjw50z0FX5fU&cx=014046186005632967208:e5ct1qrq4j8&fields=items(link,title),queries,searchInformation&q=rob+douglas", file_get_contents(__DIR__ . '/../../data/GCSE-Search-RobDouglas.json'));

        $criteria = new Criteria;
        $criteria->first_name = "rob";
        $criteria->last_name = "douglas";

        $fetcher = new GoogleGCSEFetcher($criteria);

        $manager = new GoogleGCSEManager($fetcher);
        $actualList = $manager->execute();

        $expectedList = new SearchList('googleGCSE');
        $expectedList->setUrl('https://www.googleapis.com/customsearch/v1?key=AIzaSyB-ZavpRm9ia1QSMHMQYexXjw50z0FX5fU&cx=014046186005632967208:e5ct1qrq4j8&fields=items(link,title),queries,searchInformation&q=rob+douglas');
        
        $result = new SearchResult('https://en.wikipedia.org/wiki/Rob_Douglas');
        
        $result->orderInList = 0;
        $result->title = 'Rob Douglas - Wikipedia';
        $expectedList->addResult($result);
        
        
        $result = new SearchResult('http://www.identitytheft.info/robdouglas.aspx');
        
        $result->orderInList = 1;
        $result->title = "Identity Theft Expert Rob Douglas' Biography";
        $expectedList->addResult($result);
        
        
        $result = new SearchResult('https://en.wikipedia.org/wiki/Robert_Douglas_(footballer)');
        
        $result->orderInList = 2;
        $result->title = "Robert Douglas (footballer) - Wikipedia";
        $expectedList->addResult($result);


        $result = new SearchResult('https://www.facebook.com/public/Rob-Douglas');
        
        $result->orderInList = 3;
        $result->title = "Rob Douglas Profiles | Facebook";
        $expectedList->addResult($result);
        
        
        $result = new SearchResult('https://twitter.com/robmdouglas7?lang=en');
        
        $result->orderInList = 4;
        $result->title = "Rob Douglas (@RobMDouglas7) | Twitter";
        $expectedList->addResult($result);
        
        $this->assertEquals($expectedList, $actualList);
    }
    
    public function testExecutionInvalidUrl()
    {
        setUrlMock("https://www.googleapis.com/customsearch/v1?key=AIzaSyB-ZavpRm9ia1QSMHMQYexXjw50z0FX5fU&cx=014046186005632967208:e5ct1qrq4j8&fields=items(link,title),queries,searchInformation&q=rob+douglas", file_get_contents(__DIR__ . '/../../data/GCSE-Search-RobDouglas-InvalidUrl.json'));

        $criteria = new Criteria;
        $criteria->first_name = "rob";
        $criteria->last_name = "douglas";

        $fetcher = new GoogleGCSEFetcher($criteria);

        $manager = new GoogleGCSEManager($fetcher);
        $actualList = $manager->execute();

        $expectedList = new SearchList('googleGCSE');
        $expectedList->setUrl('https://www.googleapis.com/customsearch/v1?key=AIzaSyB-ZavpRm9ia1QSMHMQYexXjw50z0FX5fU&cx=014046186005632967208:e5ct1qrq4j8&fields=items(link,title),queries,searchInformation&q=rob+douglas');
        
        $result = new SearchResult('en.wikipedia.org/wiki/Rob_Douglas');
        
        $result->orderInList = 0;
        $result->title = 'Rob Douglas - Wikipedia';
        $expectedList->addResult($result);
        
        
        $result = new SearchResult('http://www.identitytheft.info/robdouglas.aspx');
        
        $result->orderInList = 1;
        $result->title = "Identity Theft Expert Rob Douglas' Biography";
        $expectedList->addResult($result);
        
        
        $result = new SearchResult('https://en.wikipedia.org/wiki/Robert_Douglas_(footballer)');
        
        $result->orderInList = 2;
        $result->title = "Robert Douglas (footballer) - Wikipedia";
        $expectedList->addResult($result);


        $result = new SearchResult('https://www.facebook.com/public/Rob-Douglas');
        
        $result->orderInList = 3;
        $result->title = "Rob Douglas Profiles | Facebook";
        $expectedList->addResult($result);
        
        
        $result = new SearchResult('https://twitter.com/robmdouglas7?lang=en');
        
        $result->orderInList = 4;
        $result->title = "Rob Douglas (@RobMDouglas7) | Twitter";
        $expectedList->addResult($result);
        
        $this->assertEquals($expectedList, $actualList);
    }
    
    public function testExecutionCheckIsBanned()
    {
        setUrlMock("https://www.googleapis.com/customsearch/v1?key=AIzaSyB-ZavpRm9ia1QSMHMQYexXjw50z0FX5fU&cx=014046186005632967208:e5ct1qrq4j8&fields=items(link,title),queries,searchInformation&q=rob+douglas", file_get_contents(__DIR__ . '/../../data/GCSE-Search-RobDouglas-bannedList.json'));

        $criteria = new Criteria;
        $criteria->first_name = "rob";
        $criteria->last_name = "douglas";

        $fetcher = new GoogleGCSEFetcher($criteria);

        $manager = new GoogleGCSEManager($fetcher);
        $actualList = $manager->execute();

        $expectedList = new SearchList('googleGCSE');
        $expectedList->setUrl('https://www.googleapis.com/customsearch/v1?key=AIzaSyB-ZavpRm9ia1QSMHMQYexXjw50z0FX5fU&cx=014046186005632967208:e5ct1qrq4j8&fields=items(link,title),queries,searchInformation&q=rob+douglas');
        
        $result = new SearchResult('http://en.wikipedia.org/wiki/Rob_Douglas');
        
        $result->orderInList = 0;
        $result->title = 'Rob Douglas - Wikipedia';
        $expectedList->addResult($result);
        
        
        $result = new SearchResult('http://www.Whitepages.com');
        
        $result->orderInList = 1;
        $result->title = "Identity Theft Expert Rob Douglas' Biography";
        $expectedList->addResult($result);
        
        
        $result = new SearchResult('https://en.wikipedia.org/wiki/Robert_Douglas_(footballer)');
        
        $result->orderInList = 2;
        $result->title = "Robert Douglas (footballer) - Wikipedia";
        $expectedList->addResult($result);


        $result = new SearchResult('https://www.facebook.com/public/Rob-Douglas');
        
        $result->orderInList = 3;
        $result->title = "Rob Douglas Profiles | Facebook";
        $expectedList->addResult($result);
        
        
        $result = new SearchResult('https://twitter.com/robmdouglas7?lang=en');
        
        $result->orderInList = 4;
        $result->title = "Rob Douglas (@RobMDouglas7) | Twitter";
        $expectedList->addResult($result);
        
        $this->assertEquals($expectedList, $actualList);
    }
    
    public function testExecutionBannedTitle()
    {
        setUrlMock("https://www.googleapis.com/customsearch/v1?key=AIzaSyB-ZavpRm9ia1QSMHMQYexXjw50z0FX5fU&cx=014046186005632967208:e5ct1qrq4j8&fields=items(link,title),queries,searchInformation&q=rob+douglas", file_get_contents(__DIR__ . '/../../data/GCSE-Search-RobDouglas-bannedTitle.json'));

        $criteria = new Criteria;
        $criteria->first_name = "rob";
        $criteria->last_name = "douglas";

        $fetcher = new GoogleGCSEFetcher($criteria);

        $manager = new GoogleGCSEManager($fetcher);
        $actualList = $manager->execute();

        $expectedList = new SearchList('googleGCSE');
        $expectedList->setUrl('https://www.googleapis.com/customsearch/v1?key=AIzaSyB-ZavpRm9ia1QSMHMQYexXjw50z0FX5fU&cx=014046186005632967208:e5ct1qrq4j8&fields=items(link,title),queries,searchInformation&q=rob+douglas');
        
        $result = new SearchResult('https://en.wikipedia.org/wiki/Rob_Douglas');
        
        $result->orderInList = 0;
        $result->title = 'Rob Douglas - Wikipedia';
        $expectedList->addResult($result);
        
        
        $result = new SearchResult('http://www.identitytheft.info/robdouglas.aspx');
        
        $result->orderInList = 1;
        $result->title = "Whitepages";
        $expectedList->addResult($result);
        
        
        $result = new SearchResult('https://en.wikipedia.org/wiki/Robert_Douglas_(footballer)');
        
        $result->orderInList = 2;
        $result->title = "Robert Douglas (footballer) - Wikipedia";
        $expectedList->addResult($result);


        $result = new SearchResult('https://www.facebook.com/public/Rob-Douglas');
        
        $result->orderInList = 3;
        $result->title = "Rob Douglas Profiles | Facebook";
        $expectedList->addResult($result);
        
        
        $result = new SearchResult('https://twitter.com/robmdouglas7?lang=en');
        
        $result->orderInList = 4;
        $result->title = "Rob Douglas (@RobMDouglas7) | Twitter";
        $expectedList->addResult($result);
        
        $this->assertEquals($expectedList, $actualList);
    }

    public function testNoResponse()
    {
        setUrlMock("https://www.googleapis.com/customsearch/v1?key=AIzaSyB-ZavpRm9ia1QSMHMQYexXjw50z0FX5fU&cx=014046186005632967208:e5ct1qrq4j8&fields=items(link,title),queries,searchInformation&q=rob+douglas", file_get_contents(__DIR__ . '/../../data/GCSE-Search-RobDouglas.json'), "HTTP/1.1 404 Not Found");

        $criteria = new Criteria;
        $criteria->first_name = "rob";
        $criteria->last_name = "douglas";


        $fetcher = new GoogleGCSEFetcher($criteria);

        $manager = new GoogleGCSEManager($fetcher);
        $actualList = $manager->execute();

        $expectedList = new SearchList('googleGCSE');

        $this->assertEquals($expectedList, $actualList);
    }
}
