<?php
namespace Skopenow\SearchTest\Fetching\Fetchers;

use Skopenow\Search\Fetching\Fetchers\SoundcloudFetcher;
use Skopenow\Search\Models\Criteria;
use Skopenow\Search\Models\SearchList;
use Skopenow\Search\Models\SearchResult;
use App\DataTypes\Name;
use App\DataTypes\Address;

class SoundcloudFetcherTest extends \TestCase
{
    public function testFetchingFirstLastNames()
    {
        setUrlMock("https://api-mobi.soundcloud.com/search/users?q=tom+cruise&client_id=ygy2GkGPGW6Tn8m22Bz1SDplrFKBlwP0&format=json&app_version=1464963889", file_get_contents(__DIR__ . '/../../data/Soundcloud-Search-TomCruise.json'));

        setUrlMock("https://m.soundcloud.com/", file_get_contents(__DIR__ . '/../../data/Soundcloud-Mobile-TomCruise.html'));

        setUrlMock("https://soundcloud.com/tomcruiseofficial", file_get_contents(__DIR__ . '/../../data/Soundcloud-Profile-TomCruise.html'));

        setUrlMock("https://soundcloud.com/tom-cruise-8", file_get_contents(__DIR__ . '/../../data/Soundcloud-Profile-TomCruise8.html'));

        setUrlMock("https://soundcloud.com/tomcruiseprofile", file_get_contents(__DIR__ . '/../../data/Soundcloud-Profile-TomCruiseProfile.html'));

        setUrlMock("https://soundcloud.com/glitch409", file_get_contents(__DIR__ . '/../../data/Soundcloud-Profile-Glitch409.html'));

        setUrlMock("https://soundcloud.com/tom-cruise-putcha", file_get_contents(__DIR__ . '/../../data/Soundcloud-Profile-TomCruisePutcha.html'));

        $criteria = new Criteria;
        $criteria->first_name = "tom";
        $criteria->last_name = "cruise";

        $fetcher = new SoundcloudFetcher($criteria);
        $fetcher->execute();
        $actualList = $fetcher->getOutput();

        $expectedList = new SearchList('soundcloud');
        $expectedList->setUrl('https://soundcloud.com/search/people?q=tom+cruise');
        
        $result = new SearchResult('https://soundcloud.com/tomcruiseofficial');
        $result->setIsProfile(true);
        $result->source = "soundcloud";
        $result->mainSource = "soundcloud";
        $result->username = 'Tom Cruise';
        $result->screenshotUrl = 'https://soundcloud.com/tomcruiseofficial';
        $result->orderInList = 0;
        $result->image = 'https://i1.sndcdn.com/avatars-000338927891-0oxmpk-large.jpg';
        $result->addName(Name::create(["full_name"=>"Tom Cruise"], $result->mainSource));
        $expectedList->addResult($result);
        
        $result = new SearchResult('https://soundcloud.com/tom-cruise-8');
        $result->setIsProfile(true);
        $result->source = "soundcloud";
        $result->mainSource = "soundcloud";
        $result->username = 'Tom Cruise 6';
        $result->screenshotUrl = 'https://soundcloud.com/tom-cruise-8';
        $result->orderInList = 1;
        $result->image = 'https://i1.sndcdn.com/avatars-000338927891-0oxmpk-large.jpg';
        $result->addName(Name::create(["full_name"=>"Tom Cruise 6"], $result->mainSource));
        $expectedList->addResult($result);
        
        $result = new SearchResult('https://soundcloud.com/tomcruiseprofile');
        $result->setIsProfile(true);
        $result->source = "soundcloud";
        $result->mainSource = "soundcloud";
        $result->username = 'Tom Cruise 1';
        $result->screenshotUrl = 'https://soundcloud.com/tomcruiseprofile';
        $result->orderInList = 2;
        $result->image = 'https://i1.sndcdn.com/avatars-000338927891-0oxmpk-large.jpg';
        $result->addName(Name::create(["full_name"=>"Tom Cruise 1"], $result->mainSource));
        $expectedList->addResult($result);
        
        $result = new SearchResult('https://soundcloud.com/glitch409');
        $result->setIsProfile(true);
        $result->username = 'Glitch409';
        $result->screenshotUrl = 'https://soundcloud.com/glitch409';
        $result->orderInList = 3;
        $result->image = 'https://i1.sndcdn.com/avatars-000004664808-53hx3h-large.jpg';
        $result->addName(Name::create(["full_name"=>"tom cruise"], $result->mainSource));
        $result->addLocation(Address::create(["full_address"=>"Beaumont, US"], $result->mainSource));
        $expectedList->addResult($result);
        
        $result = new SearchResult('https://soundcloud.com/tom-cruise-putcha');
        $result->setIsProfile(true);
        $result->source = "soundcloud";
        $result->mainSource = "soundcloud";
        $result->username = 'Tom Cruise Putcha';
        $result->screenshotUrl = 'https://soundcloud.com/tom-cruise-putcha';
        $result->orderInList = 4;
        $result->image = 'https://i1.sndcdn.com/avatars-000338927891-0oxmpk-large.jpg';
        $result->addName(Name::create(["full_name"=>"Tom Cruise Putcha"], $result->mainSource));
        $expectedList->addResult($result);
        
        $this->assertEquals($expectedList, $actualList);
    }

