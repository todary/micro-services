<?php
namespace Skopenow\SearchTest\Managing\Managers;

use Skopenow\Search\Fetching\Fetchers\SoundcloudFetcher;
use Skopenow\Search\Managing\Managers\SoundcloudManager;
use Skopenow\Search\Models\Criteria;
use Skopenow\Search\Models\SearchList;
use Skopenow\Search\Models\SearchResult;

class SoundcloudManagerTest extends \TestCase
{
    public function testExecution()
    {
        setUrlMock("https://m.soundcloud.com/", file_get_contents(__DIR__ . '/../../data/Soundcloud-Mobile-TomCruise.html'));
        setUrlMock("https://api-mobi.soundcloud.com/search/users?q=tom+cruise&client_id=ygy2GkGPGW6Tn8m22Bz1SDplrFKBlwP0&format=json&app_version=1464963889", file_get_contents(__DIR__ . '/../../data/Soundcloud-Search-TomCruise.json'));

        $criteria = new Criteria;
        $criteria->first_name = "tom";
        $criteria->last_name = "cruise";

        $fetcher = new SoundcloudFetcher($criteria);

        $manager = new SoundcloudManager($fetcher);
        $actualList = $manager->execute();

        $expectedList = new SearchList('soundcloud');
        $expectedList->setUrl('https://soundcloud.com/search/people?q=tom+cruise');

        $result = new SearchResult('https://soundcloud.com/tomcruiseofficial');
        $result->setIsProfile(true);
        $result->username = 'Tom Cruise';
        $result->screenshotUrl = 'https://soundcloud.com/tomcruiseofficial';
        $result->orderInList = 0;
        $result->image = 'http://a1.sndcdn.com/images/default_avatar_large.png?1505997153';
        $result->addName('Tom Cruise');
        $expectedList->addResult($result);
        
        $result = new SearchResult('https://soundcloud.com/tom-cruise-8');
        $result->setIsProfile(true);
        $result->username = 'Tom Cruise 6';
        $result->screenshotUrl = 'https://soundcloud.com/tom-cruise-8';
        $result->orderInList = 1;
        $result->image = 'https://i1.sndcdn.com/avatars-000066237505-a88igh-large.jpg';
        $result->addName('Tom Cruise 6');
        $expectedList->addResult($result);
        
        $result = new SearchResult('https://soundcloud.com/tomcruiseprofile');
        $result->setIsProfile(true);
        $result->username = 'Tom Cruise 1';
        $result->screenshotUrl = 'https://soundcloud.com/tomcruiseprofile';
        $result->orderInList = 2;
        $result->image = 'https://i1.sndcdn.com/avatars-000007968494-pn1i12-large.jpg';
        $result->addName('Tom Cruise 1');
        $expectedList->addResult($result);
        
        $result = new SearchResult('https://soundcloud.com/glitch409');
        $result->setIsProfile(true);
        $result->username = 'Glitch409';
        $result->screenshotUrl = 'https://soundcloud.com/glitch409';
        $result->orderInList = 3;
        $result->image = 'https://i1.sndcdn.com/avatars-000004664808-53hx3h-large.jpg';
        $result->addName('tom cruise');
        $result->addLocation('Beaumont, US');
        $expectedList->addResult($result);
        
        $result = new SearchResult('https://soundcloud.com/tom-cruise-putcha');
        $result->setIsProfile(true);
        $result->username = 'Tom Cruise Putcha';
        $result->screenshotUrl = 'https://soundcloud.com/tom-cruise-putcha';
        $result->orderInList = 4;
        $result->image = 'https://i1.sndcdn.com/avatars-000045331228-gtd19q-large.jpg';
        $result->addName('Tom Cruise Putcha');
        $expectedList->addResult($result);
        
        $this->assertEquals($expectedList, $actualList);
    }

    public function testNoResponse()
    {
        setUrlMock("https://m.soundcloud.com/", file_get_contents(__DIR__ . '/../../data/Soundcloud-Mobile-TomCruise.html'));
        setUrlMock("https://api-mobi.soundcloud.com/search/users?q=tom+cruise&client_id=ygy2GkGPGW6Tn8m22Bz1SDplrFKBlwP0&format=json&app_version=1464963889", file_get_contents(__DIR__ . '/../../data/Soundcloud-Search-Empty.json'));

        $criteria = new Criteria;
        $criteria->first_name = "tomffff";
        $criteria->last_name = "cruisefffff";


        $fetcher = new SoundcloudFetcher($criteria);

        $manager = new SoundcloudManager($fetcher);
        $actualList = $manager->execute();

        $expectedList = new SearchList('soundcloud');

        $this->assertEquals($expectedList, $actualList);
    }
}
