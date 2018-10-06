<?php
namespace Skopenow\SearchTest\Fetching\Fetchers;

use Skopenow\Search\Fetching\Fetchers\TwitterstatusFetcher;
use Skopenow\Search\Models\Criteria;
use Skopenow\Search\Models\SearchList;
use Skopenow\Search\Models\SearchResult;
use App\DataTypes\Name;

class TwitterstatusFetcherTest extends \TestCase
{
    public function testFetchingFirstLastNames()
    {
        setUrlMock("https://twitter.com/search?q=tom+cruise&src=typd", file_get_contents(__DIR__ . '/../../data/Twitterstatus-Search-TomCruise-FirstLastNames.json'));
        
        $criteria = new Criteria;
        $criteria->full_name = "tom cruise";
        
        $fetcher = new TwitterstatusFetcher($criteria);
        $fetcher->execute();
        $actualList = $fetcher->getOutput();
        
        $expectedList = new SearchList('twitterstatus');
        $expectedList->setUrl("https://twitter.com/search?q=tom+cruise&src=typd");
        
        $result = new SearchResult('http://twitter.com/larsen_payton/status/911209746438414339');
        
        $result->title = "Young Tom cruise will always be a panty dropper.";
        $result->orderInList = 0;
        $result->addName(Name::create(["full_name"=>"Tom Cruise"], $result->mainSource));
        $expectedList->addResult($result);
        
        
        $result = new SearchResult('http://twitter.com/Lawrence_tko/status/911550959616217088');
        
        $result->title = "When Tom Cruise asked for a pic with &#39;The Sauce&#39;";
        $result->orderInList = 1;
        $expectedList->addResult($result);

        $result = new SearchResult('http://twitter.com/Skydance/status/911591332291989510');
        
        $result->title = "Ethan Hunt or Jack Reacher: which Tom Cruise character is your favorite?";
        $result->orderInList = 2;
        $result->addName(Name::create(["full_name"=>"Payton Larsen"], $result->mainSource));
        $expectedList->addResult($result);
        
        $result = new SearchResult('http://twitter.com/MovieGrafTR/status/911905036506210305');
        
        $result->title = "Brad Pitt, Nicole Kidman &amp; Tom Cruise, 1998.";
        $result->orderInList = 3;
        $result->addName(Name::create(["full_name"=>"Lawrence Okolie"], $result->mainSource));
        $expectedList->addResult($result);
        
        
        $result = new SearchResult('http://twitter.com/cristiamos8586/status/913262171437465601');
        
        $result->title = "Conan drives with  Tom Cruise&#39;s highlights. Part 5: Can&#39;t bear being stuck in this uncomfortable situation, Tom asked for help.";
        $result->orderInList = 4;
        $expectedList->addResult($result);
        
        $this->assertEquals($expectedList, $actualList);
    }