    public function testFetchingFirstLastMiddleNames()
    {
        setUrlMock("https://m.soundcloud.com/", file_get_contents(__DIR__ . '/../../data/Soundcloud-Mobile-TomCruise.html'));

        setUrlMock("https://api-mobi.soundcloud.com/search/users?q=tom+cruise+Mapother&client_id=ygy2GkGPGW6Tn8m22Bz1SDplrFKBlwP0&format=json&app_version=1464963889", file_get_contents(__DIR__ . '/../../data/Soundcloud-Search-TomCruise.json'));

        setUrlMock("https://m.soundcloud.com/", file_get_contents(__DIR__ . '/../../data/Soundcloud-Mobile-TomCruise.html'));

        setUrlMock("https://soundcloud.com/tomcruiseofficial", file_get_contents(__DIR__ . '/../../data/Soundcloud-Profile-TomCruise.html'));

        setUrlMock("https://soundcloud.com/tom-cruise-8", file_get_contents(__DIR__ . '/../../data/Soundcloud-Profile-TomCruise8.html'));

        setUrlMock("https://soundcloud.com/tomcruiseprofile", file_get_contents(__DIR__ . '/../../data/Soundcloud-Profile-TomCruiseProfile.html'));

        setUrlMock("https://soundcloud.com/glitch409", file_get_contents(__DIR__ . '/../../data/Soundcloud-Profile-Glitch409.html'));

        setUrlMock("https://soundcloud.com/tom-cruise-putcha", file_get_contents(__DIR__ . '/../../data/Soundcloud-Profile-TomCruisePutcha.html'));
        
        
        $criteria = new Criteria;
        $criteria->first_name = "tom";
        $criteria->last_name = "Mapother";
        $criteria->middle_name = "cruise";

        $fetcher = new SoundcloudFetcher($criteria);
        $fetcher->execute();
        $actualList = $fetcher->getOutput();
        
        $expectedList = new SearchList('soundcloud');
        $expectedList->setUrl('https://soundcloud.com/search/people?q=tom+cruise+Mapother');
        
        $result = new SearchResult('https://soundcloud.com/tomcruiseofficial');
        $result->setIsProfile(true);
        $result->source = "soundcloud";
        $result->mainSource = "soundcloud";
        $result->username = 'Tom Cruise';
        $result->screenshotUrl = 'https://soundcloud.com/tomcruiseofficial';
        $result->orderInList = 0;
        $result->image = 'https://i1.sndcdn.com/avatars-000338927891-0oxmpk-large.jpg';
        $result->addName(Name::create(["full_name"=>"Tom Cruise"], $result->mainSource));
        $expectedList->addResult($result);
        
        $result = new SearchResult('https://soundcloud.com/tom-cruise-8');
        $result->setIsProfile(true);
        $result->source = "soundcloud";
        $result->mainSource = "soundcloud";
        $result->username = 'Tom Cruise 6';
        $result->screenshotUrl = 'https://soundcloud.com/tom-cruise-8';
        $result->orderInList = 1;
        $result->image = 'https://i1.sndcdn.com/avatars-000338927891-0oxmpk-large.jpg';
        $result->addName(Name::create(["full_name"=>"Tom Cruise 6"], $result->mainSource));
        $expectedList->addResult($result);
        
        $result = new SearchResult('https://soundcloud.com/tomcruiseprofile');
        $result->setIsProfile(true);
        $result->source = "soundcloud";
        $result->mainSource = "soundcloud";
        $result->username = 'Tom Cruise 1';
        $result->screenshotUrl = 'https://soundcloud.com/tomcruiseprofile';
        $result->orderInList = 2;
        $result->image = 'https://i1.sndcdn.com/avatars-000338927891-0oxmpk-large.jpg';
        $result->addName(Name::create(["full_name"=>"Tom Cruise 1"], $result->mainSource));
        $expectedList->addResult($result);
        
        $result = new SearchResult('https://soundcloud.com/glitch409');
        $result->setIsProfile(true);
        $result->username = 'Glitch409';
        $result->screenshotUrl = 'https://soundcloud.com/glitch409';
        $result->orderInList = 3;
        $result->image = 'https://i1.sndcdn.com/avatars-000004664808-53hx3h-large.jpg';
        $result->addName(Name::create(["full_name"=>"tom cruise"], $result->mainSource));
        $result->addLocation(Address::create(["full_address"=>"Beaumont, US"], $result->mainSource));
        $expectedList->addResult($result);
        
        $result = new SearchResult('https://soundcloud.com/tom-cruise-putcha');
        $result->setIsProfile(true);
        $result->source = "soundcloud";
        $result->mainSource = "soundcloud";
        $result->username = 'Tom Cruise Putcha';
        $result->screenshotUrl = 'https://soundcloud.com/tom-cruise-putcha';
        $result->orderInList = 4;
        $result->image = 'https://i1.sndcdn.com/avatars-000338927891-0oxmpk-large.jpg';
        $result->addName(Name::create(["full_name"=>"Tom Cruise Putcha"], $result->mainSource));
        $expectedList->addResult($result);
        
        $this->assertEquals($expectedList, $actualList);
    }

