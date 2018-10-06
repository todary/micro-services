<?php

namespace Skopenow\Result\tests\Save;

use Mockery;
use Cache;
use Skopenow\Result\DataSourceBridge;
use Skopenow\Result\DataSourceLuman;
use Skopenow\Result\Save\ResultSave;
use Skopenow\Result\Save\RejectedResult;
use Skopenow\Result\Banned;
use App\Models\ResultData;
use Skopenow\Scoring\EntryPoint as Scoring;
use App\DataTypes\Name;
use App\DataTypes\Address;
use App\DataTypes\Email;

use Illuminate\Support\Facades\Artisan;

class ResultSaveTest extends \TestCase
{
    protected $dbSource;
    protected $rejected;
    protected $resultData;
    protected $scoring;
    protected $matchstatus = array(
        "matchingData" => array(
            "name"  => array(
                "status"    => true ,
                "identities"    => array(
                    "fn"    => true ,
                    "mn "   => false ,
                    "ln"    => true ,
                    "otherName" => false ,
                ),
                "matchWith" => "Rob Douglas" ,
            ) ,
            "location"  => array(
                "status" => true ,
                "identities"    => array(
                    "exct-sm"   => true ,
                    "exct-bg"   => true ,
                    "st"    => true ,
                    "pct"   => true ,
                ),
                "distance"=>3,
                "matchWith" => "Oyster Bay , NY",
            ),
        ),
        "isProfile" => true ,
        "isRelative" => false ,
        "mainSource" => "facebook" ,
        "source" => "facebook_live_in",
        "resultsCount" => 10 ,
        "link"  => "https://www.facebook.com/rob.douglas.7923",
        "type"  => "result",
        "results_page_type_id"=>1,
        "additional"=>"",
    );

    protected $scoreingData = [
        "identifiers" =>'{"main":{"fn":"rob","ln":"douglas","ct":"Oyster Bay","st":"NY"},"extra":{"st":"NY","fn":"rob","ln":"douglas"}}',
        "identities"=>'["exct","IN","ln","exactName","pic","exct-bg","fn","st","rltv"]',
        "sourceTypeScore"=>1,
        "resultTypeScore"=>1,
        "listCountScore"=>0.365212,
        "finalScore"=>1.5296,
        "flags"=>573+1024
    ];
    
    public function setup()
    {
        $r = Artisan::call('migrate:refresh', ['--path' => 'packages/skopenow/result/src/database/migrations']);
        config(["state.report_id"=>5]);
        config(["state.combination_id"=>6]);
        $this->rejected = \Mockery::mock(RejectedResult::class);
        $this->dbSource = \Mockery::mock(DataSourceLuman::class);
        

        require __DIR__ . '/../data/dbData.php';
       

        \Cache::forget('score_type');
        \Cache::forget('score_identity');
        \Cache::forget('score_single_result');
        \Cache::forget('score_results_count');
        \Cache::forget('score_sources');
        \Cache::forget('quantcastList');
        \Cache::forget('score_single_result');
        \Cache::forget('BannedDomains');
        \Cache::forget('Reportdata_120a7372c7e6e7658fee01a5d6e5c7d47');
        \Cache::forget('Reportdata_1d41d8cd98f00b204e9800998ecf8427e');

        \DB::table("score_identity")->insert($score_identity);
        \DB::table("score_results_count")->insert($score_results_count);
        \DB::table("main_source")->insert($main_source);
        \DB::table("score_single_result")->insert($score_single_result);
        \DB::table("score_sources")->insert($score_sources);
        \DB::table("score_type")->insert($score_type);
        \DB::table("persons")->insert($persons);
        \DB::table("source")->insert($source);
        \DB::table("banned_domains")->insert($banned_domains);
        
        $this->scoring = \Mockery::mock(Scoring::class);

        setUrlMock("https://m.facebook.com/kim.douglas.370/about", file_get_contents(__DIR__ . '/../data/Facebook-Profile-Kim.html'));

        $url = "https://facebook.com/kim.douglas.370";
        $this->resultData = new ResultData($url);
    }
    
    /**
      * @expectedException     \Exception
      */
    public function testSaveDeadLock()
    {
        $this->resultData->setMatchStatus($this->matchstatus);
        $this->resultData->url = "https://www.facebook.com/rob.douglas.111";
        $this->resultData->addLocation(Address::create(["full_address"=>"Milwaukee, Wisconsin"], "facebook"));
        $this->resultData->setIsProfile(true);
        $this->resultData->setIsRelative(true);
        $this->resultData->setIsManual(true);
        $this->resultData->setRawType("list");

        $this->dbSource->shouldReceive("saveResult")->andThrow(\Exception::class, "Deadlock found");
        $this->scoring->shouldReceive("init")->andReturn($this->scoreingData);
        $resultSave = new ResultSave($this->dbSource, $this->scoring, $this->rejected);

        $this->expectException($resultSave->saveResult($this->resultData));
    }

