<?php
namespace Skopenow\SearchTest\Fetching\Fetchers;

use Skopenow\Search\Fetching\Fetchers\PinterestFetcher;
use Skopenow\Search\Models\Criteria;
use Skopenow\Search\Models\SearchList;
use Skopenow\Search\Models\SearchResult;
use App\DataTypes\Name;
use App\DataTypes\Address;
use App\DataTypes\Work;
use App\DataTypes\School;
use App\DataTypes\Email;
use App\DataTypes\Phone;
use App\DataTypes\Age;
use App\DataTypes\Username;

class PinterestFetcherTest extends \TestCase
{
    public function testFetching()
    {
        setUrlMock("http://www.pinterest.com/search/people/?q=tom+cruise", file_get_contents(__DIR__ . '/../../data/Pinterest-Search-TomCruise.json'));

        setUrlMock("https://www.pinterest.com/tomcruisecom", file_get_contents(__DIR__ . '/../../data/Pinterest-Profile-TomCruise.html'));

         setUrlMock("http://www.pinterest.com/tomcruisecom", file_get_contents(__DIR__ . '/../../data/Pinterest-Profile-TomCruise.html'));


        setUrlMock("http://www.pinterest.com/gvcxvc", file_get_contents(__DIR__ . '/../../data/Pinterest-Profile-gvcxvc.html'));

        setUrlMock("http://www.pinterest.com/tomcruise11", file_get_contents(__DIR__ . '/../../data/Pinterest-Profile-tomcruise11.html'));

        setUrlMock("http://www.pinterest.com/mrstomc", file_get_contents(__DIR__ . '/../../data/Pinterest-Profile-mrstomc.html'));

        setUrlMock("http://www.pinterest.com/cruise0614", file_get_contents(__DIR__ . '/../../data/Pinterest-Profile-cruise0614.html'));

        setUrlMock("https://www.facebook.com/app_scoped_user_id/560702824", file_get_contents(__DIR__ . '/../../data/Pinterest-Facebook-Profile-planetdata.html'));

        setUrlMock("https://www.facebook.com/app_scoped_user_id/560702824/", file_get_contents(__DIR__ . '/../../data/Pinterest-Facebook-Profile-planetdata.html'));

        $criteria = new Criteria;
        $criteria->first_name = "tom";
        $criteria->last_name = "cruise";
        
        $fetcher = new PinterestFetcher($criteria);
        $fetcher->execute();
        $actualList = $fetcher->getOutput();

        $expectedList = new SearchList('pinterest');
        $expectedList->setUrl('http://www.pinterest.com/search/people/?q=tom+cruise');
        
        $result = new SearchResult('http://pinterest.com/tomcruisecom');
        $result->setIsProfile(true);
        $result->username = 'tomcruisecom';
        $result->orderInList = 0;
        $result->source = "pinterest";
        $result->mainSource = "pinterest";
        $result->addName(Name::create(['full_name' => "Tom Cruise"], $result->mainSource));
        $result->addLink(['url'=>'https://twitter.com/TomCruise','reason'=>2]);
        $result->image = 'https://s-media-cache-ak0.pinimg.com/avatars/tomcruisecom_1378947354_140.jpg';
        $expectedList->addResult($result);
        
        $result = new SearchResult('http://pinterest.com/gvcxvc');
        $result->setIsProfile(true);
        $result->username = 'gvcxvc';
        $result->orderInList = 1;
        $result->source = "pinterest";
        $result->mainSource = "pinterest";
        $result->addName(Name::create(['full_name' => "Tom Cruise"], $result->mainSource));
        $result->image = 'https://s-media-cache-ak0.pinimg.com/avatars/gvcxvc-1357277530_140.jpg';
        $result->addLink(['url'=>'https://twitter.com/TomCrui08695250','reason'=>2]);
        $expectedList->addResult($result);
        
        $result = new SearchResult('http://pinterest.com/tomcruise11');
        $result->setIsProfile(true);
        $result->username = 'tomcruise11';
        $result->orderInList = 2;
        $result->source = "pinterest";
        $result->mainSource = "pinterest";
        $result->image = 'https://s-media-cache-ak0.pinimg.com/avatars/tomcruise11_1409554181_140.jpg';
        $result->addName(Name::create(['full_name' => "Tom Cruise"], $result->mainSource));
        $expectedList->addResult($result);
        
        $result = new SearchResult('http://pinterest.com/mrstomc');
        $result->setIsProfile(true);
        $result->username = 'mrstomc';
        $result->orderInList = 3;
        $result->source = "pinterest";
        $result->mainSource = "pinterest";
        $result->addName(Name::create(['full_name' => "Mrs Tom Cruise"], $result->mainSource));
        $result->addLocation(Address::create(['full_address' => 'Boston, MA'], $result->mainSource));
        $result->image = 'https://s-media-cache-ak0.pinimg.com/avatars/mrstomc-1410727738_140.jpg';
        $expectedList->addResult($result);
        
        $result = new SearchResult('http://pinterest.com/cruise0614');
        $result->setIsProfile(true);
        $result->username = 'cruise0614';
        $result->orderInList = 4;
        $result->source = "pinterest";
        $result->mainSource = "pinterest";
        $result->addName(Name::create(['full_name' => "Tom Cruise"], $result->mainSource));
        $result->image = 'https://s-media-cache-ak0.pinimg.com/avatars/cruise0614_1402334306_140.jpg';
        $result->addLink(['url'=>'https://twitter.com/SilverTomz','reason'=>2]);
        $expectedList->addResult($result);
       
        $this->assertEquals($expectedList, $actualList);
    }