    public function testFetchingCityState()
    {
        setUrlMock("https://twitter.com/search?q=tom+cruise+near%3A%22Beverly+Hills%2C+CA%22&src=typd", file_get_contents(__DIR__ . '/../../data/Twitterstatus-Search-TomCruise-CityState.json'));
        
        $criteria = new Criteria;
        $criteria->full_name = "tom cruise";
        $criteria->city = "Beverly Hills";
        $criteria->state = "CA";
        
        $fetcher = new TwitterstatusFetcher($criteria);
        $fetcher->execute();
        $actualList = $fetcher->getOutput();
        
        $expectedList = new SearchList('twitterstatus');
        $expectedList->setUrl("https://twitter.com/search?q=tom+cruise+near%3A%22Beverly+Hills%2C+CA%22&src=typd");
        
        $expectedList = new SearchList('twitterstatus');
        $expectedList->setUrl("https://twitter.com/search?q=tom+cruise+near%3A%22Beverly+Hills%2C+CA%22&src=typd");
        
        $result = new SearchResult('http://twitter.com/larsen_payton/status/911209746438414339');
        
        $result->title = "Young Tom cruise will always be a panty dropper.";
        $result->orderInList = 0;
        $result->addName(Name::create(["full_name"=>"Tom Cruise"], $result->mainSource));
        $expectedList->addResult($result);
        
        
        $result = new SearchResult('http://twitter.com/Lawrence_tko/status/911550959616217088');
        
        $result->title = "When Tom Cruise asked for a pic with &#39;The Sauce&#39;";
        $result->orderInList = 1;
        $expectedList->addResult($result);

        $result = new SearchResult('http://twitter.com/Skydance/status/911591332291989510');
        
        $result->title = "Ethan Hunt or Jack Reacher: which Tom Cruise character is your favorite?";
        $result->orderInList = 2;
        $result->addName(Name::create(["full_name"=>"Payton Larsen"], $result->mainSource));
        $expectedList->addResult($result);
        
        $result = new SearchResult('http://twitter.com/MovieGrafTR/status/911905036506210305');
        
        $result->title = "Brad Pitt, Nicole Kidman &amp; Tom Cruise, 1998.";
        $result->orderInList = 3;
        $result->addName(Name::create(["full_name"=>"Lawrence Okolie"], $result->mainSource));
        $expectedList->addResult($result);
        
        
        $result = new SearchResult('http://twitter.com/cristiamos8586/status/913262171437465601');
        
        $result->title = "Conan drives with  Tom Cruise&#39;s highlights. Part 5: Can&#39;t bear being stuck in this uncomfortable situation, Tom asked for help.";
        $result->orderInList = 4;
        $expectedList->addResult($result);
        
        $this->assertEquals($expectedList, $actualList);
    }

    public function testFetchingCityOnly()
    {
        setUrlMock("https://twitter.com/search?q=tom+cruise+near%3A%22Beverly+Hills%22&src=typd", file_get_contents(__DIR__ . '/../../data/Twitterstatus-Search-TomCruise-CityOnly.json'));

        $criteria = new Criteria;
        $criteria->full_name = "tom cruise";
        $criteria->city = "Beverly Hills";
        
        $fetcher = new TwitterstatusFetcher($criteria);
        $fetcher->execute();
        $actualList = $fetcher->getOutput();
        
        $expectedList = new SearchList('twitterstatus');
        $expectedList->setUrl("https://twitter.com/search?q=tom+cruise+near%3A%22Beverly+Hills%22&src=typd");
        
        
        $result = new SearchResult('http://twitter.com/Skydance/status/911591332291989510');
        
        $result->title = "Ethan Hunt or Jack Reacher: which Tom Cruise character is your favorite?";
        $result->orderInList = 0;
        $result->addName(Name::create(["full_name"=>"Amy Nicholson"], $result->mainSource));
        $expectedList->addResult($result);
        
        
        $result = new SearchResult('http://twitter.com/ConanOBrien/status/912879786560602112');
        
        $result->title = "Watch me take";
        $result->orderInList = 1;
        $result->addName(Name::create(["full_name"=>"Not Tom Cruise"], $result->mainSource));
        $expectedList->addResult($result);
        
        
        $result = new SearchResult('http://twitter.com/MoviewebMovies/status/912727416258179077');
        
        $result->title = "Will Tom Cruise&#39;s American Made Fly High at the Box Office?";
        $result->orderInList = 2;
        $expectedList->addResult($result);
        
        
        $result = new SearchResult('http://twitter.com/enews/status/913080087242838016');
        
        $result->title = "If you&#39;re still wondering if Tom Cruise is really that bootylicious in Valkyrie, let us clarify—Yes, yes he is.";
        $result->orderInList = 3;
        $result->addName(Name::create(["full_name"=>"Conan O&#39;Brien"], $result->mainSource));
        $expectedList->addResult($result);
        
        
        $result = new SearchResult('http://twitter.com/Collider/status/912459428850311168');
        
        $result->title = "What&#39;s YOUR favorite Tom Cruise performance?

SHOW US THE COMMENTS!";
        $result->orderInList = 4;
        $result->addName(Name::create(["full_name"=>"Movie News"], $result->mainSource));
        $expectedList->addResult($result);
        
        
        $this->assertEquals($expectedList, $actualList);
    }

