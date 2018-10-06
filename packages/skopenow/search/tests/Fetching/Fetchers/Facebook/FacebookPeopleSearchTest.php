<?php

namespace Skopenow\SearchTest\Fetching\Fetchers;

use Skopenow\Search\Fetching\Fetchers\Facebook\FacebookPeopleSearch;
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

class FacebookPeopleSearchTest extends \TestCase
{

    public function testFetching()
    {
        setUrlMock("https://www.facebook.com/search/people/?q=Rob%20Douglas%20skopenow", file_get_contents(__DIR__ . '/../../../data/facebook/Search-RobDouglas-NameCompany.html'));

        setUrlMock("https://m.facebook.com/rob.douglas.7923/about", file_get_contents(__DIR__ . '/../../../data/facebook/Profile-rob.douglas.7923.html'));

        $criteria = new Criteria;
        $criteria->first_name = "Rob";
        $criteria->last_name = "Douglas";
        $criteria->company = "skopenow";

        $fetcher = new FacebookPeopleSearch($criteria);
        $fetcher->execute();
        $actualList = $fetcher->getOutput();

        $expectedList = new SearchList('facebook');
        $expectedList->setUrl('https://www.facebook.com/search/people/?q=Rob%20Douglas%20skopenow');

        $result = new SearchResult('https://www.facebook.com/rob.douglas.7923?ref=br_rs');
        $result->url = "https://www.facebook.com/rob.douglas.7923";
        $result->username = "rob.douglas.7923";
        $result->resultsCount=1;
        $result->mainSource = 'facebook';
        $result->source = 'facebook';
        $result->setIsProfile(true);
        $result->addName(Name::create(['full_name' => "Rob Douglas"], "facebook"));
        $result->addLocation(Address::create(['full_address' => "New York, New York"], 'facebook'));
        $result->addLocation(Address::create(['full_address' => "Oyster Bay Cove, New York"], 'facebook'));

        $result->image = 'https://scontent-cai1-1.xx.fbcdn.net/v/t1.0-1/c20.6.74.74/p86x86/602480_10100110010210498_1105520697_n.jpg?oh=6b8564bd84c7445a71c1fbe308c1b140&amp;oe=5A43FD7D';
            
        $result->addExperience(
            Work::create([
                "company"   =>  "Skopenow",
                "image" =>  'https://scontent-cai1-1.xx.fbcdn.net/v/t1.0-1/cp0/e15/q65/p48x48/11822860_856941437722373_5579155948699186970_n.png.jpg?efg=eyJpIjoidCJ9&oh=2d5c03aff71252fbccd3c768f8da7a9a&oe=5A866832',
                "title" => "Co-Founder/CEO",
                "start" => "",
                "end" => ""
            ], 'facebook')
        );
        
        $result->addEducation(
            School::create([
                "image" => 'https://scontent-cai1-1.xx.fbcdn.net/v/t1.0-1/cp0/e15/q65/p48x48/375069_10150710767456907_1761486500_n.jpg?efg=eyJpIjoidCJ9&oh=d44f21572c25c58af364871b22f4219a&oe=5A885473',
                "name" => "Vanderbilt University",
                "start" => "",
                "end" => "2010",
                "degree" => "College",
            ], 'facebook')
        );
        
        $result->addEducation(
            School::create([
                "image" => 'https://external-cai1-1.xx.fbcdn.net/safe_image.php?d=AQBOyWrhQp7qWCoZ&w=48&h=48&url=http\25 3A\25 2F\25 2Fupload.wikimedia.org\25 2Fwikipedia\25 2Fcommons\25 2F5\25 2F5e\25 2FOyster_Bay_HS_in_2005.JPG&cfs=1&fallback=hub_likes&f&_nc_hash=AQDxoUzRb5SkI-Di',
                "name" => "Oyster Bay High School",
                "degree" => "High School",
                'start' => '',
                'end' => ''

            ], 'facebook')
        );

        $result->orderInList = 0;
        $expectedList->addResult($result);

        $this->assertEquals($expectedList, $actualList);
    }

    public function testExceedingLimit()
    {
        // setUrlMock("https://www.facebook.com/search/people/?=ahmed_sama27%40yahoo.com/keywords_top?em=1", file_get_contents(__DIR__ . '/../../../data/facebook/Search-ahmed_sama27-Email.html'));
        setUrlMock("https://www.facebook.com/search/people/?q=ahmed_sama27%40yahoo.com", file_get_contents(__DIR__ . '/../../../data/facebook/Search-ahmed_sama27-Email.html'));
        setUrlMock("https://m.facebook.com/ahmedsamir732/about", file_get_contents(__DIR__ . '/../../../data/facebook/Profile-ahmedsamir732.html'));

        $criteria = new Criteria;
        $criteria->email = "ahmed_sama27@yahoo.com";

        $fetcher = new FacebookPeopleSearch($criteria);
        $fetcher->maxResults = 0;
        $fetcher->execute();
        $actualList = $fetcher->getOutput();

        $expectedList = new SearchList('facebook');
        $expectedList->setUrl('https://www.facebook.com/search/people/?q=ahmed_sama27%40yahoo.com');

        

        $this->assertEquals($expectedList, $actualList);
    }

    public function testBadResponse()
    {
        setUrlMock("https://www.facebook.com/search/people/?q=ahmed_sama27%40yahoo.com","<html></html>", "HTTP/1.1 404 Not Found");
        setUrlMock("https://www.facebook.com/search/people/?q=ahmed_sama27%40yahoo.com&em=1","<html></html>", "HTTP/1.1 404 Not Found");

        $criteria = new Criteria;
        $criteria->email = "ahmed_sama27@yahoo.com";

        $fetcher = new FacebookPeopleSearch($criteria);
        $fetcher->execute();
        $actualList = $fetcher->getOutput();

        $expectedList = new SearchList('facebook');
        $expectedList->setUrl('https://www.facebook.com/search/people/?q=ahmed_sama27%40yahoo.com');

        $this->assertEquals($expectedList, $actualList);
    }

    public function testEmptyResponse()
    {
        setUrlMock("https://www.facebook.com/search/people/?q=ahmed_sama27%40yahoo.com","<html></html>");
        setUrlMock("https://www.facebook.com/search/people/?q=ahmed_sama27%40yahoo.com&em=1","<html></html>");

        $criteria = new Criteria;
        $criteria->email = "ahmed_sama27@yahoo.com";

        $fetcher = new FacebookPeopleSearch($criteria);
        $fetcher->execute();
        $actualList = $fetcher->getOutput();

        $expectedList = new SearchList('facebook');
        $expectedList->setUrl('https://www.facebook.com/search/people/?q=ahmed_sama27%40yahoo.com');

        $this->assertEquals($expectedList, $actualList);
    }
}
