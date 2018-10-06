<?php
namespace Skopenow\SearchTest\Fetching\Fetchers;

use App\DataTypes\Name;
use Skopenow\Search\Fetching\Fetchers\InstagramFetcher;
use Skopenow\Search\Models\Criteria;
use Skopenow\Search\Models\SearchList;
use Skopenow\Search\Models\SearchResult;

class InstagramFetcherTest extends \TestCase
{
    public function testFetching()
    {
        $urlMocks = [
            [
                'url' => 'https://www.instagram.com/web/search/topsearch/?context=blended&query=Rob+Douglas',
                'data' => 'Instagram-Search-RobDouglas.html',
            ],
            [
                'url' => 'http://instagram.com/rob.douglas',
                'data' => 'Instagram-Profile-rob.douglas.html',
            ],
            [
                'url' => 'http://instagram.com/robdouglas',
                'data' => 'Instagram-Profile-robdouglas.html',
            ],
        ];

        foreach ($urlMocks as $urlMock) {
            setUrlMock($urlMock['url'], file_get_contents(__DIR__ . '/../../data/' . $urlMock['data']));
        }

        $criteria = new Criteria;
        $criteria->full_name = 'Rob Douglas';

        $fetcher = new InstagramFetcher($criteria);
        $fetcher->maxResults = 2;
        $fetcher->execute();
        $actualList = $fetcher->getOutput();

        $expectedList = new SearchList('instagram');
        $expectedList->setUrl($urlMocks[0]['url']);

        $result = new SearchResult('http://instagram.com/rob.douglas');
        $result->setIsProfile(true);
        $result->addName(Name::create(['full_name' => 'Rob Douglas'], 'instagram'));

        $result->image = 'https://scontent-cai1-1.cdninstagram.com/t51.2885-19/s150x150/18252068_339154543165722_1282321859848699904_a.jpg';
        $result->username = 'rob.douglas';
        $result->orderInList = 0;
        $expectedList->addResult($result);

        $result = new SearchResult('http://instagram.com/robdouglas');
        $result->setIsProfile(true);
        $result->addName(Name::create(['full_name' => 'Rob'], 'instagram'));
        $result->image = 'https://scontent-cai1-1.cdninstagram.com/t51.2885-19/10623673_730467297018335_514637224_a.jpg';
        $result->username = 'robdouglas';
        $result->orderInList = 1;
        $expectedList->addResult($result);

        $this->assertEquals($expectedList, $actualList);
    }

    public function testBadResponse()
    {
        $url = 'https://www.instagram.com/web/search/topsearch/?context=blended&query=Rob+Douglas';
        setUrlMock($url, '', 'HTTP/1.1 404 Not Found');

        $criteria = new Criteria;
        $criteria->full_name = 'Rob Douglas';

        $fetcher = new InstagramFetcher($criteria);
        $fetcher->execute();
        $actualList = $fetcher->getOutput();

        $expectedList = new SearchList('instagram');
        $expectedList->setUrl($url);

        $this->assertEquals($expectedList, $actualList);
    }

    public function testEmptyResponse()
    {
        $url = 'https://www.instagram.com/web/search/topsearch/?context=blended&query=Rob+Douglas';
        setUrlMock($url, 'Instagram-Search-NoResults.html');

        $criteria = new Criteria;
        $criteria->full_name = 'Rob Douglas';

        $fetcher = new InstagramFetcher($criteria);
        $fetcher->execute();
        $actualList = $fetcher->getOutput();

        $expectedList = new SearchList('instagram');
        $expectedList->setUrl($url);

        $this->assertEquals($expectedList, $actualList);
    }
}
