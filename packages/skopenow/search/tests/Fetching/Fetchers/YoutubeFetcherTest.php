<?php
namespace Skopenow\SearchTest\Fetching\Fetchers;

use Skopenow\Search\Fetching\Fetchers\YoutubeFetcher;
use Skopenow\Search\Models\Criteria;
use Skopenow\Search\Models\SearchList;
use Skopenow\Search\Models\SearchResult;

class YoutubeFetcherTest extends \TestCase
{
    public function testFetchingCaseDesktop()
    {
        setUrlMock("https://www.youtube.com/results?q=tom+cruise", file_get_contents(__DIR__ . '/../../data/Youtube-Search-TomCruise-Desktop.html'));
        
        $criteria = new Criteria;
        $criteria->full_name = "tom cruise";
        
        $fetcher = new YoutubeFetcher($criteria);
        $fetcher->execute();
        $actualList = $fetcher->getOutput();
        
        $expectedList = new SearchList('youtube');
        $expectedList->setUrl('https://www.youtube.com/results?q=tom+cruise');
        
        $result = new SearchResult('https://www.youtube.com/watch?v=woE4GQdvu8E');
        
        $result->username = 'UCuuU2L90sqhQahV1IkhCPaQ';
        $result->orderInList = 0;
        $result->image = 'https://i.ytimg.com/vi/woE4GQdvu8E/hqdefault.jpg?sqp=-oaymwEXCPYBEIoBSFryq4qpAwkIARUAAIhCGAE=&rs=AOn4CLAAXwQ3EB3Zigi6hEnw7JmntcpdzA';
        $result->title = "American Made Official Trailer #1 (2017) Tom Cruise Thriller Movie HD";
        $expectedList->addResult($result);
        
        $result = new SearchResult('https://www.youtube.com/watch?v=AHLOEjxWy68');
        
        $result->username = 'UCJ0uqCI0Vqr2Rrt1HseGirg';
        $result->orderInList = 1;
        $result->image = 'https://i.ytimg.com/vi/AHLOEjxWy68/hqdefault.jpg?sqp=-oaymwEXCPYBEIoBSFryq4qpAwkIARUAAIhCGAE=&rs=AOn4CLCIDNDOq2sIp--4N3qATU1xyx4--w';
        $result->title = "Tom's Cruise on the River Thames Corden";
        $expectedList->addResult($result);
        
        $result = new SearchResult('https://www.youtube.com/watch?v=y92iud8QKCA');
        
        $result->username = 'UCi7GJNg51C3jgmYTUwqoUXA';
        $result->orderInList = 2;
        $result->image = 'https://i.ytimg.com/vi/y92iud8QKCA/hqdefault.jpg?sqp=-oaymwEXCPYBEIoBSFryq4qpAwkIARUAAIhCGAE=&rs=AOn4CLBp56uHHnrM3ZfarM3yntZ8wyY0-Q';
        $result->title = "Coming 9/26: Conan’s Remote With Tom Cruise  - CONAN on TBS";
        $expectedList->addResult($result);
        
        $result = new SearchResult('https://www.youtube.com/watch?v=DFacZu1XVk0');
        
        $result->username = 'UC9k-yiEpRHMNVOnOi_aQK8w';
        $result->orderInList = 3;
        $result->image = 'https://i.ytimg.com/vi/DFacZu1XVk0/hqdefault.jpg?sqp=-oaymwEXCPYBEIoBSFryq4qpAwkIARUAAIhCGAE=&rs=AOn4CLB4AJmT5RLHssTZXAzaZKEXtwmdcw';
        $result->title = "See Tom Cruise's Stunt Go Wrong on 'Mission Impossible' Set";
        $expectedList->addResult($result);
        
        $result = new SearchResult('https://www.youtube.com/watch?v=UFBZ_uAbxS0');
        
        $result->username = 'UCRO_DdwUxkV3GNLLXGh0AbA';
        $result->orderInList = 4;
        $result->image = 'https://i.ytimg.com/vi/UFBZ_uAbxS0/hqdefault.jpg?sqp=-oaymwEXCPYBEIoBSFryq4qpAwkIARUAAIhCGAE=&rs=AOn4CLCKWWIFjNHR-V7zGhgJcky40KQr8g';
        $result->title = "Tom Cruise Scientology Video - ( Original UNCUT )";
        $expectedList->addResult($result);
        
        $this->assertEquals($expectedList, $actualList);
    }