    /**
      * @expectedException     \Exception
      */
    public function testExceptionError()
    {
        $this->resultData->setMatchStatus($this->matchstatus);
        $this->resultData->url = "https://www.facebook.com/rob.douglas.111";
        $this->resultData->addLocation(Address::create(["full_address"=>"Milwaukee, Wisconsin"], "facebook"));
        $this->resultData->setIsProfile(true);
        $this->resultData->setIsRelative(true);
        $this->resultData->setIsManual(true);
        $this->resultData->setRawType("list");

        $this->dbSource->shouldReceive("saveResult")->andThrow(\Exception::class, "Unknown error");
        $this->scoring->shouldReceive("init")->andReturn($this->scoreingData);

        $resultSave = Mockery::mock(ResultSave::class, [$this->dbSource,
            $this->scoring,
            $this->rejected])->makePartial()->shouldAllowMockingProtectedMethods();

        $this->expectException($resultSave->saveResult($this->resultData));
    }

     /**
      * @expectedException     \Exception
      */
    public function testExceptionDeadLockRelatedSave()
    {
        $this->resultData->setMatchStatus($this->matchstatus);
        $this->resultData->url = "https://www.facebook.com/rob.douglas.111";
        $this->resultData->addName(Name::create(["full_name"=>"Rob Douglas"], "facebook"));
        $this->resultData->addLocation(Address::create(["full_address"=>"Milwaukee, Wisconsin"], "facebook"));
        $this->resultData->setIsProfile(true);
        $this->resultData->setIsRelative(true);
        $this->resultData->setIsManual(true);
        $this->resultData->setRawType("list");

        $this->dbSource->shouldReceive("saveResult")->andThrow(\Exception::class, "Deadlock found");
        $this->scoring->shouldReceive("init")->andReturn($this->scoreingData);
        
        $related = [[
            "url" =>"https://www.facebook.com/rob.douglas.111",
            "result" => $this->resultData
        ]];
        
        
        $resultSave = Mockery::mock(ResultSave::class, [$this->dbSource,
            $this->scoring,
            $this->rejected])->makePartial()->shouldAllowMockingProtectedMethods();

        $this->expectException($resultSave->saveRelatedUrl($related, 0));
    }

    public function testSaveResultRejected()
    {
        $this->resultData->setMatchStatus($this->matchstatus);
        $scoreing = [
            "identifiers" =>'{"main":{}',
            "identities"=>'[]',
            "sourceTypeScore"=>0,
            "resultTypeScore"=>0,
            "listCountScore"=>0,
            "finalScore"=>0,
            "flags"=>0
        ];
        $this->scoring->shouldReceive("init")->andReturn($scoreing);
        $this->rejected->shouldReceive("save")->andReturn(true);
        
        $resultSave = Mockery::mock(ResultSave::class, [$this->dbSource,
            $this->scoring,
            $this->rejected])->makePartial()->shouldAllowMockingProtectedMethods();

        $resultSave->shouldReceive("runAfterInsert")->andReturn(null);

        $expected = [
          "https://facebook.com/kim.douglas.370" => [
            "action" => "rejected",
            "resultId" => null,
            "reason" => 33554689,
          ]
        ];

        $this->assertEquals($expected, $resultSave->saveResult($this->resultData));
    }

