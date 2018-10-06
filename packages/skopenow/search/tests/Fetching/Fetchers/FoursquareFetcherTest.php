<?php
namespace Skopenow\SearchTest\Fetching\Fetchers;

use Skopenow\Search\Fetching\Fetchers\FoursquareFetcher;
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

class FoursquareFetcherTest extends \TestCase
{
    public function testFetching()
    {
        $apiAccount = getApiAccount("foursquare");
        setUrlMock("https://api.foursquare.com/v2/users/search?name=tom+cruise&oauth_token=".$apiAccount->password."&v=20160726", file_get_contents(__DIR__ . '/../../data/Foursquare-Search-TomCruise.json'));

        setUrlMock("https://foursquare.com/tomcruisetimbet", file_get_contents(__DIR__ . '/../../data/Foursquare-Profile-TomCruise.html'));

        setUrlMock("https://foursquare.com/user/39093674", file_get_contents(__DIR__ . '/../../data/Foursquare-Profile-TomCruise.html'));

       
        $criteria = new Criteria;
        $criteria->first_name = "tom";
        $criteria->last_name = "cruise";
        
        $fetcher = new FoursquareFetcher($criteria);
        $fetcher->execute();
        $actualList = $fetcher->getOutput();

        $expectedList = new SearchList('foursquare');
        $expectedList->setUrl("https://api.foursquare.com/v2/users/search?name=tom+cruise&oauth_token=".$apiAccount->password."&v=20160726");
        
        $result = new SearchResult('https://foursquare.com/user/39093674');
        $result->setIsProfile(true);
        $result->id = '39093674';
        $result->username = '39093674';
        $result->orderInList = 0;
        $result->source = "foursquare";
        $result->mainSource = "foursquare";
        $result->addName(Name::create(['full_name' => "tom cruise tim beta"], $result->mainSource));
        $result->addLocation(Address::create(['full_address' =>"Salvador"], $result->mainSource));
        $result->addLink(['url'=>"http://www.facebook.com/100002065880108",'reason'=>2]);
        $result->addLink(['url'=>"http://twitter.com/tomcruisetimbet",'reason'=>2]);
        $expectedList->addResult($result);
        
        $result = new SearchResult('https://foursquare.com/user/127513550');
        $result->setIsProfile(true);
        $result->id = '127513550';
        $result->username = '127513550';
        $result->image = "https://ss0.4sqi.net/img/footer-top@2x-ef6ccfa1b4ce50e9257b922d1c8935ac.png";
        $result->orderInList = 1;
        $result->source = "foursquare";
        $result->mainSource = "foursquare";
        $result->addName(Name::create(['full_name' => "Tom Cruise"], $result->mainSource));
        $result->addLocation(Address::create(['full_address' =>"İzmir, Turkey"], $result->mainSource));
        $expectedList->addResult($result);
        
        
        $result = new SearchResult('https://foursquare.com/user/4911633');
        $result->setIsProfile(true);
        $result->id = '4911633';
        $result->username = '4911633';
        $result->image = "https://ss0.4sqi.net/img/footer-top@2x-ef6ccfa1b4ce50e9257b922d1c8935ac.png";
        $result->orderInList = 2;
        $result->source = "foursquare";
        $result->mainSource = "foursquare";
        $result->addName(Name::create(['full_name' => "Tom Cruise"], $result->mainSource));
        $result->addLocation(Address::create(['full_address' =>"Bang Po, Bang Sue Thailand"], $result->mainSource));
        $expectedList->addResult($result);
        
        $result = new SearchResult('https://foursquare.com/user/122892842');
        $result->setIsProfile(true);
        $result->id = '122892842';
        $result->username = '122892842';
        $result->image = "https://ss0.4sqi.net/img/footer-top@2x-ef6ccfa1b4ce50e9257b922d1c8935ac.png";
        $result->orderInList = 3;
        $result->source = "foursquare";
        $result->mainSource = "foursquare";
        $result->addName(Name::create(['full_name' => "Tom Cruise"], $result->mainSource));
        $result->addLocation(Address::create(['full_address' =>"Syracuse (NY)"], $result->mainSource));
        $expectedList->addResult($result);
        
        $result = new SearchResult('https://foursquare.com/user/6877401');
        $result->setIsProfile(true);
        $result->id = '6877401';
        $result->username = '6877401';
        $result->image = "https://ss0.4sqi.net/img/footer-top@2x-ef6ccfa1b4ce50e9257b922d1c8935ac.png";
        $result->orderInList = 4;
        $result->source = "foursquare";
        $result->mainSource = "foursquare";
        $result->addName(Name::create(['full_name' => "tom cruise"], $result->mainSource));
        $result->addLocation(Address::create(['full_address' =>"千代田区, 日本"], $result->mainSource));
        $result->addLink(['url'=>"http://www.facebook.com/715180782",'reason'=>2]);
        $result->addLink(['url'=>"http://twitter.com/minuano_68",'reason'=>2]);
        $expectedList->addResult($result);
        
        $this->assertEquals($expectedList, $actualList);
    }
    