    public function testFetchingFirstLastNamesAndState()
    {
        setUrlMock("https://m.soundcloud.com/", file_get_contents(__DIR__ . '/../../data/Soundcloud-Mobile-TomCruise.html'));
        setUrlMock("https://api-mobi.soundcloud.com/search/users?q=tom+cruise+Mapother&filter.place=Beverly+Hills&client_id=ygy2GkGPGW6Tn8m22Bz1SDplrFKBlwP0&format=json&app_version=1464963889", file_get_contents(__DIR__ . '/../../data/Soundcloud-Search-TomCruise-NameState.json'));
        
        $criteria = new Criteria;
        $criteria->first_name = "tom";
        $criteria->middle_name = "cruise";
        $criteria->last_name = "Mapother";
        $criteria->state = "Beverly Hills";

        $fetcher = new SoundcloudFetcher($criteria);
        $fetcher->execute();
        $actualList = $fetcher->getOutput();
        
        $expectedList = new SearchList('soundcloud');
        
        $expectedList->setUrl('https://soundcloud.com/search/people?q=tom+cruise+Mapother&filter.place=Beverly+Hills');
        
        $result = new SearchResult('https://soundcloud.com/user32585771');
        $result->setIsProfile(true);
        $result->username = 'Tom.Cruise';
        $result->screenshotUrl = 'https://soundcloud.com/user32585771';
        $result->orderInList = 0;
        $result->image = 'https://i1.sndcdn.com/avatars-000043194122-q8ak7g-large.jpg';
        $result->addName(Name::create(["full_name"=>"Tom Cruise"], $result->mainSource));
        $result->addLocation(Address::create(["full_address"=>"Beverly Hills, US"], $result->mainSource));
        $expectedList->addResult($result);
        
        $this->assertEquals($expectedList, $actualList);
    }

    public function testFetchingFirstLastNamesAndCity()
    {
        setUrlMock("https://m.soundcloud.com/", file_get_contents(__DIR__ . '/../../data/Soundcloud-Mobile-TomCruise.html'));
        setUrlMock("https://api-mobi.soundcloud.com/search/users?q=tom+cruise+Mapother&filter.place=Beverly+Hills&client_id=ygy2GkGPGW6Tn8m22Bz1SDplrFKBlwP0&format=json&app_version=1464963889", file_get_contents(__DIR__ . '/../../data/Soundcloud-Search-TomCruise-NameState.json'));
        
        $criteria = new Criteria;
        $criteria->first_name = "tom";
        $criteria->middle_name = "cruise";
        $criteria->last_name = "Mapother";
        $criteria->city = "Beverly Hills";

        $fetcher = new SoundcloudFetcher($criteria);
        $fetcher->execute();
        $actualList = $fetcher->getOutput();
        
        $expectedList = new SearchList('soundcloud');
        $expectedList->setUrl('https://soundcloud.com/search/people?q=tom+cruise+Mapother&filter.place=Beverly+Hills');
        
        $result = new SearchResult('https://soundcloud.com/user32585771');
        $result->setIsProfile(true);
        $result->username = 'Tom.Cruise';
        $result->screenshotUrl = 'https://soundcloud.com/user32585771';
        $result->orderInList = 0;
        $result->image = 'https://i1.sndcdn.com/avatars-000043194122-q8ak7g-large.jpg';
        $result->addName(Name::create(["full_name"=>"Tom Cruise"], $result->mainSource));
        $result->addLocation(Address::create(["full_address"=>"Beverly Hills, US"], $result->mainSource));
        $expectedList->addResult($result);
        
        $this->assertEquals($expectedList, $actualList);
    }