    public function testResultSave()
    {
        $this->resultData->setMatchStatus($this->matchstatus);
        $this->resultData->source = "facebook";
        $this->resultData->mainSource = "facebook";
        $this->resultData->url = "https://www.facebook.com/rob.douglas.111";
        $this->resultData->addName(Name::create(["full_name"=>"rob M douglas"], "facebook"));
        $this->resultData->addLocation(Address::create(["full_address"=>"Milwaukee, Wisconsin"], "facebook"));
        $this->resultData->setIsProfile(true);
        $this->resultData->setIsRelative(true);
        $this->resultData->setIsManual(true);
        $this->resultData->username = "rob.douglas.111";
        $education = new \ArrayIterator(["Programming"]);
        $this->resultData->setEducation($education);
        $this->resultData->addLink(array("url"=>"https://www.youtube.com/kim.douglas", "reason" => 2));
        $identities = ["uniq","fn","ln","exactName","pic"];
        $this->resultData->setScoreIdentities($identities);

        $this->dbSource->shouldReceive("saveResult")->times(2)->andReturn(20201220, 20201221);
        $this->dbSource->shouldReceive("getResult")->andReturn(["id"=>"20201220"]);
        $this->dbSource->shouldReceive("getResult")->andReturn(["id"=>"20201221"]);

        $this->scoring->shouldReceive("init")->times(2)->andReturn($this->scoreingData);
        //$this->scoring->shouldReceive("rescore")->times(2)->andReturn($this->scoreingData);
        
        $resultSave = Mockery::mock(ResultSave::class, [$this->dbSource,
            $this->scoring,
            $this->rejected])->makePartial()->shouldAllowMockingProtectedMethods();

        $resultSave->shouldReceive("runAfterInsert")->andReturn(null);
        $resultSave->shouldReceive("getResult")->andReturn($this->resultData);

        $expected = [
          "https://www.facebook.com/rob.douglas.111" => [
            "invisible_reason" => 0,
            "action" => "save",
            "resultId" => 20201220,
          ]
        ];

        $this->assertEquals($expected, $resultSave->saveResult($this->resultData));
    }

    public function testResultFoundUpdate()
    {
        $this->resultData->setMatchStatus($this->matchstatus);
        $this->resultData->source = "facebook";
        $this->resultData->mainSource = "facebook";
        $this->resultData->url = "https://www.facebook.com/rob.douglas.111";
        $this->resultData->addName(Name::create(["full_name"=>"rob M douglas"], "facebook"));
        $this->resultData->addLocation(Address::create(["full_address"=>"Milwaukee, Wisconsin"], "facebook"));
        $this->resultData->setIsProfile(true);
        $this->resultData->setIsRelative(true);
        $this->resultData->setIsManual(true);
        $this->resultData->username = "rob.douglas.111";
        $education = new \ArrayIterator(["Programming"]);
        $this->resultData->setEducation($education);
        $this->resultData->addLink(array("url"=>"https://www.youtube.com/kim.douglas", "reason" => 2));
        $identities = ["uniq","fn","ln","exactName","pic"];
        $this->resultData->setScoreIdentities($identities);

        $this->dbSource->shouldReceive("saveResult")->times(2)->andThrow(\Exception::class, "Duplicate entry");
        $this->dbSource->shouldReceive("updateResult")->times(2)->andReturn(1, 1);
        $this->dbSource->shouldReceive("getResult")->andReturn(["id"=>"20201220"]);
        $this->dbSource->shouldReceive("getResult")->andReturn(["id"=>"20201221"]);

        $this->scoring->shouldReceive("init")->times(2)->andReturn($this->scoreingData);
        $this->scoring->shouldReceive("rescore")->times(2)->andReturn($this->scoreingData);
        
        $resultSave = Mockery::mock(ResultSave::class, [$this->dbSource,
            $this->scoring,
            $this->rejected])->makePartial()->shouldAllowMockingProtectedMethods();

        $resultSave->shouldReceive("runAfterUpdate")->andReturn(null);
        $resultSave->shouldReceive("getResult")->andReturn($this->resultData);

        $expected = [
          "https://www.facebook.com/rob.douglas.111" => [
            "invisible_reason" => 0,
            "action" => "update",
            "resultId" => 20201220,
          ]
        ];

        $this->assertEquals($expected, $resultSave->saveResult($this->resultData));
    }

    public function testUpdateDirectNoIdNoUrl()
    {
        $this->dbSource->shouldReceive("updateResult")->andReturn(1);
        $resultSave = Mockery::mock(ResultSave::class, [$this->dbSource,
            $this->scoring,
            $this->rejected])->makePartial()->shouldAllowMockingProtectedMethods();

        $this->assertEquals(0, $resultSave->updateResult([], null, null, null));
    }

    public function testUpdateDirectNoAffect()
    {
        $this->dbSource->shouldReceive("updateResult")->andReturn(0);
        $this->dbSource->shouldReceive("getResult")->andReturn(["content"=>"https://www.facebook.com/rob.douglas.111", "id"=>20201220]);
        $resultSave = Mockery::mock(ResultSave::class, [$this->dbSource,
            $this->scoring,
            $this->rejected])->makePartial()->shouldAllowMockingProtectedMethods();

        $this->assertEquals(20201220, $resultSave->updateResult([], null, "https://www.facebook.com/rob.douglas.111", null));
    }

