<?php
namespace Skopenow\SearchTest\Fetching\Fetchers;

use Skopenow\Search\Fetching\Fetchers\YellowpagesFetcher;
use Skopenow\Search\Models\Criteria;
use Skopenow\Search\Models\SearchList;
use Skopenow\Search\Models\SearchResult;
use App\DataTypes\Name;
use App\DataTypes\Address;

class YellowpagesFetcherTest extends \TestCase
{
    public function testFetching()
    {
        setUrlMock("http://people.yellowpages.com/reversephonelookup?phone=5162205847", file_get_contents(__DIR__ . '/../../data/Yellowpages-Search-RobDouglas.html'));
        
        $criteria = new Criteria;
        $criteria->phone = "5162205847";
        
        $fetcher = new YellowpagesFetcher($criteria);
        $fetcher->execute();
        $actualList = $fetcher->getOutput();
        
        $expectedList = new SearchList('yellowpages');
        $expectedList->setUrl("http://people.yellowpages.com/reversephonelookup?phone=5162205847");
        
        $result = new SearchResult('http://people.yellowpages.com/browse.php/4THCtRf3/dJ2uKECt/vTPFhYm0/guOVJ9H1/_2B27dri/KHDsViMs/cnh5Emdv/AmFcaA8h/4gQKsqhW/903Fk_2F/J_2F1xSE/l5QwyKVD/LMMDj4k9/Kyu6D2zg/ZKjmfc9E/nvcAZThA/75I3oS5D/oMHhat0p/09_2Bvon/WtN7jvqh/ha5pxxAr/NeTOYI1i/iM7vCZI4/vl91QLnH/sqQSVDyU/nSQj5eAS/tvuoeN73/f8ghIzgT/O0_2BhUQ/54fDdMd7/9GLFKrx3/Ho7ka2ev/gAK6R7Mb/wv3cdZMs/gc6WJb9o/0tPeiIZH/ZHLHe9ZA/jXsx70LH/d_2FlgPc/FPA_3D/b13/');
        $result->setIsProfile(false);
        $result->orderInList = 0;
        $result->addName(Name::create(['full_name'=>'Robert B Douglas'],$result->mainSource));
        $result->addLocation(Address::create(['full_address'=>'Snohomish, WA'],$result->mainSource));
        $expectedList->addResult($result);
        
        $this->assertEquals($expectedList, $actualList);
    }
    
    public function testExceedingLimit()
    {
        setUrlMock("http://people.yellowpages.com/reversephonelookup?phone=5162205847", file_get_contents(__DIR__ . '/../../data/Yellowpages-Search-RobDouglas_multi.html'));
        
        $criteria = new Criteria;
        $criteria->phone = "5162205847";
        
        $fetcher = new YellowpagesFetcher($criteria);
        $fetcher->maxResults = 1;
        $fetcher->execute();
        $actualList = $fetcher->getOutput();
        
        $expectedList = new SearchList('yellowpages');
        $expectedList->setUrl("http://people.yellowpages.com/reversephonelookup?phone=5162205847");
        
        $result = new SearchResult('http://people.yellowpages.com/browse.php/4THCtRf3/dJ2uKECt/vTPFhYm0/guOVJ9H1/_2B27dri/KHDsViMs/cnh5Emdv/AmFcaA8h/4gQKsqhW/903Fk_2F/J_2F1xSE/l5QwyKVD/LMMDj4k9/Kyu6D2zg/ZKjmfc9E/nvcAZThA/75I3oS5D/oMHhat0p/09_2Bvon/WtN7jvqh/ha5pxxAr/NeTOYI1i/iM7vCZI4/vl91QLnH/sqQSVDyU/nSQj5eAS/tvuoeN73/f8ghIzgT/O0_2BhUQ/54fDdMd7/9GLFKrx3/Ho7ka2ev/gAK6R7Mb/wv3cdZMs/gc6WJb9o/0tPeiIZH/ZHLHe9ZA/jXsx70LH/d_2FlgPc/FPA_3D/b13/');
        $result->setIsProfile(false);
        $result->orderInList = 0;
        $result->addName(Name::create(['full_name'=>'Robert B Douglas'],$result->mainSource));
        $result->addLocation(Address::create(['full_address'=>'Snohomish, WA'],$result->mainSource));
        $expectedList->addResult($result);
        
        $this->assertEquals($expectedList, $actualList);
    }
    
    public function testBadResponse()
    {
        setUrlMock("http://people.yellowpages.com/reversephonelookup?phone=5162205847", file_get_contents(__DIR__ . '/../../data/Yellowpages-Search-RobDouglas.html'), "HTTP/1.1 404 Not Found");
        
        $criteria = new Criteria;
        $criteria->phone = "5162205847";
        
        $fetcher = new YellowpagesFetcher($criteria);
        $fetcher->execute();
        $actualList = $fetcher->getOutput();
        
        $expectedList = new SearchList('yellowpages');
        $expectedList->setUrl("http://people.yellowpages.com/reversephonelookup?phone=5162205847");
        
        $this->assertEquals($expectedList, $actualList);
    }
    
    public function testEmptyResponse()
    {
        setUrlMock("http://people.yellowpages.com/reversephonelookup?phone=5162205847", "");
        
        $criteria = new Criteria;
        $criteria->phone = "5162205847";
        
        $fetcher = new YellowpagesFetcher($criteria);
        $fetcher->execute();
        $actualList = $fetcher->getOutput();
        
        $expectedList = new SearchList('yellowpages');
        $expectedList->setUrl("http://people.yellowpages.com/reversephonelookup?phone=5162205847");
        
        $this->assertEquals($expectedList, $actualList);
    }
    
    public function testEmptyResultData()
    {
        setUrlMock("http://people.yellowpages.com/reversephonelookup?phone=5162205847", "not valid html");
        
        $criteria = new Criteria;
        $criteria->phone = "5162205847";
        
        $fetcher = new YellowpagesFetcher($criteria);
        $fetcher->execute();
        $actualList = $fetcher->getOutput();
        
        $expectedList = new SearchList('yellowpages');
        $expectedList->setUrl("http://people.yellowpages.com/reversephonelookup?phone=5162205847");
        
        $this->assertEquals($expectedList, $actualList);
    }
}