    public function testExceedingLimit()
    {
        setUrlMock("https://m.soundcloud.com/", file_get_contents(__DIR__ . '/../../data/Soundcloud-Mobile-TomCruise.html'));

        setUrlMock("https://api-mobi.soundcloud.com/search/users?q=tom+cruise&client_id=ygy2GkGPGW6Tn8m22Bz1SDplrFKBlwP0&format=json&app_version=1464963889", file_get_contents(__DIR__ . '/../../data/Soundcloud-Search-TomCruise.json'));

        setUrlMock("https://soundcloud.com/tomcruiseofficial", file_get_contents(__DIR__ . '/../../data/Soundcloud-Profile-TomCruise.html'));
        
        $criteria = new Criteria;
        $criteria->first_name = "tom";
        $criteria->last_name = "cruise";

        $fetcher = new SoundcloudFetcher($criteria);
        $fetcher->maxResults = 1;
        $fetcher->execute();
        $actualList = $fetcher->getOutput();
        
        $expectedList = new SearchList('soundcloud');
        $expectedList->setUrl('https://soundcloud.com/search/people?q=tom+cruise');
        
        $result = new SearchResult('https://soundcloud.com/tomcruiseofficial');
        $result->setIsProfile(true);
        $result->source = "soundcloud";
        $result->mainSource = "soundcloud";
        $result->username = 'Tom Cruise';
        $result->screenshotUrl = 'https://soundcloud.com/tomcruiseofficial';
        $result->orderInList = 0;
        $result->image = 'https://i1.sndcdn.com/avatars-000338927891-0oxmpk-large.jpg';
        $result->addName(Name::create(["full_name"=>"Tom Cruise"], $result->mainSource));
        $expectedList->addResult($result);
        
        
        $this->assertEquals($expectedList, $actualList);
    }
   
    public function testEmptyResponse()
    {
        setUrlMock("https://m.soundcloud.com/", file_get_contents(__DIR__ . '/../../data/Soundcloud-Mobile-TomCruise.html'));

        setUrlMock("https://api-mobi.soundcloud.com/search/users?q=tomffff+cruisefffff&client_id=ygy2GkGPGW6Tn8m22Bz1SDplrFKBlwP0&format=json&app_version=1464963889", file_get_contents(__DIR__ . '/../../data/Soundcloud-Search-Empty.json'));
        
        
        $criteria = new Criteria;
        $criteria->first_name = "tomffff";
        $criteria->last_name = "cruisefffff";
        
        $fetcher = new SoundcloudFetcher($criteria);
        $fetcher->execute();
        $actualList = $fetcher->getOutput();
        
        $expectedList = new SearchList('soundcloud');
        $expectedList->setUrl('https://soundcloud.com/search/people?q=tomffff+cruisefffff');
        
        $this->assertEquals($expectedList, $actualList);
    }

    public function testBadResponse1()
    {
        setUrlMock("https://m.soundcloud.com/", "<html></html>", ["HTTP/1.1 404 Not Found", "user-agent" => ""]);

        setUrlMock("https://api-mobi.soundcloud.com/search/users?q=tomffff+cruisefffff&client_id=ygy2GkGPGW6Tn8m22Bz1SDplrFKBlwP0&format=json&app_version=1464963889", file_get_contents(__DIR__ . '/../../data/Soundcloud-Search-Empty.json'));

        setUrlMock("", "");
        
        $criteria = new Criteria;
        $criteria->first_name = "tom";
        $criteria->last_name = "cruise";

        $fetcher = new SoundcloudFetcher($criteria);
        $fetcher->execute();
        $actualList = $fetcher->getOutput();

        $expectedList = new SearchList('soundcloud');

        $this->assertEquals($expectedList, $actualList);
    }

    public function testBadResponse2()
    {
        setUrlMock("https://m.soundcloud.com/", file_get_contents(__DIR__ . '/../../data/Soundcloud-Mobile-TomCruise.html'));
        setUrlMock("https://api-mobi.soundcloud.com/search/users?q=tom+cruise&client_id=ygy2GkGPGW6Tn8m22Bz1SDplrFKBlwP0&format=json&app_version=1464963889", file_get_contents(__DIR__ . '/../../data/Soundcloud-Search-Empty.json'), "HTTP/1.1 404 Not Found");
        
        $criteria = new Criteria;
        $criteria->first_name = "tom";
        $criteria->last_name = "cruise";

        $fetcher = new SoundcloudFetcher($criteria);
        $fetcher->execute();
        $actualList = $fetcher->getOutput();

        $expectedList = new SearchList('soundcloud');
        $expectedList->setUrl('https://soundcloud.com/search/people?q=tom+cruise');

        $this->assertEquals($expectedList, $actualList);
    }
}