    public function testUpdateDirectIdNoUrl()
    {
        $this->dbSource->shouldReceive("updateResult")->andReturn(1);
        $this->dbSource->shouldReceive("getResult")->andReturn(["content"=>"https://www.facebook.com/rob.douglas.111", "id"=>20201220]);

        $this->scoring->shouldReceive("rescore")->andReturn($this->scoreingData);
        $resultSave = Mockery::mock(ResultSave::class, [$this->dbSource,
            $this->scoring,
            $this->rejected])->makePartial()->shouldAllowMockingProtectedMethods();
        $resultSave->shouldReceive("runAfterUpdate")->andReturn(null);

        $identities = ["uniq","fn","ln","exactName","pic"];
        $output = $resultSave->updateResult(["identities" => $identities], 20201220, null, null);
        
        $this->assertEquals(20201220, $output);
    }

    public function testUpdateDirectIdNoUrlAndRescoring()
    {
        $identities = ["uniq","fn","ln","exactName","pic"];
        $updateData = $this->matchstatus;
        $updateData["identities"] = $identities;
        
        $this->dbSource->shouldReceive("updateResult")->andReturn(1);
        $this->dbSource->shouldReceive("getResult")->andReturn(["content"=>"https://www.facebook.com/rob.douglas.111", "id"=>20201220]);

        $this->scoring->shouldReceive("rescore")->andReturn($this->scoreingData);

        $resultSave = Mockery::mock(ResultSave::class, [$this->dbSource,
            $this->scoring,
            $this->rejected])->makePartial()->shouldAllowMockingProtectedMethods();

        $resultSave->shouldReceive("runAfterUpdate")->andReturn(null);


        $this->assertEquals(20201220, $resultSave->updateResult($updateData, 20201220, null, null));
    }

    public function testUpdateDirectNoIdUrlAndRescoring()
    {
        $identities = ["uniq","fn","ln","exactName","pic"];
        $updateData = $this->matchstatus;
        $updateData["identities"] = $identities;
        
        $this->dbSource->shouldReceive("updateResult")->andReturn(1);
        $this->dbSource->shouldReceive("getResult")->andReturn(["content"=>"https://www.facebook.com/rob.douglas.111", "id"=>20201220]);

        $this->scoring->shouldReceive("rescore")->andReturn($this->scoreingData);

        $resultSave = Mockery::mock(ResultSave::class, [$this->dbSource,
            $this->scoring,
            $this->rejected])->makePartial()->shouldAllowMockingProtectedMethods();

        $resultSave->shouldReceive("runAfterUpdate")->andReturn(null);

        $this->assertEquals(20201220, $resultSave->updateResult($updateData, null, "https://www.facebook.com/rob.douglas.111", null));
    }

    public function testUpdateDirectNoIdNoUrlEmptyResutData()
    {
        $this->dbSource->shouldReceive("updateResult")->andReturn(1);
        $this->dbSource->shouldReceive("getResult")->andReturn(["content"=>"https://www.facebook.com/rob.douglas.111", "id"=>20201220]);

        $resultSave = Mockery::mock(ResultSave::class, [$this->dbSource,
            $this->scoring,
            $this->rejected])->makePartial()->shouldAllowMockingProtectedMethods();
        $resultSave->shouldReceive("runAfterUpdate")->andReturn(null);
        $resultData = new ResultData("");

        $this->assertEquals(20201220, $resultSave->updateResult(["is_delete" => 1], 20201220, null, $resultData));
    }

    public function testUpdateDirectNoIdUrlEmptyResutData()
    {
        $this->dbSource->shouldReceive("updateResult")->andReturn(1);
        $this->dbSource->shouldReceive("getResult")->andReturn(["content"=>"https://www.facebook.com/rob.douglas.111", "id"=>20201220]);

        $resultSave = Mockery::mock(ResultSave::class, [$this->dbSource,
            $this->scoring,
            $this->rejected])->makePartial()->shouldAllowMockingProtectedMethods();

        $resultSave->shouldReceive("runAfterUpdate")->andReturn(null);

        $resultData = new ResultData("");
        $this->assertEquals(20201220, $resultSave->updateResult(["is_delete" => 1], null, "https://www.facebook.com/rob.douglas.111", $resultData));
    }

    public function testUpdateDirectWithId()
    {
        $this->dbSource->shouldReceive("updateResult")->andReturn(1);
        $this->dbSource->shouldReceive("getResult")->andReturn(["content"=>"https://www.facebook.com/rob.douglas.111", "id"=>20201220]);

        $resultSave = Mockery::mock(ResultSave::class, [$this->dbSource,
            $this->scoring,
            $this->rejected])->makePartial()->shouldAllowMockingProtectedMethods();

        $resultSave->shouldReceive("runAfterUpdate")->andReturn(null);

        $resultData = new ResultData("");
        $this->assertEquals(20201220, $resultSave->updateResult(["is_delete" => 1], 20201220, null, null));
    }

