<?php
namespace Skopenow\SearchTest\Managing\Managers;

use Skopenow\Search\Fetching\Fetchers\LinkedinFetcher;
use Skopenow\Search\Managing\Managers\LinkedinManager;
use Skopenow\Search\Models\Criteria;
use Skopenow\Search\Models\SearchList;
use Skopenow\Search\Models\SearchResult;
use Illuminate\Support\Facades\Artisan ;
use App\Models\ResultData;

class LinkedinManagerTest extends \TestCase
{

	public $matchingData = array(
		"matchingData" => array(
			"name"	=> array(
				"status"	=> true ,
				"identities"	=> array(
					"fn"	=> true ,
					"mn	"	=> false ,
					"ln"	=> true ,
					"otherName"	=> false ,
				),
				"matchWith"	=> "Rob Douglas" ,
			) ,
			"location"	=> array(
				"status" => true ,
				"identities"	=> array(
					"exct-sm"	=> true ,
					"exct-bg"	=> true ,
					"st"	=> true ,
					"pct"	=> true ,
				),
				"matchWith"	=> "Oyster Bay , NY",
			),
		),
		"isProfile"	=> true ,
		"isRelative" => false ,
		"mainSource" => "facebook" ,
		"source" => "facebook_live_in",
		"resultsCount" => 10 ,
		"link"	=> "https://www.facebook.com/rob.douglas.7923",
		"type"	=> "result",
	);

	public $fetcher;

	public function init()
	{
		$r = Artisan::call('migrate:refresh', ['--path' => 'packages/skopenow/search/database/migrations']);

		setUrlMock("https://www.linkedin.com/search/results/index/?keywords=firstname%3A%22Rob%22+lastname%3A%22Douglas%22+AND+Oyster+Bay+New+York+AND+Skopenow&locationType=I&countryCode=us", file_get_contents(__DIR__ . '/../../data/Linkedin-Search-RobDouglasSkopenow.html'));

		setUrlMock("https://www.linkedin.com/in/robdouglas?__sid=automation_sessions_linkedin", file_get_contents(__DIR__ . '/../../data/Linkedin-Profile-RobDouglas.html'));

		$criteria = new Criteria();
		$criteria->first_name = "Rob";
		$criteria->last_name = "Douglas";
		$criteria->company = "Skopenow";
		$criteria->city = "Oyster Bay";
		$criteria->state = "New York";

		$expectedList = new SearchList('linkedin');
        $expectedList->setUrl('https://www.linkedin.com/search/results/index/?keywords=firstname%3A%22Rob%22+lastname%3A%22Douglas%22+AND+Oyster+Bay+New+York+AND+Skopenow&locationType=I&countryCode=us');
        
        $result = \Mockery::mock(new SearchResult('https://www.linkedin.com/in/robdouglas'));/*->makePartial()->shouldAllowMockingProtectedMethods()*/;
        $result->shouldReceive('save')->andReturn(true);
        $result->setIsProfile(true);
        $result->username = 'robdouglas';
        $result->screenshotUrl = 'https://www.linkedin.com/in/robdouglas';
        $result->orderInList = 0;
        $result->image = 'https://media.licdn.com/mpr/mpr/shrinknp_400_400/AAEAAQAAAAAAAAhyAAAAJDliMGU0ZDcxLWU5ODYtNGI2Yi05NjFkLWUzYjdjMDlhZDVkNg.jpg';

        $expectedList->addResult($result);

		$this->fetcher = \Mockery::mock(LinkedinFetcher::class,[$criteria])->makePartial()->shouldAllowMockingProtectedMethods();
		
		$this->fetcher->shouldReceive('getOutput')->andReturn($expectedList);

	}

	public function testExecution()
	{
		$this->init();
		$this->fetcher->shouldReceive('execute')->andReturn(true);
		$manager = \Mockery::mock(LinkedinManager::class,[$this->fetcher])->makePartial()->shouldAllowMockingProtectedMethods();

		$manager->shouldReceive("checkResult")->andReturnUsing(function($result) {
            $result->setMatchStatus($this->matchingData);
            $this->assertTrue($result->save());
            return $result;
        });
        $manager->shouldReceive('saveToPending')->andReturn(true);
        $actualList = $manager->execute();
        $this->assertEquals($actualList,$this->fetcher->getOutput());
	}

	public function testErrorBeforeSave()
	{
		$this->init();
		$this->fetcher->shouldReceive('execute')->andReturn(true);
		$manager = \Mockery::mock(LinkedinManager::class,[$this->fetcher])->makePartial()->shouldAllowMockingProtectedMethods();
		$manager->shouldReceive('beforeResultSave')->andReturn(false);

		$manager->shouldReceive("checkResult")->andReturn(true);
		$manager->shouldReceive('saveToPending')->andReturn(true);
        $actualList = $manager->execute();
        $this->assertEquals($actualList,$this->fetcher->getOutput());
	}

	public function testNoData()
	{
		$this->init();
		$this->fetcher->shouldReceive('execute')->andReturn(false);
		$manager = \Mockery::mock(LinkedinManager::class,[$this->fetcher])->makePartial()->shouldAllowMockingProtectedMethods();
		$manager->shouldReceive('beforeResultSave')->andReturn(false);

		$manager->shouldReceive("checkResult")->andReturn(true);
		$manager->shouldReceive('saveToPending')->andReturn(true);
        $actualList = $manager->execute();
        $this->assertEquals($actualList,new SearchList('linkedin'));
	}
}