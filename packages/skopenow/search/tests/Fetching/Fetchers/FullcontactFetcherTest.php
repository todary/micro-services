<?php
namespace Skopenow\SearchTest\Fetching\Fetchers;

use Skopenow\Search\Fetching\Fetchers\FullcontactFetcher;
use Skopenow\Search\Models\Criteria;
use Skopenow\Search\Models\SearchList;
use Skopenow\Search\Models\SearchResult;
use App\DataTypes\Address;
use App\DataTypes\Name;
use App\DataTypes\Work;
use App\DataTypes\Website;

class FullcontactFetcherTest extends \TestCase
{
    public function testFetching()
    {
        config(['settings.fullcontact_key'=>'-']);
        setUrlMock("https://api.fullcontact.com/v2/person.json?email=bart%40fullcontact.com&method=email&apiKey=-", file_get_contents(__DIR__ . '/../../data/Fullcontact-Search-Bart_AT_fullcontact.com.html'));
        
        $criteria = new Criteria;
        $criteria->email = "bart@fullcontact.com";
        
        $fetcher = new FullcontactFetcher($criteria);
        $fetcher->execute();
        $actualList = $fetcher->getOutput();

        $expectedList = new SearchList('fullcontact');
        
        // $result->image = 'https://d2ojpxxtu63wzl.cloudfront.net/static/a7e6a5aba590d4933e35eaadabd97fd2_44e00e968ac57725a15b32f9ca714827aff8e4818d290cb0c611f2e2585253b3';
        $expectedList->addDataPoint('name', Name::create(['full_name'=>"Bart Lorang"], 'fullcontact'));
        $expectedList->addDataPoint('address', Address::create([
                'full_address'=>"Boulder, Colorado, United States",
                "city"=>"Boulder",
                'state' => 'Colorado',
                'country' => 'United States'

            ], 'fullcontact'));

        $expectedList->addDataPoint('work', Work::create(['company'=>'FullContact', 'title'=>'Co-Founder & CEO', 'start'=>2010, 'end'=>null], 'fullcontact'));

        $expectedList->addDataPoint('work', Work::create(['company'=>'V1.vc', 'title'=>'Co-Founder & Managing Director', 'start'=>2015, 'end'=>null], 'fullcontact'));

        $expectedList->addDataPoint('website', Website::create(['url'=>'http://bartlorang.com'], 'fullcontact'));

        $links = [
            "https://about.me/lorangb",
            "https://angel.co/bartlorang",
            "https://www.facebook.com/bart.lorang",
            "https://www.flickr.com/people/39267654@N00",
            "https://github.com/lorangb",
            "https://plus.google.com/111748526539078793602",
            "https://gravatar.com/blorang",
            "http://news.ycombinator.com/user?id=lorangb",
            "https://instagram.com/bartlorang",
            "https://keybase.io/bartlorang",
            "https://www.linkedin.com/in/bartlorang",
            "http://www.pinterest.com/lorangb/",
            "http://www.plancast.com/lorangb",
            "http://www.quora.com/bart-lorang",
            "https://twitter.com/bartlorang",
            "https://www.xing.com/profile/bart_lorang2",
            "https://youtube.com/user/lorangb"
        ];
        for ($i=0; $i<count($links); $i++) {
            $result = new SearchResult($links[$i]);
            $expectedList->addResult($result);
        }

        
        $this->assertEquals($expectedList, $actualList);
    }
}