    public function testGetResultDataNotFound()
    {
        $url = "https://www.facebook.com/rob.douglas.111";
        $key = "Reportdata_5994770fd6b91be28b627ad6c44c6eb0b69ed";

        // Cache::shouldReceive('get')->once()->with($key)->andReturn('value');
        // Cache::shouldReceive('delete')->once()->with($key)->andReturn(true);
        // Cache::shouldReceive('put')->once()->with($key)->andReturn(null);
        Cache::put($key, "value", 5);
        $this->dbSource->shouldReceive("getResult")->andReturn(["content"=>"https://www.facebook.com/rob.douglas.111", "id"=>20201220]);

        $resultSave = Mockery::mock(ResultSave::class, [$this->dbSource,
            $this->scoring,
            $this->rejected])->makePartial()->shouldAllowMockingProtectedMethods();

        $output = $resultSave->getResultData($url);

        $this->assertEquals(20201220, $output->id);
        Cache::delete($key);
    }

    public function testGetResultDataEmptyId()
    {
        $url = "https://www.facebook.com/rob.douglas.111";
        $key = "Reportdata_5994770fd6b91be28b627ad6c44c6eb0b69ed";
        $resultData = new ResultData($url);
        Cache::put($key, serialize($resultData), 5);

        $this->dbSource->shouldReceive("getResult")->andReturn(["content"=>"https://www.facebook.com/rob.douglas.111", "id"=>20201220]);

        $resultSave = Mockery::mock(ResultSave::class, [$this->dbSource,
            $this->scoring,
            $this->rejected])->makePartial()->shouldAllowMockingProtectedMethods();

        $output = $resultSave->getResultData($url);

        $this->assertEquals(20201220, $output->id);
        Cache::delete($key);
    }

    public function testGetUserIdNotInDB()
    {
        $uniqeContent = "http://www.facebook.com/rob.douglas.111";
        $this->dbSource->shouldReceive("getResult")->andReturn([]);

        $resultSave = Mockery::mock(ResultSave::class, [$this->dbSource,
            $this->scoring,
            $this->rejected])->makePartial()->shouldAllowMockingProtectedMethods();

        $output = $resultSave->getResultId($uniqeContent);
        $this->assertEquals(0, $output);
    }

    public function testGetResultUrlNotInDB()
    {
        $this->dbSource->shouldReceive("getResult")->andReturn([]);

        $resultSave = Mockery::mock(ResultSave::class, [$this->dbSource,
            $this->scoring,
            $this->rejected])->makePartial()->shouldAllowMockingProtectedMethods();

        $output = $resultSave->getResultUrl(20201220);
        $this->assertEquals(null, $output);
    }

    public function testAfterInsertUpdateEvents()
    {
        \Event::fake();

        $resultSave = Mockery::mock(ResultSave::class, [$this->dbSource,
            $this->scoring,
            $this->rejected])->makePartial()->shouldAllowMockingProtectedMethods();

        $output = $resultSave->runAfterInsert($this->resultData);
        $output = $resultSave->runAfterUpdate($this->resultData);

        $this->assertTrue(true);
    }

    public function testgetResultDataFromCache()
    {
        $this->dbSource->shouldReceive("getResult")->andReturn(["content"=>"https://facebook.com/kim.douglas.370", "id"=>20201220]);
        $resultSave = Mockery::mock(ResultSave::class, [$this->dbSource,
            $this->scoring,
            $this->rejected])->makePartial()->shouldAllowMockingProtectedMethods();
        
        $url = "https://www.facebook.com/kim.douglas.370";
        $resultData = new ResultData($url);

        \Cache::put("Reportdata_546c4beeea4d380ac1cde002cbf36e207", serialize($resultData), 60);
        
        $output = $resultSave->getResultData($url);
        $this->assertEquals($url, $output->url);
    }

    public function testgetResultDataEmptyCache()
    {
        $this->dbSource->shouldReceive("getResult")->andReturn(["content"=>"https://facebook.com/kim.douglas.370", "id"=>20201220]);
        $resultSave = Mockery::mock(ResultSave::class, [$this->dbSource,
            $this->scoring,
            $this->rejected])->makePartial()->shouldAllowMockingProtectedMethods();
        
        \Cache::forget("Reportdata_546c4beeea4d380ac1cde002cbf36e207");
        $url = "https://www.facebook.com/kim.douglas.370";
        $output = $resultSave->getResultData($url);
        $this->assertEquals($url, $output->url);
    }
}
