<?php
namespace Skopenow\SearchTest\Fetching\Fetchers\Whois;

use Skopenow\Search\Fetching\Fetchers\Whois\WhoisFetcher;
use Skopenow\Search\Models\Criteria;
use Skopenow\Search\Models\SearchList;
use Skopenow\Search\Models\SearchResult;
use App\DataTypes\Name;
use App\DataTypes\Address;
use App\DataTypes\Email;
use App\DataTypes\Phone;
use App\DataTypes\Website;

class WhoIsDotComSourceFetcherTest extends \TestCase
{
    public function testFetching()
    {
        setUrlMock("http://api.whoxy.com/?key=e9ddbbd683d80d4xp03bcd61620bf20e&whois=facebook.com", "");

        setUrlMock("https://who.is/whois/facebook.com", "");

        setUrlMock("http://www.whois.com/whois/facebook.com", file_get_contents(__DIR__ . '/../../../data/Whois/Whois-Whoisdotcom-Domain-Facebook.html'));

        $criteria = new Criteria;
        $criteria->domain = "facebook.com";
        
        $fetcher = new WhoisFetcher($criteria);
        $fetcher->execute();
        $actualList = $fetcher->getOutput();

        $expectedList = new SearchList('whois');
        $expectedList->setUrl('');
        
        $result = new SearchResult('http://facebook.com');
        $result->source = "whois";
        $result->mainSource = "whois";
        $result->screenshotUrl = 'http://facebook.com';
        $result->orderInList = 0;
        $result->addName(
            Name::create(
                ["full_name"=>"Domain Administrator"],
                $result->mainSource
            )
        );

        $result->addName(
            Name::create(
                ["full_name"=>"mark zuckerberg"],
                $result->mainSource
            )
        );

        $result->addLocation(
            Address::create(
                [
                    "full_address" => "1601 Willow Road, Menlo Park, CA",
                    "street" => "1601 Willow Road,",
                    "city" => "Menlo Park",
                    "state" => "CA",
                    'country' => "US",
                    'zip' => "94025"
                ],
                $result->mainSource
            )
        );

        $result->addLocation(
            Address::create(
                [
                    "full_address" => "16 Willow Road, Menlo Park, CA",
                    "street" => "16 Willow Road,",
                    "city" => "Menlo Park",
                    "state" => "CA",
                    'country' => "US",
                    'zip' => "94025"
                ],
                $result->mainSource
            )
        );

        $result->addPhone(
            Phone::create(
                ['phone' => "+1.6505434800"],
                $result->mainSource
            )
        );

        $result->addPhone(
            Phone::create(
                ['phone' => "+1.6505434811"],
                $result->mainSource
            )
        );
        $result->addWebsite(Website::create(['url' =>$criteria->domain], WhoisFetcher::MAIN_SOURCE_NAME));
        $expectedList->addResult($result);

        $this->assertEquals($expectedList, $actualList);
    }

    public function testFetchingEmptyData()
    {
        setUrlMock("http://api.whoxy.com/?key=e9ddbbd683d80d4xp03bcd61620bf20e&whois=facebook.com", "");

        setUrlMock("https://who.is/whois/facebook.com", "");

        setUrlMock("http://www.whois.com/whois/facebook.com", file_get_contents(__DIR__ . '/../../../data/Whois/Whois-Whoisdotcom-Domain-Facebook-EmptyData.html'));

        $criteria = new Criteria;
        $criteria->domain = "facebook.com";
        
        $fetcher = new WhoisFetcher($criteria);
        $fetcher->execute();
        $actualList = $fetcher->getOutput();

        $expectedList = new SearchList('whois');
        $expectedList->setUrl('');
        
        $result = new SearchResult('http://facebook.com');
        $result->source = "whois";
        $result->mainSource = "whois";
        $result->screenshotUrl = 'http://facebook.com';
        $result->orderInList = 0;
        $result->addName(
            Name::create(
                ["full_name"=>"Domain Administrator"],
                $result->mainSource
            )
        );
        
        $result->addLocation(
            Address::create(
                [
                    "full_address" => "1601 Willow Road, Menlo Park, CA",
                    "street" => "1601 Willow Road,",
                    "city" => "Menlo Park",
                    "state" => "CA",
                    'country' => "US",
                    'zip' => "94025"
                ],
                $result->mainSource
            )
        );
        
        $result->addPhone(
            Phone::create(
                ['phone' => "+1.6505434800"],
                $result->mainSource
            )
        );
        $result->addWebsite(Website::create(['url' =>$criteria->domain], WhoisFetcher::MAIN_SOURCE_NAME));
        $expectedList->addResult($result);

        $this->assertEquals($expectedList, $actualList);
    }

    public function testPrivicyFetching()
    {
        setUrlMock("http://api.whoxy.com/?key=e9ddbbd683d80d4xp03bcd61620bf20e&whois=facebook.com", "");

        setUrlMock("https://who.is/whois/facebook.com", "");

        setUrlMock("http://www.whois.com/whois/facebook.com", "asdsad Registrant Privacy form ");

        $criteria = new Criteria;
        $criteria->domain = "facebook.com";
        
        $fetcher = new WhoisFetcher($criteria);
        $fetcher->execute();
        $actualList = $fetcher->getOutput();
        
        $expectedList = new SearchList('whois');
        $expectedList->setUrl('');

        $this->assertEquals($expectedList, $actualList);
    }
}