    public function testExceedingLimit()
    {
        $apiAccount = getApiAccount("foursquare");
        setUrlMock("https://api.foursquare.com/v2/users/search?name=tom+cruise&oauth_token=".$apiAccount->password."&v=20160726", file_get_contents(__DIR__ . '/../../data/Foursquare-Search-TomCruise.json'));
        
        $criteria = new Criteria;
        $criteria->first_name = "tom";
        $criteria->last_name = "cruise";
        
        $fetcher = new FoursquareFetcher($criteria);
        $fetcher->maxResults = 1;
        $fetcher->execute();
        $actualList = $fetcher->getOutput();
        
        $expectedList = new SearchList('foursquare');
        $expectedList->setUrl("https://api.foursquare.com/v2/users/search?name=tom+cruise&oauth_token=".$apiAccount->password."&v=20160726");
        
        $result = new SearchResult('https://foursquare.com/user/39093674');
        $result->setIsProfile(true);
        $result->id = '39093674';
        $result->username = '39093674';
        $result->orderInList = 0;
        $result->source = "foursquare";
        $result->mainSource = "foursquare";
        $result->addName(Name::create(['full_name' => "tom cruise tim beta"], $result->mainSource));
        $result->addLocation(Address::create(['full_address' =>"Salvador"], $result->mainSource));
        $result->addLink(['url'=>"http://www.facebook.com/100002065880108",'reason'=>2]);
        $result->addLink(['url'=>"http://twitter.com/tomcruisetimbet",'reason'=>2]);
        $expectedList->addResult($result);
        
        
        $this->assertEquals($expectedList, $actualList);
    }

    public function testBadResponse()
    {
        $apiAccount = getApiAccount("foursquare");
        setUrlMock("https://api.foursquare.com/v2/users/search?name=tom+cruise&oauth_token=".$apiAccount->password."&v=20160726", file_get_contents(__DIR__ . '/../../data/Foursquare-Search-TomCruise.json'), "HTTP/1.1 404 Not Found");
        
        $criteria = new Criteria;
        $criteria->first_name = "tom";
        $criteria->last_name = "cruise";

        $fetcher = new FoursquareFetcher($criteria);
        $fetcher->execute();
        $actualList = $fetcher->getOutput();

        $expectedList = new SearchList('foursquare');
        $expectedList->setUrl("https://api.foursquare.com/v2/users/search?name=tom+cruise&oauth_token=".$apiAccount->password."&v=20160726");

        $this->assertEquals($expectedList, $actualList);
    }
 
    public function testEmptyResponse()
    {
        $apiAccount = getApiAccount("foursquare");
        setUrlMock("https://api.foursquare.com/v2/users/search?name=tommmmmmm+cruiseeeeeeeeee&oauth_token=".$apiAccount->password."&v=20160726", "");
        
        $criteria = new Criteria;
        $criteria->first_name = "tommmmmmm";
        $criteria->last_name = "cruiseeeeeeeeee";

        $fetcher = new FoursquareFetcher($criteria);
        $fetcher->execute();
        $actualList = $fetcher->getOutput();

        $expectedList = new SearchList('foursquare');
        $expectedList->setUrl("https://api.foursquare.com/v2/users/search?name=tommmmmmm+cruiseeeeeeeeee&oauth_token=".$apiAccount->password."&v=20160726");

        $this->assertEquals($expectedList, $actualList);
    }
    
    public function testEmptyResultsArray()
    {
        $apiAccount = getApiAccount("foursquare");
        setUrlMock("https://api.foursquare.com/v2/users/search?name=tom+cruise&oauth_token=".$apiAccount->password."&v=20160726", file_get_contents(__DIR__ . '/../../data/Foursquare-Search-TomCruise-Empty.json'));
        
        $criteria = new Criteria;
        $criteria->first_name = "tom";
        $criteria->last_name = "cruise";

        $fetcher = new FoursquareFetcher($criteria);
        $fetcher->execute();
        $actualList = $fetcher->getOutput();

        $expectedList = new SearchList('foursquare');
        $expectedList->setUrl("https://api.foursquare.com/v2/users/search?name=tom+cruise&oauth_token=".$apiAccount->password."&v=20160726");

        $this->assertEquals($expectedList, $actualList);
    }

}