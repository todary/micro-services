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

class WhoxySourceFetcherTest extends \TestCase
{
    public function testFetching()
    {
        setUrlMock("http://api.whoxy.com/?key=e9ddbbd683d80d4xp03bcd61620bf20e&whois=skopenow.com", file_get_contents(__DIR__ . '/../../../data/Whois/Whois-Domain-Skopenow.json'));

        $criteria = new Criteria;
        $criteria->domain = "skopenow.com";
        
        $fetcher = new WhoisFetcher($criteria);
        $fetcher->execute();
        $actualList = $fetcher->getOutput();

        $expectedList = new SearchList('whois');
        $expectedList->setUrl('');
        
        $result = new SearchResult('http://skopenow.com');
        $result->source = "whois";
        $result->mainSource = "whois";
        $result->screenshotUrl = 'http://skopenow.com';
        $result->orderInList = 0;
        $result->addName(
            Name::create(
                ["full_name"=>"Robert Douglas"],
                $result->mainSource
            )
        );
        $result->addName(
            Name::create(
                ["full_name"=>"Rob Douglas"],
                $result->mainSource
            )
        );

        $result->addLocation(
            Address::create(
                [
                    "full_address" => "100 Ring Road West, Garden City, New York",
                    "street" => "100 Ring Road West",
                    "city" => "Garden City",
                    "state" => "New York",
                    'country' => "United States",
                    'zip' => "11530"
                ],
                $result->mainSource
            )
        );
        $result->addLocation(
            Address::create(
                [
                    "full_address" => "50 Ring Road West, Garden City, New York",
                    "street" => "50 Ring Road West",
                    "city" => "Garden City",
                    "state" => "New York",
                    'country' => "United States",
                    'zip' => "11530"
                ],
                $result->mainSource
            )
        );

        $result->addEmail(
            Email::create(
                ['email' => "robert@skopenow.com"],
                $result->mainSource
            )
        );
        $result->addEmail(
            Email::create(
                ['email' => "rob@skopenow.com"],
                $result->mainSource
            )
        );

        $result->addPhone(
            Phone::create(
                ['phone' => "00518002521437"],
                $result->mainSource
            )
        );
        $result->addPhone(
            Phone::create(
                ['phone' => "18002521437"],
                $result->mainSource
            )
        );
        
        $result->addWebsite(Website::create(['url' =>$criteria->domain], WhoisFetcher::MAIN_SOURCE_NAME));

        $expectedList->addResult($result);

        $this->assertEquals($expectedList, $actualList);
    }

    public function testPrivicyFetching()
    {
        setUrlMock("http://api.whoxy.com/?key=e9ddbbd683d80d4xp03bcd61620bf20e&whois=maj-ss.com", file_get_contents(__DIR__ . '/../../../data/Whois/Whois-Domain-maj-ss-Privacy.json'));

        $criteria = new Criteria;
        $criteria->domain = "maj-ss.com";
        
        $fetcher = new WhoisFetcher($criteria);
        $fetcher->execute();
        $actualList = $fetcher->getOutput();

        $expectedList = new SearchList('whois');
        $expectedList->setUrl('');

        $this->assertEquals($expectedList, $actualList);
    }

    public function testStatusFetching()
    {
        setUrlMock("http://api.whoxy.com/?key=e9ddbbd683d80d4xp03bcd61620bf20e&whois=maj-ss.com", file_get_contents(__DIR__ . '/../../../data/Whois/Whois-Domain-maj-ss-Status.json'));

        $criteria = new Criteria;
        $criteria->domain = "maj-ss.com";
        
        $fetcher = new WhoisFetcher($criteria);
        $fetcher->execute();
        $actualList = $fetcher->getOutput();

        $expectedList = new SearchList('whois');
        $expectedList->setUrl('');
        
        $this->assertEquals($expectedList, $actualList);
    }

    public function testEmptyBodyFetching()
    {
        setUrlMock("http://api.whoxy.com/?key=e9ddbbd683d80d4xp03bcd61620bf20e&whois=maj-ss.com", "Nothing");

        $criteria = new Criteria;
        $criteria->domain = "maj-ss.com";
        
        $fetcher = new WhoisFetcher($criteria);
        $fetcher->execute();
        $actualList = $fetcher->getOutput();

        $expectedList = new SearchList('whois');
        $expectedList->setUrl('');
        
        $this->assertEquals($expectedList, $actualList);
    }
}
