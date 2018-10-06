<?php
namespace Skopenow\SearchTest\Managing\Managers;

use Skopenow\Search\Fetching\Fetchers\Facebook\FacebookByPhone;
use Skopenow\Search\Managing\Managers\Facebook\FacebookPeopleManager;
use Skopenow\Search\Models\Criteria;
use Skopenow\Search\Models\SearchList;
use Skopenow\Search\Models\SearchResult;
use Illuminate\Support\Facades\Artisan ;
use App\Models\Result;

class FacebookPeopleManagerTest extends \TestCase
{
    protected $expectedList;

    public function init()
    {
        $r = Artisan::call('migrate:refresh', ['--path' => 'packages/skopenow/search/database/migrations']);

        $this->expectedList = new SearchList('facebook');
        $this->expectedList->setUrl('https://www.facebook.com/search/str/01285446018/keywords_top?em=1');

        $result = new SearchResult('https://www.facebook.com/m.magdy.cg?ref=br_rs');
        $result->url = "https://www.facebook.com/m.magdy.cg";
        $result->setIsProfile(true);
        $result->addName("Mahmoud Magdy");
        $result->image = 'https://scontent-cai1-1.xx.fbcdn.net/v/t1.0-1/cp0/e15/q65/c4.26.48.48/p56x56/307310_280355545309387_431547637_n.jpg?efg=eyJpIjoidCJ9&oh=1be48b551c0778f67a03fcbb91c691ec&oe=5A7F11DB';
        $result->addExperience([
            [
                "image" =>  'https://scontent-cai1-1.xx.fbcdn.net/v/t1.0-1/cp0/e15/q65/c4.26.48.48/p56x56/307310_280355545309387_431547637_n.jpg?efg=eyJpIjoidCJ9&oh=1be48b551c0778f67a03fcbb91c691ec&oe=5A7F11DB',
                "company"   =>  "H3S ASU"
            ],
            [
                "image" =>  'https://scontent-cai1-1.xx.fbcdn.net/v/t1.0-1/cp0/e15/q65/p48x48/994519_10201044761218004_748892446_n.jpg?efg=eyJpIjoidCJ9&oh=5e7599818beb964df15ec181643e5a9c&oe=5A7C4520',
                "company"   =>  "faculty of engineering"
            ],
            [
                "image" =>  'https://scontent-cai1-1.xx.fbcdn.net/v/t1.0-1/cp0/e15/q65/c0.3.48.48/p48x48/19324_10153812952638569_767427622881300383_n.jpg?efg=eyJpIjoidCJ9&oh=e9d18fd2a074171d760f1162ccb28ad1&oe=5A86E99E',
                "company"   =>  "C.O.R.D.",
                "position"  => "Multimedia Head"
            ],
        ]);
        $result->addEducation([
            [
                "image" => 'https://external-cai1-1.xx.fbcdn.net/safe_image.php?d=AQDb0bhoi3AifBir&w=48&h=48&url=https\25 3A\25 2F\25 2Fupload.wikimedia.org\25 2Fwikipedia\25 2Fcommons\25 2Fthumb\25 2Fc\25 2Fce\25 2FAin_Shams_University-Zafarana_Palace2.JPG\25 2F720px-Ain_Shams_University-Zafarana_Palace2.JPG&cfs=1&fallback=hub_education&f&_nc_hash=AQANplmA1pZYK1sd',
                "school" => 'Faculty of Engineering\\',
                "title" => "College",
            ],
            [
                "image" => 'https://scontent-cai1-1.xx.fbcdn.net/v/t1.0-1/cp0/e15/q65/c13.4.48.48/p56x56/1151032_673448579351370_496713161_n.jpg?efg=eyJpIjoidCJ9&oh=1b12f72cd9880a1036805d723d615cdb&oe=5A7AF567',
                "school" => "مدرسة المتفوقين الثانوية بنين بعين شمس",
                "title" => "High School",
            ]
        ]);

        $result->orderInList = 0;
        $this->expectedList->addResult($result);
    }

