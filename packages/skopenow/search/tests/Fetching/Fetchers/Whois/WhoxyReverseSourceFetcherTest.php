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

class WhoxyReverseSourceFetcherTest extends \TestCase
{
    public function testFetching()
    {
        setUrlMock("http://api.whoxy.com/?key=e9ddbbd683d80d4xp03bcd61620bf20e&format=json&reverse=whois&email=robert.douglas%40hotmail.com", file_get_contents(__DIR__ . '/../../../data/Whois/Whois-Whoxy-Reverse-Skopenow.json'));

        $criteria = new Criteria;
        $criteria->full_name = "Robert Douglas";
        $criteria->email = "robert.douglas@hotmail.com";
        
        $fetcher = new WhoisFetcher($criteria);
        $fetcher->execute();
        $actualList = $fetcher->getOutput();

        $expectedList = new SearchList('whois');
        $expectedList->setUrl('');
        
        $result = new SearchResult('http://robertdouglasindustries.com');
        $result->source = "whois";
        $result->mainSource = "whois";
        $result->screenshotUrl = 'http://robertdouglasindustries.com';
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
                    "full_address" => "11705 Lemens Spice Cv, Austin, Texas",
                    "street" => "11705 Lemens Spice Cv",
                    "city" => "Austin",
                    "state" => "Texas",
                    'country' => "United States",
                    'zip' => "27516"
                ],
                $result->mainSource
            )
        );
        $result->addLocation(
            Address::create(
                [
                    "full_address" => "11 Lemens Spice Cv, Austin, Texas",
                    "street" => "11 Lemens Spice Cv",
                    "city" => "Austin",
                    "state" => "Texas",
                    'country' => "United States",
                    'zip' => "27516"
                ],
                $result->mainSource
            )
        );

        $result->addEmail(
            Email::create(
                ['email' => "robert.douglas@hotmail.com"],
                $result->mainSource
            )
        );
        $result->addEmail(
            Email::create(
                ['email' => "robert.douglas2@hotmail.com"],
                $result->mainSource
            )
        );

        $result->addPhone(
            Phone::create(
                ['phone' => "16179396295"],
                $result->mainSource
            )
        );
        $result->addPhone(
            Phone::create(
                ['phone' => "0056179396295"],
                $result->mainSource
            )
        );

        $result->addWebsite(Website::create(['url' => "robertdouglasindustries.com"], WhoisFetcher::MAIN_SOURCE_NAME));

        $expectedList->addResult($result);

        $result = new SearchResult('http://todddouglas.info');
        $result->source = "whois";
        $result->mainSource = "whois";
        $result->orderInList = 0;
        $result->addName(
            Name::create(
                ["full_name"=>"Robert Douglas"],
                $result->mainSource
            )
        );

        $result->addLocation(
            Address::create(
                [
                    "full_address" => "11705 Lemens Spice Cv, Austin, Texas",
                    "street" => "11705 Lemens Spice Cv",
                    "city" => "Austin",
                    "state" => "Texas",
                    'country' => "United States",
                    'zip' => "78750"
                ],
                $result->mainSource
            )
        );
        $result->addEmail(
            Email::create(
                ['email' => "robert.douglas@hotmail.com"],
                $result->mainSource
            )
        );
        $result->addPhone(
            Phone::create(
                ['phone' => "16179396295"],
                $result->mainSource
            )
        );

        $result->addWebsite(Website::create(['url' => "todddouglas.info"], WhoisFetcher::MAIN_SOURCE_NAME));

        $expectedList->addResult($result);

        $this->assertEquals($expectedList, $actualList);
    }

    public function testFetchingNoResult()
    {
        setUrlMock("http://api.whoxy.com/?key=e9ddbbd683d80d4xp03bcd61620bf20e&format=json&reverse=whois&name=Robert+Douglas&email=robert.douglas%40hotmail.com", file_get_contents(__DIR__ . '/../../../data/Whois/Whois-Whoxy-Reverse-Skopenow-EmptyData.json'));

        $criteria = new Criteria;
        $criteria->full_name = "Robert Douglas";
        $criteria->email = "robert.douglas@hotmail.com";
        
        $fetcher = new WhoisFetcher($criteria);
        $fetcher->execute();
        $actualList = $fetcher->getOutput();

        $expectedList = new SearchList('whois');
        $expectedList->setUrl('');
        
        $this->assertEquals($expectedList, $actualList);
    }

    public function testFetchingSearchData()
    {
        $criteria = new Criteria;
                
        $fetcher = new WhoisFetcher($criteria);
        $fetcher->execute();
        $actualList = $fetcher->getOutput();

        $expectedList = new SearchList('whois');
        $expectedList->setUrl('');
        
        $this->assertEquals($expectedList, $actualList);
    }
}
