<?php

namespace Skopenow\SearchTest\Fetching\Fetchers;

use Skopenow\Search\Fetching\Fetchers\FlickrFetcher;
use Skopenow\Search\Models\Criteria;
use Skopenow\Search\Models\SearchList;
use Skopenow\Search\Models\SearchResult;

class FlickrFetcherTest extends \TestCase
{
    
    public function testFetching()
    {
        setUrlMock("https://www.flickr.com/search/people/?username=tom+cruise", file_get_contents(__DIR__ . '/../../data/Flickr-Search-TomCruise.html'));

        setUrlMock("https://www.flickr.com/photos/tom-cruise", file_get_contents(__DIR__ . '/../../data/Flickr-Profile-TomCruise.html'));

        setUrlMock("https://www.flickr.com/photos/151485523@N06", file_get_contents(__DIR__ . '/../../data/Flickr-Profile-Photos-151485523.html'));

        setUrlMock("https://www.flickr.com/photos/26991138@N08", file_get_contents(__DIR__ . '/../../data/Flickr-Profile-Photos-26991138.html'));

        setUrlMock("https://www.flickr.com/photos/jimmycorrigan", file_get_contents(__DIR__ . '/../../data/Flickr-Profile-Photos-jimmycorrigan.html'));

        setUrlMock("https://www.flickr.com/photos/tom_cruise_bp", file_get_contents(__DIR__ . '/../../data/Flickr-Profile-Photos-TomCruiseBp.html'));
        
        $criteria = new Criteria;
        $criteria->first_name = "tom";
        $criteria->last_name = "cruise";
        
        $fetcher = new FlickrFetcher($criteria);
        $fetcher->execute();
        $actualList = $fetcher->getOutput();
     
        $expectedList = new SearchList('flickr');
        $expectedList->setUrl('https://www.flickr.com/search/people/?username=tom+cruise');
        
        $result = new SearchResult('https://www.flickr.com/photos/tom-cruise');
        $result->setIsProfile(true);
        $result->username = 'Tom cruise, THE MEGA STAR';
        $result->source = "flickr";
        $result->mainSource = "flickr";
        $result->screenshotUrl = 'https://www.flickr.com/photos/tom-cruise';
        $result->orderInList = 0;
        $result->image = 'https://farm2.staticflickr.com/1344/buddyicons/10470787@N04.jpg';
        $expectedList->addResult($result);
        
        $result = new SearchResult('https://www.flickr.com/photos/151485523@N06');
        $result->setIsProfile(true);
        $result->username = 'Pete6534';
        $result->source = "flickr";
        $result->mainSource = "flickr";
        $result->screenshotUrl = 'https://www.flickr.com/photos/151485523@N06';
        $result->orderInList = 1;
        $result->image = 'https://www.flickr.com/images/buddyicon08.png';
        $expectedList->addResult($result);
        
        $result = new SearchResult('https://www.flickr.com/photos/26991138@N08');
        $result->setIsProfile(true);
        $result->source = "flickr";
        $result->mainSource = "flickr";
        $result->username = 'Tom Cruise Vs. Johnny Depp';
        $result->screenshotUrl = 'https://www.flickr.com/photos/26991138@N08';
        $result->orderInList = 2;
        $result->image = 'https://farm3.staticflickr.com/2165/buddyicons/26991138@N08.jpg';
        $expectedList->addResult($result);
        
        $result = new SearchResult('https://www.flickr.com/photos/jimmycorrigan');
        $result->setIsProfile(true);
        $result->source = "flickr";
        $result->mainSource = "flickr";
        $result->username = '711StrikesBack';
        $result->screenshotUrl = 'https://www.flickr.com/photos/jimmycorrigan';
        $result->orderInList = 3;
        $result->image = 'https://www.flickr.com/images/buddyicon08.png';
        $expectedList->addResult($result);
        
        $result = new SearchResult('https://www.flickr.com/photos/tom_cruise_bp');
        $result->setIsProfile(true);
        $result->source = "flickr";
        $result->mainSource = "flickr";
        $result->username = 'tom_cruisebp';
        $result->screenshotUrl = 'https://www.flickr.com/photos/tom_cruise_bp';
        $result->orderInList = 4;
        $result->image = 'https://farm8.staticflickr.com/7335/buddyicons/62096705@N03.jpg';
        $expectedList->addResult($result);
        
        $this->assertEquals($expectedList, $actualList);
    }
    
    public function testExceedingLimit()
    {
        setUrlMock("https://www.flickr.com/search/people/?username=tom+cruise", file_get_contents(__DIR__ . '/../../data/Flickr-Search-TomCruise.html'));
        
        setUrlMock("https://www.flickr.com/photos/tom-cruise", file_get_contents(__DIR__ . '/../../data/Flickr-Profile-TomCruise.html'));

        $criteria = new Criteria;
        $criteria->first_name = "tom";
        $criteria->last_name = "cruise";
        
        $fetcher = new FlickrFetcher($criteria);
        $fetcher->maxResults = 1;
        $fetcher->execute();
        $actualList = $fetcher->getOutput();
        
        $expectedList = new SearchList('flickr');
        $expectedList->setUrl('https://www.flickr.com/search/people/?username=tom+cruise');
        
        $result = new SearchResult('https://www.flickr.com/photos/tom-cruise');
        $result->setIsProfile(true);
        $result->source = "flickr";
        $result->mainSource = "flickr";
        $result->username = 'Tom cruise, THE MEGA STAR';
        $result->screenshotUrl = 'https://www.flickr.com/photos/tom-cruise';
        $result->orderInList = 0;
        $result->image = 'https://farm2.staticflickr.com/1344/buddyicons/10470787@N04.jpg';
        $expectedList->addResult($result);
        
        $this->assertEquals($expectedList, $actualList);
    }

    public function testBadResponse()
    {
        setUrlMock("https://www.flickr.com/search/people/?username=tommmmmmm+cruiseeeeeeeeeeee", file_get_contents(__DIR__ . '/../../data/Flickr-Search-TomCruiseEmpty.html'), "HTTP/1.1 404 Not Found");

        $criteria = new Criteria;
        $criteria->first_name = "tommmmmmm";
        $criteria->last_name = "cruiseeeeeeeeeeee";

        $fetcher = new FlickrFetcher($criteria);
        $fetcher->execute();
        $actualList = $fetcher->getOutput();

        $expectedList = new SearchList('flickr');
        $expectedList->setUrl('https://www.flickr.com/search/people/?username=tommmmmmm+cruiseeeeeeeeeeee');

        $this->assertEquals($expectedList, $actualList);
    }

    public function testEmptyResponse()
    {
        setUrlMock("https://www.flickr.com/search/people/?username=tommmmmmm+cruiseeeeeeeeeeee", file_get_contents(__DIR__ . '/../../data/Flickr-Search-TomCruiseEmpty.html'));
        
        $criteria = new Criteria;
        $criteria->first_name = "tommmmmmm";
        $criteria->last_name = "cruiseeeeeeeeeeee";
        
        $fetcher = new FlickrFetcher($criteria);
        $fetcher->execute();
        $actualList = $fetcher->getOutput();

        $expectedList = new SearchList('flickr');
        $expectedList->setUrl('https://www.flickr.com/search/people/?username=tommmmmmm+cruiseeeeeeeeeeee');

        $this->assertEquals($expectedList, $actualList);
    }
}
