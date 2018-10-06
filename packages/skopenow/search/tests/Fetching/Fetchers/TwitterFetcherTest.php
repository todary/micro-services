<?php
namespace Skopenow\SearchTest\Fetching\Fetchers;

use Skopenow\Search\Fetching\Fetchers\TwitterFetcher;
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

class TwitterFetcherTest extends \TestCase
{
    public function testFetchingNormal()
    {

        setUrlMock("https://twitter.com/search?q=tom+cruise&src=typd&f=users", file_get_contents(__DIR__ . '/../../data/Twitter-Search-TomCruise-FirstLast.json'));

        setUrlMock("http://twitter.com/TomCruise", file_get_contents(__DIR__ . '/../../data/Twitter-Profile-TomCruise.html'));

        setUrlMock("http://twitter.com/TomCruiseFanCom", file_get_contents(__DIR__ . '/../../data/Twitter-Profile-TomCruiseFanCom.html'));

        setUrlMock("http://twitter.com/MissionFilm", file_get_contents(__DIR__ . '/../../data/Twitter-Profile-MissionFilm.html'));

        setUrlMock("http://twitter.com/TomCruiseBRCom", file_get_contents(__DIR__ . '/../../data/Twitter-Profile-TomCruiseBRCom.html'));

        setUrlMock("http://twitter.com/TheAmyNicholson", file_get_contents(__DIR__ . '/../../data/Twitter-Profile-TheAmyNicholson.html'));

        $criteria = new Criteria;
        $criteria->full_name = "tom cruise";
        
        $fetcher = new TwitterFetcher($criteria);
        $fetcher->execute();
        $actualList = $fetcher->getOutput();

        $expectedList = new SearchList('twitter');
        $expectedList->setUrl("https://twitter.com/search?q=tom+cruise&src=typd&f=users");
        
        $result = new SearchResult('http://twitter.com/TomCruise');
        $result->setIsProfile(true);
        $result->username = "TomCruise";
        $result->orderInList = 0;
        $result->source = "twitter";
        $result->mainSource = "twitter";
        $result->image = "https://pbs.twimg.com/profile_images/603269306026106880/42CwEF4n_400x400.jpg";
        $result->addName(Name::create(['full_name' => "Tom Cruise"], $result->mainSource));
        $result->addLocation(Address::create(['full_address' =>"Worldwide"], $result->mainSource));
        $result->addLink(['url'=>"https://TomCruise.com",'reason'=>2]);
        $expectedList->addResult($result);

        $result = new SearchResult('http://twitter.com/TomCruiseFanCom');
        $result->setIsProfile(true);
        $result->username = "TomCruiseFanCom";
        $result->orderInList = 1;
        $result->source = "twitter";
        $result->mainSource = "twitter";
        $result->image = "https://pbs.twimg.com/profile_images/575162674788503552/kK6xdbk-_400x400.jpeg";
        $result->addName(Name::create(['full_name' => "TomCruiseFan.com"], $result->mainSource));
        $result->addLink(['url'=>"https://tomcruisefan.com",'reason'=>2]);

        $expectedList->addResult($result);
        
        $result = new SearchResult('http://twitter.com/MissionFilm');
        $result->setIsProfile(true);
        $result->username = "MissionFilm";
        $result->orderInList = 2;
        $result->source = "twitter";
        $result->mainSource = "twitter";
        $result->image = "https://pbs.twimg.com/profile_images/666748160350400512/vjizVmBE_400x400.jpg";
        $result->addName(Name::create(['full_name' => "Mission: Impossible"], $result->mainSource));
        $result->addLink(['url'=>"https://MissionImpossible.com",'reason'=>2]);
        $expectedList->addResult($result);
        
        $result = new SearchResult('http://twitter.com/TomCruiseBRCom');
        $result->setIsProfile(true);
        $result->username = "TomCruiseBRCom";
        $result->orderInList = 3;
        $result->source = "twitter";
        $result->mainSource = "twitter";
        $result->image = "https://pbs.twimg.com/profile_images/916052782175653889/1iEifGQQ_400x400.jpg";
        $result->addName(Name::create(['full_name' => "Tom Cruise Brasil"], $result->mainSource));
        $result->addLocation(Address::create(['full_address' =>"IG: @tomcruisebrcom"], $result->mainSource));
        $result->addLink(['url'=>"https://tomcruisebrasilsite.tumblr.com",'reason'=>2]);
        $expectedList->addResult($result);

        $result = new SearchResult('http://twitter.com/TheAmyNicholson');
        $result->setIsProfile(true);
        $result->username = "TheAmyNicholson";
        $result->orderInList = 4;
        $result->source = "twitter";
        $result->mainSource = "twitter";
        $result->image = "https://pbs.twimg.com/profile_images/483489196024160256/C2jwnZyl_400x400.jpeg";
        $result->addName(Name::create(['full_name' => "Amy Nicholson"], $result->mainSource));
        $result->addLocation(Address::create(['full_address' =>"Los Angeles"], $result->mainSource));
        $result->addLink(['url'=>"https://rottentomatoes.com/critic/amy-nic…",'reason'=>2]);
        $expectedList->addResult($result);

        $this->assertEquals($expectedList, $actualList);
    }

