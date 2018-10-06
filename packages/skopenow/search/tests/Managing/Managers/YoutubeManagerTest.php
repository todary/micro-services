<?php
namespace Skopenow\SearchTest\Managing\Managers;

use Skopenow\Search\Fetching\Fetchers\YoutubeFetcher;
use Skopenow\Search\Managing\Managers\YoutubeManager;
use Skopenow\Search\Models\Criteria;
use Skopenow\Search\Models\SearchList;
use Skopenow\Search\Models\SearchResult;

class YoutubeManagerTest extends \TestCase
{
    public function testExecution()
    {
        setUrlMock("https://www.youtube.com/results?q=tom+cruise", file_get_contents(__DIR__ . '/../../data/Youtube-Search-TomCruise-Desktop.html'));

        $criteria = new Criteria;
        $criteria->first_name = "tom";
        $criteria->last_name = "cruise";

        $fetcher = new YoutubeFetcher($criteria);

        $manager = new YoutubeManager($fetcher);
        $actualList = $manager->execute();

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

    public function testNoResponse()
    {
        setUrlMock("https://www.youtube.com/results?q=tom+cruise", "<html></html>", "HTTP/1.1 404 Not Found");

        $criteria = new Criteria;
        $criteria->first_name = "tom";
        $criteria->last_name = "cruise";


        $fetcher = new YoutubeFetcher($criteria);

        $manager = new YoutubeManager($fetcher);
        $actualList = $manager->execute();

        $expectedList = new SearchList('youtube');

        $this->assertEquals($expectedList, $actualList);
    }
}