    public function testFetchingCaseMobile()
    {
        setUrlMock("https://www.youtube.com/results?q=tom+cruise", file_get_contents(__DIR__ . '/../../data/Youtube-Search-TomCruise-Mobile.html'));
        
        $criteria = new Criteria;
        $criteria->full_name = "tom cruise";
        
        $fetcher = new YoutubeFetcher($criteria);
        $fetcher->execute();
        $actualList = $fetcher->getOutput();
        
        $expectedList = new SearchList('youtube');
        $expectedList->setUrl('https://www.youtube.com/results?q=tom+cruise');
        
        $result = new SearchResult('https://www.youtube.com/watch?v=y92iud8QKCA');
        
        $result->orderInList = 0;
        $result->image = 'https://i.ytimg.com/vi/y92iud8QKCA/hqdefault.jpg?sqp=-oaymwEXCPYBEIoBSFryq4qpAwkIARUAAIhCGAE=&amp;rs=AOn4CLBp56uHHnrM3ZfarM3yntZ8wyY0-Q';
        $result->title = "Coming 9/26: Conan’s Remote With Tom Cruise  - CONAN on TBS";
        $expectedList->addResult($result);
        
        
        $result = new SearchResult('https://www.youtube.com/watch?v=woE4GQdvu8E');
        
        $result->orderInList = 1;
        $result->image = 'https://i.ytimg.com/vi/woE4GQdvu8E/hqdefault.jpg?sqp=-oaymwEXCPYBEIoBSFryq4qpAwkIARUAAIhCGAE=&amp;rs=AOn4CLAAXwQ3EB3Zigi6hEnw7JmntcpdzA';
        $result->title = "American Made Official Trailer #1 (2017) Tom Cruise Thriller Movie HD";
        $expectedList->addResult($result);
        
        
        
        $result = new SearchResult('https://www.youtube.com/watch?v=AHLOEjxWy68');
        
        $result->orderInList = 2;
        $result->image = 'https://i.ytimg.com/vi/AHLOEjxWy68/hqdefault.jpg?sqp=-oaymwEXCPYBEIoBSFryq4qpAwkIARUAAIhCGAE=&amp;rs=AOn4CLCIDNDOq2sIp--4N3qATU1xyx4--w';
        $result->title = "Tom&#39;s Cruise on the River Thames Corden";
        $expectedList->addResult($result);
        
        
        $result = new SearchResult('https://www.youtube.com/watch?v=bwTFXVdbeEw');
        
        $result->orderInList = 3;
        $result->image = 'https://i.ytimg.com/vi/bwTFXVdbeEw/hqdefault.jpg?sqp=-oaymwEXCPYBEIoBSFryq4qpAwkIARUAAIhCGAE=&amp;rs=AOn4CLDYzoZxuUPcjxZlrVI-ST_0W-Xcrw';
        $result->title = "The Mummy Official Trailer #1 (2017) Tom Cruise, Sofia Boutella Action Movie HD";
        $expectedList->addResult($result);
        
        
        $result = new SearchResult('https://www.youtube.com/watch?v=DFacZu1XVk0');
        
        $result->orderInList = 4;
        $result->image = 'https://i.ytimg.com/vi/DFacZu1XVk0/hqdefault.jpg?sqp=-oaymwEXCPYBEIoBSFryq4qpAwkIARUAAIhCGAE=&amp;rs=AOn4CLB4AJmT5RLHssTZXAzaZKEXtwmdcw';
        $result->title = "See Tom Cruise&#39;s Stunt Go Wrong on &#39;Mission Impossible&#39; Set";
        $expectedList->addResult($result);
        
        
        
        $this->assertEquals($expectedList, $actualList);
    }
    