    public function testFetchingCityState()
    {
        setUrlMock("https://twitter.com/search?q=tom+cruise near:Beverly Hills CA&src=typd&f=users", file_get_contents(__DIR__ . '/../../data/Twitter-Search-TomCruise-CityState.json'));

        setUrlMock("https://twitter.com/search?q=tom+cruise+near:Beverly+Hills+CA&src=typd&f=users", file_get_contents(__DIR__ . '/../../data/Twitter-Search-TomCruise-BeverlyHills.html'));
        
        $criteria = new Criteria;
        $criteria->full_name = "tom cruise";
        $criteria->city = "Beverly Hills";
        $criteria->state = "CA";
        
        $fetcher = new TwitterFetcher($criteria);
        $fetcher->execute();
        $actualList = $fetcher->getOutput();
        
        $expectedList = new SearchList('twitter');
        $expectedList->setUrl("https://twitter.com/search?q=tom+cruise+near:Beverly+Hills+CA&src=typd&f=users");
        
        $this->assertEquals($expectedList, $actualList);
    }

    public function testFetchingCityOnly()
    {
        setUrlMock("https://twitter.com/search?q=tom+cruise near:Beverly Hills&src=typd&f=users", file_get_contents(__DIR__ . '/../../data/Twitter-Search-TomCruise-CityState.json'));

        setUrlMock("https://twitter.com/search?q=tom+cruise+near:Beverly+Hills&src=typd&f=users", file_get_contents(__DIR__ . '/../../data/Twitter-Search-TomCruise-BeverlyHills.html'));
        
        $criteria = new Criteria;
        $criteria->full_name = "tom cruise";
        $criteria->city = "Beverly Hills";
        
        $fetcher = new TwitterFetcher($criteria);
        $fetcher->execute();
        $actualList = $fetcher->getOutput();
        
        $expectedList = new SearchList('twitter');
        $expectedList->setUrl("https://twitter.com/search?q=tom+cruise+near:Beverly+Hills&src=typd&f=users");
        
        $this->assertEquals($expectedList, $actualList);
    }

    public function testFetchingStateOnly()
    {
        setUrlMock("https://twitter.com/search?q=tom+cruise near:CA&src=typd&f=users", file_get_contents(__DIR__ . '/../../data/Twitter-Search-TomCruise-CityState.json'));

        setUrlMock("https://twitter.com/search?q=tom+cruise+near:CA&src=typd&f=users", file_get_contents(__DIR__ . '/../../data/Twitter-Search-TomCruise-BeverlyHills.html'));
        
        $criteria = new Criteria;
        $criteria->full_name = "tom cruise";
        $criteria->state = "CA";
        
        $fetcher = new TwitterFetcher($criteria);
        $fetcher->execute();
        $actualList = $fetcher->getOutput();
        
        $expectedList = new SearchList('twitter');
        $expectedList->setUrl("https://twitter.com/search?q=tom+cruise+near:CA&src=typd&f=users");
        
        $this->assertEquals($expectedList, $actualList);
    }

    public function testFetchingWithDistance()
    {
        setUrlMock("https://twitter.com/search?q=tom+cruise+within%3A1mi&src=typd&f=users", file_get_contents(__DIR__ . '/../../data/Twitter-Search-TomCruise-FirstLast.json'));
        
        //setUrlMock("https://twitter.com/search?q=tom+cruise+within%3A1mi&src=typd&f=users", file_get_contents(__DIR__ . '/../../data/Twitter-Search-TomCruise-within-3A1mi.html'));

        setUrlMock("http://twitter.com/TomCruise", file_get_contents(__DIR__ . '/../../data/Twitter-Profile-TomCruise.html'));

        setUrlMock("http://twitter.com/TomCruiseFanCom", file_get_contents(__DIR__ . '/../../data/Twitter-Profile-TomCruiseFanCom.html'));

        setUrlMock("http://twitter.com/MissionFilm", file_get_contents(__DIR__ . '/../../data/Twitter-Profile-MissionFilm.html'));

        setUrlMock("http://twitter.com/TomCruiseBRCom", file_get_contents(__DIR__ . '/../../data/Twitter-Profile-TomCruiseBRCom.html'));

        setUrlMock("http://twitter.com/TheAmyNicholson", file_get_contents(__DIR__ . '/../../data/Twitter-Profile-TheAmyNicholson.html'));
        
        $criteria = new Criteria;
        $criteria->full_name = "tom cruise";
        $criteria->distance = 1;
        
        $fetcher = new TwitterFetcher($criteria);
        $fetcher->execute();
        $actualList = $fetcher->getOutput();

        $expectedList = new SearchList('twitter');
        $expectedList->setUrl("https://twitter.com/search?q=tom+cruise+within%3A1mi&src=typd&f=users");

        $result = new SearchResult('http://twitter.com/TomCruise');
        
        $result->username = "TomCruise";
        $result->orderInList = 0;
        $expectedList->addResult($result);

        $result = new SearchResult('http://twitter.com/TomCruiseFanCom');
        
        $result->username = "TomCruiseFanCom";
        $result->orderInList = 1;
        $expectedList->addResult($result);
        
        $result = new SearchResult('http://twitter.com/MissionFilm');
        
        $result->username = "MissionFilm";
        $result->orderInList = 2;
        $expectedList->addResult($result);
        
        $result = new SearchResult('http://twitter.com/TomCruiseBRCom');
        
        $result->username = "TomCruiseBRCom";
        $result->orderInList = 3;
        $expectedList->addResult($result);
        
        $result = new SearchResult('http://twitter.com/TheAmyNicholson');
        
        $result->username = "TheAmyNicholson";
        $result->orderInList = 4;
        $expectedList->addResult($result);

        $this->assertEquals(5, count($actualList->getResults()));
    }