    public function testExceedingLimit()
    {
        setUrlMock("http://www.pinterest.com/search/people/?q=tom+cruise", file_get_contents(__DIR__ . '/../../data/Pinterest-Search-TomCruise.json'));

        setUrlMock("http://www.pinterest.com/tomcruisecom", file_get_contents(__DIR__ . '/../../data/Pinterest-Profile-TomCruise.html'));
        
        $criteria = new Criteria;
        $criteria->first_name = "tom";
        $criteria->last_name = "cruise";
        
        $fetcher = new PinterestFetcher($criteria);
        $fetcher->maxResults = 1;
        $fetcher->execute();
        $actualList = $fetcher->getOutput();

        $expectedList = new SearchList('pinterest');
        $expectedList->setUrl('http://www.pinterest.com/search/people/?q=tom+cruise');
        
        $result = new SearchResult('http://pinterest.com/tomcruisecom');
        $result->setIsProfile(true);
        $result->username = 'tomcruisecom';
        $result->orderInList = 0;
        $result->source = "pinterest";
        $result->mainSource = "pinterest";
        $result->addName(Name::create(['full_name' => "Tom Cruise"], $result->mainSource));
        $result->image = 'https://s-media-cache-ak0.pinimg.com/avatars/tomcruisecom_1378947354_140.jpg';
        $result->addLink(['url'=>'https://twitter.com/TomCruise','reason'=>2]);
        $expectedList->addResult($result);
        
        $this->assertEquals($expectedList, $actualList);
    }

    public function testBadResponse()
    {
        setUrlMock("http://www.pinterest.com/search/people/?q=tom+cruise", file_get_contents(__DIR__ . '/../../data/Pinterest-Search-TomCruise.json'), "HTTP/1.1 404 Not Found");
        
        $criteria = new Criteria;
        $criteria->first_name = "tom";
        $criteria->last_name = "cruise";
        
        $fetcher = new PinterestFetcher($criteria);
        $fetcher->execute();
        $actualList = $fetcher->getOutput();
        
        $expectedList = new SearchList('pinterest');
        $expectedList->setUrl('http://www.pinterest.com/search/people/?q=tom+cruise');
        
        $this->assertEquals($expectedList, $actualList);
    }
    
    public function testEmptyResponse()
    {
        setUrlMock("http://www.pinterest.com/search/people/?q=tom+cruise", "");
        
        $criteria = new Criteria;
        $criteria->first_name = "tom";
        $criteria->last_name = "cruise";
        
        $fetcher = new PinterestFetcher($criteria);
        $fetcher->execute();
        $actualList = $fetcher->getOutput();
        
        $expectedList = new SearchList('pinterest');
        $expectedList->setUrl('http://www.pinterest.com/search/people/?q=tom+cruise');
        
        $this->assertEquals($expectedList, $actualList);
    }
    
    public function testEmptyResults()
    {
        setUrlMock("http://www.pinterest.com/search/people/?q=tom+cruise", file_get_contents(__DIR__ . '/../../data/Pinterest-Search-TomCruise-NoResults.json'));
        
        $criteria = new Criteria;
        $criteria->first_name = "tom";
        $criteria->last_name = "cruise";
        
        $fetcher = new PinterestFetcher($criteria);
        $fetcher->execute();
        $actualList = $fetcher->getOutput();
        
        $expectedList = new SearchList('pinterest');
        $expectedList->setUrl('http://www.pinterest.com/search/people/?q=tom+cruise');
        
        $this->assertEquals($expectedList, $actualList);
    }
}