    public function testFetchingStateOnly()
    {
        setUrlMock("https://twitter.com/search?q=tom+cruise+near%3A%22CA%22&src=typd", file_get_contents(__DIR__ . '/../../data/Twitterstatus-Search-TomCruise-StateOnly.json'));
        
        $criteria = new Criteria;
        $criteria->full_name = "tom cruise";
        $criteria->state = "CA";
        
        $fetcher = new TwitterstatusFetcher($criteria);
        $fetcher->execute();
        $actualList = $fetcher->getOutput();
        
        $expectedList = new SearchList('twitterstatus');
        $expectedList->setUrl("https://twitter.com/search?q=tom+cruise+near%3A%22CA%22&src=typd");
        
        
        $result = new SearchResult('http://twitter.com/mcb33r/status/911273595728404480');
        
        $result->title = "Is it just me, or is Tom Cruise beginning to look like a middle aged lesbian?";
        $result->orderInList = 0;
        $expectedList->addResult($result);
        
        
        $result = new SearchResult('http://twitter.com/Zendraa_/status/323902358104985601');
        
        $result->title = "hallo";
        $result->orderInList = 1;
        $result->addName(Name::create(["full_name"=>"Taufik Zendrato"], $result->mainSource));
        $expectedList->addResult($result);
        
        
        $result = new SearchResult('http://twitter.com/juliaedithrios/status/911105522006290433');
        
        $result->title = "Tom cruise&quot;@NobitaaKepo: Jhony Deep / Tom Cruise?";
        $result->orderInList = 2;
        $expectedList->addResult($result);
        
        
        $result = new SearchResult('http://twitter.com/wn_widhia/status/765080583114555392');
        
        $result->title = "Ga biasanya film e tom cruise ky gini



      

      
        


      
      

      
      
  
      
    
    
    
      
        1 reply
      
    
    
      
        0 retweets
      
    
    
      
        0 likes
      
    
  

  
    
  
    
      
      Reply
    
      
        1
      
  


    
  
    
      
      Retweet
    
      
    
  

  
    
      
      Retweeted
    
      
    
  

  



    
  
    
      
      
      Like
    
      
    
  

  
    
      
      
      Liked";
        $result->orderInList = 3;
        $result->addName(Name::create(["full_name"=>"Widhia Nur Idza"], $result->mainSource));
        $expectedList->addResult($result);
        
        
        $result = new SearchResult('http://twitter.com/nip_ika/status/552794744259366912');
        
        $result->title = "Wht ? kotoran burungnya bulbul ? :o&quot;";
        $result->orderInList = 4;
        $result->addName(Name::create(["full_name"=>"Novita Ika Putri"], $result->mainSource));
        $expectedList->addResult($result);
        
        $this->assertEquals($expectedList, $actualList);
    }

    public function testFetchingWithDistance()
    {
        setUrlMock("https://twitter.com/search?q=tom+cruise+near%3A%22Beverly+Hills%2C+CA%22+within%3A12mi&src=typd", file_get_contents(__DIR__ . '/../../data/Twitterstatus-Search-TomCruise-WithDistance.json'));
        
        $criteria = new Criteria;
        $criteria->full_name = "tom cruise";
        $criteria->city = "Beverly Hills";
            $criteria->state = "CA";
        $criteria->distance = 12;
        
        $fetcher = new TwitterstatusFetcher($criteria);
        $fetcher->execute();
        $actualList = $fetcher->getOutput();
        
        $expectedList = new SearchList('twitterstatus');
        $expectedList->setUrl("https://twitter.com/search?q=tom+cruise+near%3A%22Beverly+Hills%2C+CA%22+within%3A12mi&src=typd");
        
        $result = new SearchResult('http://twitter.com/Skydance/status/911591332291989510');
        
        $result->title = "Ethan Hunt or Jack Reacher: which Tom Cruise character is your favorite?";
        $result->orderInList = 0;
        $result->addName(Name::create(["full_name"=>"Amy Nicholson"], $result->mainSource));
        $expectedList->addResult($result);
        
        
        $result = new SearchResult('http://twitter.com/ConanOBrien/status/912879786560602112');
        
        $result->title = "Watch me take";
        $result->orderInList = 1;
        $result->addName(Name::create(["full_name"=>"Not Tom Cruise"], $result->mainSource));
        $expectedList->addResult($result);
        
        
        $result = new SearchResult('http://twitter.com/extratv/status/909899858055618560');
        
        $result->title = "Tom Cruise gives an update after scary fall on set of &quot;Mission: Impossible 6&quot;:";
        $result->orderInList = 2;
        $expectedList->addResult($result);
        
        
        $result = new SearchResult('http://twitter.com/MoviewebMovies/status/912727416258179077');
        
        $result->title = "Will Tom Cruise&#39;s American Made Fly High at the Box Office?";
        $result->orderInList = 3;
        $result->addName(Name::create(["full_name"=>"Conan O&#39;Brien"], $result->mainSource));
        $expectedList->addResult($result);
        
        
        $result = new SearchResult('http://twitter.com/enews/status/913080087242838016');
        
        $result->title = "If you&#39;re still wondering if Tom Cruise is really that bootylicious in Valkyrie, let us clarify—Yes, yes he is.";
        $result->orderInList = 4;
        $expectedList->addResult($result);
        
        $this->assertEquals($expectedList, $actualList);
    }