    public function testExceedingLimit()
    {
        setUrlMock("https://www.youtube.com/results?q=tom+cruise", file_get_contents(__DIR__ . '/../../data/Youtube-Search-TomCruise-Desktop.html'));
        
        $criteria = new Criteria;
        $criteria->full_name = "tom cruise";
        
        $fetcher = new YoutubeFetcher($criteria);
        $fetcher->maxResults = 1;
        $fetcher->execute();
        $actualList = $fetcher->getOutput();
        
        $expectedList = new SearchList('youtube');
        $expectedList->setUrl('https://www.youtube.com/results?q=tom+cruise');
        
        $result = new SearchResult('https://www.youtube.com/watch?v=woE4GQdvu8E');
        
        $result->username = 'UCuuU2L90sqhQahV1IkhCPaQ';
        $result->orderInList = 0;
        $result->image = 'https://i.ytimg.com/vi/woE4GQdvu8E/hqdefault.jpg?sqp=-oaymwEXCPYBEIoBSFryq4qpAwkIARUAAIhCGAE=&rs=AOn4CLAAXwQ3EB3Zigi6hEnw7JmntcpdzA';
        $result->title = "American Made Official Trailer #1 (2017) Tom Cruise Thriller Movie HD";
        $expectedList->addResult($result);
        
        $this->assertEquals($expectedList, $actualList);
    }
    
    public function testBadResponse()
    {
        setUrlMock("https://www.youtube.com/results?q=tom+cruise", file_get_contents(__DIR__ . '/../../data/Youtube-Search-TomCruise-Desktop.html'), "HTTP/1.1 404 Not Found");
        
        $criteria = new Criteria;
        $criteria->full_name = "tom cruise";
        
        $fetcher = new YoutubeFetcher($criteria);
        $fetcher->execute();
        $actualList = $fetcher->getOutput();
        
        $expectedList = new SearchList('youtube');
        $expectedList->setUrl('https://www.youtube.com/results?q=tom+cruise');
        
        $this->assertEquals($expectedList, $actualList);
    }

    public function testEmptyResponse()
    {
        setUrlMock("https://www.youtube.com/results?q=tom+cruise", "");
        
        $criteria = new Criteria;
        $criteria->full_name = "tom cruise";
        
        $fetcher = new YoutubeFetcher($criteria);
        $fetcher->execute();
        $actualList = $fetcher->getOutput();
        
        $expectedList = new SearchList('youtube');
        $expectedList->setUrl('https://www.youtube.com/results?q=tom+cruise');
        
        $this->assertEquals($expectedList, $actualList);
    }
    
    public function testAutoCorrection()
    {
        setUrlMock("https://www.youtube.com/results?q=mummy+rtombbb", file_get_contents(__DIR__ . '/../../data/Youtube-Search-AutoCorrection.html'));
        
        $criteria = new Criteria;
        $criteria->full_name = "mummy rtombbb";
        
        $fetcher = new YoutubeFetcher($criteria);
        $fetcher->execute();
        $actualList = $fetcher->getOutput();
        
        $expectedList = new SearchList('youtube');
        $expectedList->setUrl('https://www.youtube.com/results?q=mummy+rtombbb');
        
        $this->assertEquals($expectedList, $actualList);
    }
    
    public function testImgDataThumb()
    {
        setUrlMock("https://www.youtube.com/results?q=tom+cruise", file_get_contents(__DIR__ . '/../../data/Youtube-Search-DataThumb.html'));
        
        $criteria = new Criteria;
        $criteria->full_name = "tom cruise";
        
        $fetcher = new YoutubeFetcher($criteria);
        $fetcher->execute();
        $actualList = $fetcher->getOutput();
        
        $this->assertEquals(5, count($actualList->getResults()));
    }
    