    public function testExecution()
    {
        $this->init();
        setUrlMock("https://www.facebook.com/search/str/01285446018/keywords_top?em=1", file_get_contents(__DIR__ . '/../../../data/facebook/Search-01285446018-Phone.html'));
        setUrlMock("https://m.facebook.com/m.magdy.cg/about", file_get_contents(__DIR__ . '/../../../data/facebook/Profile-MahmoudMagdy-Phone.html'));
        $criteria = new Criteria;
        $criteria->phone = "01285446018";

        // $fetcher = new FacebookByPhone($criteria);
        $fetcher = $this->createMock(FacebookByPhone::class);
        $fetcher->method("execute")->willReturn(true);

        $fetcher->method("getOutput")->willReturn($this->expectedList);

        $manager = \Mockery::mock(FacebookPeopleManager::class,[$fetcher])->makePartial()->shouldAllowMockingProtectedMethods(); 
        $manager->shouldReceive("checkResult")->andReturnUsing(function($result){
            $result->setMatchStatus(["name"=>true]);
            return $result;
        });

        $actualList = $manager->execute();
        $this->assertEquals($this->expectedList, $actualList);
    }

    public function testFailedMatching()
    {
        $this->init();
        setUrlMock("https://www.facebook.com/search/str/01285446018/keywords_top?em=1", file_get_contents(__DIR__ . '/../../../data/facebook/Search-01285446018-Phone.html'));
        setUrlMock("https://m.facebook.com/m.magdy.cg/about", file_get_contents(__DIR__ . '/../../../data/facebook/Profile-MahmoudMagdy-Phone.html'));
        $criteria = new Criteria;
        $criteria->phone = "01285446018";

        $fetcher = $this->createMock(FacebookByPhone::class);
        $fetcher->method("execute")->willReturn(true);

        $fetcher->method("getOutput")->willReturn($this->expectedList);

        $manager = \Mockery::mock(FacebookPeopleManager::class,[$fetcher])->makePartial()->shouldAllowMockingProtectedMethods(); 
        $manager->shouldReceive("checkResult")->andReturn(false);

        $actualList = $manager->execute();

        $this->assertEquals($this->expectedList, $actualList);
    }

    public function testFailedBeforeSave()
    {
        $this->init();
        setUrlMock("https://www.facebook.com/search/str/01285446018/keywords_top?em=1", file_get_contents(__DIR__ . '/../../../data/facebook/Search-01285446018-Phone.html'));
        setUrlMock("https://m.facebook.com/m.magdy.cg/about", file_get_contents(__DIR__ . '/../../../data/facebook/Profile-MahmoudMagdy-Phone.html'));
        $criteria = new Criteria;
        $criteria->phone = "01285446018";

        $fetcher = $this->createMock(FacebookByPhone::class);
        $fetcher->method("execute")->willReturn(true);

        $fetcher->method("getOutput")->willReturn($this->expectedList);

        $manager = \Mockery::mock(FacebookPeopleManager::class,[$fetcher])->makePartial()->shouldAllowMockingProtectedMethods(); 
        $manager->shouldReceive("checkResult")->andReturn(true);
        $manager->shouldReceive("beforeResultSave")->andReturn(false);

        $actualList = $manager->execute();

        $this->assertEquals($this->expectedList, $actualList);
    }

    public function testNoResponse()
    {
        setUrlMock("https://www.facebook.com/search/str/01285446018/keywords_top?em=1","<html></html>");
        setUrlMock("https://www.facebook.com/search/people/?q=01285446018&em=1","<html></html>");
        setUrlMock("https://m.facebook.com/m.magdy.cg/about", "<html></html>");

        $criteria = new Criteria;
        $criteria->phone = "01285446018";

        $fetcher = $this->createMock(FacebookByPhone::class);
        $fetcher->method("execute")->willReturn(true);
        $fetcher->method("getOutput")->willReturn($this->expectedList);

        $manager = \Mockery::mock(FacebookPeopleManager::class,[$fetcher])->makePartial()->shouldAllowMockingProtectedMethods(); 
        $manager->shouldReceive("checkResult")->andReturnUsing(function($result){
            $result->setMatchStatus(["name"=>true]);
            return $result;
        });

        $fetcher = new FacebookByPhone($criteria);

        $manager = new FacebookPeopleManager($fetcher);
        $actualList = $manager->execute();

        $this->expectedList = new SearchList('facebook');

        $this->assertEquals($this->expectedList, $actualList);
    }
}
