<?php
namespace Skopenow\SearchTest\Managing\Managers;

use Skopenow\Search\Fetching\Fetchers\MySpaceFetcher;
use Skopenow\Search\Managing\Managers\MySpaceManager;
use Skopenow\Search\Models\Criteria;
use Skopenow\Search\Models\SearchList;
use Skopenow\Search\Models\SearchResult;

class EntryPointTest extends \TestCase
{
    public function testExecution()
    {
        setUrlMock("https://myspace.com/search/people?q=Rob+Douglas", file_get_contents(__DIR__ . '/../../data/MySpace-Search-RobDouglas.html'));
        setUrlMock("https://myspace.com/douganrob", file_get_contents(__DIR__ . '/../../data/MySpace-Profile-douganrob.html'));
        setUrlMock("https://myspace.com/douglasjr_fam", file_get_contents(__DIR__ . '/../../data/MySpace-Profile-douglasjr_fam.html'));

        $criteria = new Criteria;
        $criteria->first_name = "Rob";
        $criteria->last_name = "Douglas";

        $fetcher = new MySpaceFetcher($criteria);

        $manager = new MySpaceManager($fetcher);
        $actualList = $manager->execute();

        $expectedList = new SearchList('myspace');
        $expectedList->setUrl('https://myspace.com/search/people?q=Rob+Douglas');

        $result = new SearchResult('https://myspace.com/douganrob');
        $result->setIsProfile(true);
        $result->addName('Rob Dougan');

        $result->image = 'https://a1-images.myspacecdn.com/images03/1/c8e5577762894708b3344389189709ff/300x300.jpg';
        $result->username = 'douganrob';
        $result->orderInList = 0;
        $expectedList->addResult($result);

        $result = new SearchResult('https://myspace.com/douglasjr_fam');
        $result->setIsProfile(true);
        $result->addName('Rob Douglas');
        $result->addLocation('Lathrop, CA');
        $result->image = 'https://a3-images.myspacecdn.com/images03/32/b6bffd5bf2b041c590c14128255db244/300x300.jpg';
        $result->username = 'douglasjr_fam';
        $result->orderInList = 1;
        $expectedList->addResult($result);

        $this->assertEquals($expectedList, $actualList);
    }

    public function testNoResponse()
    {
        setUrlMock("https://myspace.com/search/people?q=Rob+Douglas", "<html></html>", "HTTP/1.1 404 Not Found");
        setUrlMock("https://myspace.com/douganrob", "<html></html>", "HTTP/1.1 404 Not Found");
        setUrlMock("https://myspace.com/douglasjr_fam", "<html></html>", "HTTP/1.1 404 Not Found");

        $criteria = new Criteria;
        $criteria->first_name = "Rob";
        $criteria->last_name = "Douglas";


        $fetcher = new MySpaceFetcher($criteria);

        $manager = new MySpaceManager($fetcher);
        $actualList = $manager->execute();

        $expectedList = new SearchList('myspace');

        $this->assertEquals($expectedList, $actualList);
    }
}
