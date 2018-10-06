<?php
namespace Skopenow\SearchTest\Fetching\Fetchers;

use Skopenow\Search\Fetching\Fetchers\GoogleimageFetcher;
use Skopenow\Search\Models\Criteria;
use Skopenow\Search\Models\SearchList;
use Skopenow\Search\Models\SearchResult;

class GoogleimageFetcherTest extends \TestCase
{
    public function testFetching()
    {
        $urlMocks = [
            [
                'url' => 'https://www.google.com/searchbyimage?image_url=https%3A%2F%2Fscontent-cai1-1.cdninstagram.com%2Ft51.2885-19%2Fs150x150%2F18252068_339154543165722_1282321859848699904_a.jpg',
                'data' => 'Googleimage-Search-FirstPage.html',
            ],
            [
                'url' => 'https://www.google.com/searchbyimage?image_url=https%3A%2F%2Fscontent-cai1-1.cdninstagram.com%2Ft51.2885-19%2Fs150x150%2F18252068_339154543165722_1282321859848699904_a.jpg&start=0',
                'data' => 'Googleimage-Search-FirstPage.html',
            ],
            [
                'url' => 'https://www.google.com/searchbyimage?image_url=https%3A%2F%2Fscontent-cai1-1.cdninstagram.com%2Ft51.2885-19%2Fs150x150%2F18252068_339154543165722_1282321859848699904_a.jpg&start=10',
                'data' => 'Googleimage-Search-SecondPage.html',
            ],
            [
                'url' => 'https://www.google.com/searchbyimage?image_url=https%3A%2F%2Fscontent-cai1-1.cdninstagram.com%2Ft51.2885-19%2Fs150x150%2F18252068_339154543165722_1282321859848699904_a.jpg&start=20',
                'data' => 'Googleimage-Search-SecondPage.html',
            ],
        ];

        foreach ($urlMocks as $urlMock) {
            setUrlMock($urlMock['url'], file_get_contents(__DIR__ . '/../../data/' . $urlMock['data']));
        }

        $criteria = new Criteria;
        $criteria->url = 'https://scontent-cai1-1.cdninstagram.com/t51.2885-19/s150x150/18252068_339154543165722_1282321859848699904_a.jpg';

        $fetcher = new GoogleimageFetcher($criteria);
        $fetcher->maxResults = 2;
        $fetcher->execute();
        $actualList = $fetcher->getOutput();

        $expectedList = new SearchList('googleimage');
        $expectedList->setUrl($urlMocks[0]['url']);

        $result = new SearchResult('http://stalkture.com/search/rob.douglas');
        

        $result->url = 'http://stalkture.com/search/rob.douglas';
        $result->orderInList = 0;
        $expectedList->addResult($result);

        $result = new SearchResult('http://gramomanias.com/user/d2gexperience');
        
        $result->url = 'http://gramomanias.com/user/d2gexperience';
        $result->orderInList = 1;
        $expectedList->addResult($result);

        $this->assertEquals($expectedList, $actualList);
    }

    public function testBadResponse()
    {
        $url = 'https://www.google.com/searchbyimage?image_url=';
        setUrlMock($url, '', 'HTTP/1.1 404 Not Found');
        setUrlMock($url . '&start=0', '', 'HTTP/1.1 404 Not Found');

        $criteria = new Criteria;

        $fetcher = new GoogleimageFetcher($criteria);
        $fetcher->execute();
        $actualList = $fetcher->getOutput();

        $expectedList = new SearchList('googleimage');
        $expectedList->setUrl($url);

        $this->assertEquals($expectedList, $actualList);
    }

    public function testEmptyResponse()
    {
        $url = 'https://www.google.com/searchbyimage?image_url=';
        setUrlMock($url, 'Googleimage-Search-NoResults.html');
        setUrlMock($url . '&start=0', 'Googleimage-Search-NoResults.html');
        setUrlMock($url . '&start=10', 'Googleimage-Search-NoResults.html');
        setUrlMock($url . '&start=20', 'Googleimage-Search-NoResults.html');

        $criteria = new Criteria;

        $fetcher = new GoogleimageFetcher($criteria);
        $fetcher->execute();
        $actualList = $fetcher->getOutput();

        $expectedList = new SearchList('googleimage');
        $expectedList->setUrl("https://www.google.com/searchbyimage?image_url=");

        $this->assertEquals($expectedList, $actualList);
    }
}