    public function testExceedingLimit()
    {
        setUrlMock("https://twitter.com/search?q=tom+cruise&src=typd&f=users", file_get_contents(__DIR__ . '/../../data/Twitter-Search-TomCruise-FirstLast.json'));

        setUrlMock("http://twitter.com/TomCruise", file_get_contents(__DIR__ . '/../../data/Twitter-Profile-TomCruise.html'));
        
        $criteria = new Criteria;
        $criteria->full_name = "tom cruise";

        $fetcher = new TwitterFetcher($criteria);
        $fetcher->maxResults = 1;
        $fetcher->execute();
        $actualList = $fetcher->getOutput();
        
        $expectedList = new SearchList('twitter');
        $expectedList->setUrl("https://twitter.com/search?q=tom+cruise&src=typd&f=users");
        
        $result = new SearchResult('http://twitter.com/TomCruise');
        $result->setIsProfile(true);
        $result->username = "TomCruise";
        $result->orderInList = 0;
        $result->source = "twitter";
        $result->mainSource = "twitter";
        $result->image = "https://pbs.twimg.com/profile_images/603269306026106880/42CwEF4n_400x400.jpg";
        $result->addName(Name::create(['full_name' => "Tom Cruise"], $result->mainSource));
        $result->addLocation(Address::create(['full_address' =>"Worldwide"], $result->mainSource));
        $result->addLink(['url'=>"https://TomCruise.com",'reason'=>2]);
        $expectedList->addResult($result);
        
        $this->assertEquals($expectedList, $actualList);
    }

    public function testBadResponse()
    {
        setUrlMock("https://twitter.com/search?q=tom+cruise&src=typd&f=users", file_get_contents(__DIR__ . '/../../data/Twitter-Search-TomCruise-CityState.json'), "HTTP/1.1 404 Not Found");
        
        $criteria = new Criteria;
        $criteria->full_name = "tom cruise";
        
        $fetcher = new TwitterFetcher($criteria);
        $fetcher->execute();
        $actualList = $fetcher->getOutput();
        
        $expectedList = new SearchList('twitter');
        $expectedList->setUrl("https://twitter.com/search?q=tom+cruise&src=typd&f=users");
        
        
        $this->assertEquals($expectedList, $actualList);
    }

    public function testEmptyResponse()
    {
        setUrlMock("https://twitter.com/search?q=tommmmmmm+cruiseeeeeee&src=typd&f=users", "");
        
        $criteria = new Criteria;
        $criteria->full_name = "tommmmmmm cruiseeeeeee";
        
        $fetcher = new TwitterFetcher($criteria);
        $fetcher->execute();
        $actualList = $fetcher->getOutput();
        
        $expectedList = new SearchList('twitter');
        $expectedList->setUrl("https://twitter.com/search?q=tommmmmmm+cruiseeeeeee&src=typd&f=users");
        
        
        $this->assertEquals($expectedList, $actualList);
    }
    
    public function testNoPage()
    {
        setUrlMock("https://twitter.com/search?q=tom+cruise&src=typd&f=users", file_get_contents(__DIR__ . '/../../data/Twitter-Search-TomCruise-No-Page.json'));
        
        $criteria = new Criteria;
        $criteria->full_name = "tom cruise";
        
        $fetcher = new TwitterFetcher($criteria);
        $fetcher->execute();
        $actualList = $fetcher->getOutput();
        
        $expectedList = new SearchList('twitter');
        $expectedList->setUrl("https://twitter.com/search?q=tom+cruise&src=typd&f=users");
        
        
        $this->assertEquals($expectedList, $actualList);
    }
}