    public function testChannelRenderer()
    {
        setUrlMock("https://www.youtube.com/results?q=rob+douglas", file_get_contents(__DIR__ . '/../../data/Youtube-Search-ChannelTest.html'));
        
        $criteria = new Criteria;
        $criteria->full_name = "rob douglas";
        
        $fetcher = new YoutubeFetcher($criteria);
        $fetcher->execute();
        $actualList = $fetcher->getOutput();
        
        $expectedList = new SearchList('youtube');
        $expectedList->setUrl('https://www.youtube.com/results?q=rob+douglas');
        
        $result = new SearchResult('https://www.youtube.com/watch?v=FzP2CMAQ7qA');
        
        $result->username = 'UCFjxi3RutMPomt4HALGYrnA';
        $result->orderInList = 0;
        $result->image = 'https://i.ytimg.com/vi/FzP2CMAQ7qA/hqdefault.jpg?sqp=-oaymwEXCPYBEIoBSFryq4qpAwkIARUAAIhCGAE=&rs=AOn4CLBvrGcin0xgqMviG-1pQ-cB7gbpow';
        $result->title = "Fastest Sailor in the World! Rob Douglas \"interview\"";
        $expectedList->addResult($result);
        
        
        $result = new SearchResult('https://www.youtube.com/watch?v=jtAmFKaThNE');
        
        $result->username = 'UCJpO2jOH5lDCTaVOKH719nw';
        $result->orderInList = 1;
        $result->image = 'https://i.ytimg.com/vi/jtAmFKaThNE/hqdefault.jpg?sqp=-oaymwEXCPYBEIoBSFryq4qpAwkIARUAAIhCGAE=&rs=AOn4CLCwhRKKXAfpYrziPWnP8MvaJNrTog';
        $result->title = "Rob Dougan - Furious Angels";
        $expectedList->addResult($result);
        
        
        $result = new SearchResult('https://www.youtube.com/user/daddyof4intx');
        
        $result->username = 'UCi2mpmShF0ZCj7rCGnKA7zw';
        $result->orderInList = 2;
        $result->image = 'https://yt3.ggpht.com/-gu5dbJNlQy4/AAAAAAAAAAI/AAAAAAAAAAA/lHfe0bL5c-k/s176-c-k-no-mo-rj-c0xffffff/photo.jpg';
        $result->title = "Rob Douglas";
        $expectedList->addResult($result);
        
        
        $result = new SearchResult('https://www.youtube.com/watch?v=Sh6vcN7gEek');
        
        $result->username = 'UCWAdvZ7N0GQWEoPsjek1UrA';
        $result->orderInList = 3;
        $result->image = 'https://i.ytimg.com/vi/Sh6vcN7gEek/hqdefault.jpg?sqp=-oaymwEXCPYBEIoBSFryq4qpAwkIARUAAIhCGAE=&rs=AOn4CLD_7P4ua3n90xUYAScLszWAh3MsUA';
        $result->title = "Rob Douglas Luderitz World Speed Sailing Record kitesurf";
        $expectedList->addResult($result);
        
        $result = new SearchResult('https://www.youtube.com/watch?v=7iox_-1TAjE');
        
        $result->username = 'UCQV-TuDfSkRfHc4gDBDZrvQ';
        $result->orderInList = 4;
        $result->image = 'https://i.ytimg.com/vi/7iox_-1TAjE/hqdefault.jpg?sqp=-oaymwEXCPYBEIoBSFryq4qpAwkIARUAAIhCGAE=&rs=AOn4CLAwRvOgFvkv51f5cbyvOdVpQnLiDA';
        $result->title = "Rob Douglas run 55.65 knts";
        $expectedList->addResult($result);
        
        $this->assertEquals(5, count($actualList->getResults()));
    }
}