    public function testExceedingLimit()
    {
        setUrlMock("https://twitter.com/search?q=tom+cruise&src=typd", file_get_contents(__DIR__ . '/../../data/Twitter-Search-TomCruise-FirstLast.json'));
        
        $criteria = new Criteria;
        $criteria->full_name = "tom cruise";
        
        $fetcher = new TwitterstatusFetcher($criteria);
        $fetcher->maxResults = 1;
        $fetcher->execute();
        $actualList = $fetcher->getOutput();

        $expectedList = new SearchList('twitterstatus');
        $expectedList->setUrl("https://twitter.com/search?q=tom+cruise&src=typd");
        
        // $result = new SearchResult('http://twitter.com/sweetstephen55/status/920365415095787521');
        // 
        // $result->title = "I could";
        // $result->orderInList = 0;
        // $result->addName(Name::create(["full_name"=>"Tom Cruise"], $result->mainSource));
        //$expectedList->addResult($result);
        
        $this->assertEquals($expectedList, $actualList);
    }
    
    public function testBadResponse()
    {
        setUrlMock("https://twitter.com/search?q=tom+cruise&src=typd", file_get_contents(__DIR__ . '/../../data/Twitter-Search-TomCruise-FirstLast.json'), "HTTP/1.1 404 Not Found");

        
        $criteria = new Criteria;
        $criteria->full_name = "tom cruise";
        
        $fetcher = new TwitterstatusFetcher($criteria);
        $fetcher->execute();
        $actualList = $fetcher->getOutput();
        
        $expectedList = new SearchList('twitterstatus');
        $expectedList->setUrl("https://twitter.com/search?q=tom+cruise&src=typd");
        
        
        $this->assertEquals($expectedList, $actualList);
    }
    
    public function testEmptyResponse()
    {
        setUrlMock("https://twitter.com/search?q=tom+cruise&src=typd", "");
        
        $criteria = new Criteria;
        $criteria->full_name = "tom cruise";
        
        $fetcher = new TwitterstatusFetcher($criteria);
        $fetcher->execute();
        $actualList = $fetcher->getOutput();
        
        $expectedList = new SearchList('twitterstatus');
        $expectedList->setUrl("https://twitter.com/search?q=tom+cruise&src=typd");
        
        $this->assertEquals($expectedList, $actualList);
    }

    public function testNoPage()
    {
        setUrlMock("https://twitter.com/search?q=tom+cruise&src=typd", file_get_contents(__DIR__ . '/../../data/Twitterstatus-Search-TomCruise-NoPage.json'));
        
        $criteria = new Criteria;
        $criteria->full_name = "tom cruise";
        
        $fetcher = new TwitterstatusFetcher($criteria);
        $fetcher->execute();
        $actualList = $fetcher->getOutput();
        
        $expectedList = new SearchList('twitterstatus');
        $expectedList->setUrl("https://twitter.com/search?q=tom+cruise&src=typd");
        
        
        $this->assertEquals($expectedList, $actualList);
    }
}
