<?php

/**
 * Entry point for the per result process .
 * @author Ahmed Samir <ahmedsamir732@gmail.com>
 */

use Illuminate\Support\Facades\Artisan ;
use Skopenow\Result\EntryPoint ;
use App\Models\Result ;
use Illuminate\Support\Facades\Event;
use App\Events\AfterSaveEvent ;
use App\Models\ResultData;
use Skopenow\Result\DataSourceBridge;
use Skopenow\Result\DataSourceLuman;
use Skopenow\Result\Save\RejectedResult;
use Skopenow\Scoring\EntryPoint as Scoring;
use App\DataTypes\Name;
use App\DataTypes\Address;

class EntryPointTest extends TestCase
{
    protected $resultSave;
    protected $dbSource;
    protected $scoring;
    protected $rejected;
    protected $matchStatus = [
        "name" => [
            "status" => false,
            "identities" => [
                "fn" => false,
                "mn" => false,
                "ln" => false,
                "input_name" => false,
                "unq_name" => false,
                "fzn" => false,
            ],
            "matchWith" => "Rob Douglas"
        ],
        "location" => [
            "status" => false,
            "identities" => [
                "exct-sm" => false,
                "exct-bg" => false,
                "input_loc" => false,
                "pct" => false,
                "st" => false,
            ],
            "distance" => 0,
            "matchWith" => "",
        ],
        "email" => [
            "status" => false,
            "identities" => [
                "em" => false,
                "input_em" => false,
            ],
            "matchWith" => "",
        ],
        "phone" => [
            "status" => false,
            "identities" => [
                "ph" => false,
                "input_ph" => false,
            ],
            "matchWith" => "",
        ],
        "work" => [
            "status" => false,
            "identities" => [
                "cm" => false,
                "input_cm" => false,
            ],
            "matchWith" => "",
        ],
        "school" => [
            "status" => false,
            "identities" => [
                "sc" => false,
                "input_sc" => false,
            ],
            "matchWith" => "",
        ],
        "age" => [
            "status" => false,
            "identities" => [
                "age" => false,
            ],
            "matchWith" => "",
        ],
        "username" => [
            "status" => false,
            "identities" => [
                "un" => false,
                "input_un" => false,
                "verified_un" => false,
            ],
            "matchWith" => "romado12187"
        ]
      ];
    
    public function setup()
    {
        $r = Artisan::call('migrate:refresh', ['--path' => 'packages/skopenow/result/src/database/migrations']);
        config(["state.report_id"=>1]);
        config(["state.combination_id"=>1]);
        $this->dbSource = new DataSourceLuman();
        $this->rejected = \Mockery::mock(RejectedResult::class);
        

        require __DIR__ . '/data/dbData.php';
       

        \Cache::forget('score_type');
        \Cache::forget('score_identity');
        \Cache::forget('score_single_result');
        \Cache::forget('score_results_count');
        \Cache::forget('score_sources');
        \Cache::forget('quantcastList');
        \Cache::forget('score_single_result');
        \Cache::forget('BannedDomains');

        \DB::table("score_identity")->insert($score_identity);
        \DB::table("score_results_count")->insert($score_results_count);
        \DB::table("main_source")->insert($main_source);
        \DB::table("score_single_result")->insert($score_single_result);
        \DB::table("score_sources")->insert($score_sources);
        \DB::table("score_type")->insert($score_type);
        \DB::table("persons")->insert($persons);
        \DB::table("source")->insert($source);
        \DB::table("banned_domains")->insert($banned_domains);
        
        $this->scoring = loadService("scoring");
        $this->resultSave = loadService("result");
    }

    public function testSaveResult()
    {
        config(["state.report_id"=>5]);
        config(["state.combination_id"=>6]);

        $url = "https://www.facebook.com/rob.douglas.7923";
        $resultData = new ResultData($url);
        $resultData->addName(Name::create(["full_name"=>"Rob Douglas"], $resultData->mainSource));
        $resultData->addLocation(Address::create(["full_address"=>"Oyster Bay, NY"], $resultData->mainSource));
        $resultData->username = "rob.douglas.7923";
        $matchStatus["name"] = [
            "status" => true,
            "identities" => [
                "fn" => true,
                "mn" => true,
                "ln" => true,
                "input_name" => false,
                "unq_name" => false,
                "fzn" => false,
            ],
            "matchWith" => "Rob Douglas"
        ];
        
        $resultData->setMatchStatus($matchStatus);
        $resultData->resultsCount = 2;
        $resultData->setIsProfile(true);
        
        $saveObj = loadService("result");

        $output = $saveObj->save($resultData);
        dd($output);

        //get result data
        /*$result = Result::find($output[$url]["resultId"]);
        $accual = [
            "status" =>  $output[$url]["action"],
            "identifiers" => $result->identifiers,
            "score_identity" => $result->score_identity,
            "score_source" => $result->score_source,
            "score_source_type" => $result->score_source_type,
            "score_result_count" => $result->score_result_count,
            "score" => $result->score,
            "flags" => $result->flags
        ];

        $expected = [
            "status" =>  "save",
            "identifiers" => '["Rob Douglas"]',
            "score_identity" => '["fn","mn","ln"]',
            "score_source" => 1.0,
            "score_source_type" => 1.0,
            "score_result_count" => 0.84799690655495,
            "score" => 0.86959938131099,
            "flags" => 7
        ];
        $this->assertEquals($expected, $accual);
        $url = "https://www.facebook.com/rob.douglas.7923";
        $resultSave = loadService("result");
        $resultData = new ResultData($url);
        $resultData->addName(Name::create(["full_name"=>"Rob Douglas"], $resultData->mainSource));
        $resultData->addLocation(Address::create(["full_address"=>"Oyster Bay, NY"], $resultData->mainSource));
        $resultData->username = "rob.douglas.7923";
        $matchStatus["location"] = [
            "status" => true,
            "identities" => [
                "exct-sm" => true,
                "exct-bg" => false,
                "input_loc" => false,
                "pct" => false,
                "st" => true,
            ],
            "distance" => 0,
            "matchWith" => "",
        ];
        
        $resultData->setMatchStatus($matchStatus);
        $resultData->resultsCount = 2;
        $resultData->setIsProfile(true);
        
        $output = $this->resultSave->save($resultData);

        //get result data
        $result = Result::find($output[$url]["resultId"]);

        $accual = [
            "status" =>  $output[$url]["action"],
            "identifiers" => $result->identifiers,
            "score_identity" => $result->score_identity,
            "score_source" => $result->score_source,
            "score_source_type" => $result->score_source_type,
            "score_result_count" => $result->score_result_count,
            "score" => $result->score,
            "flags" => $result->flags
        ];

        $expected = [
            "status" =>  "update",
            "identifiers" => '["Rob Douglas"]',
            "score_identity" => '["fn","mn","ln","exct-sm","st"]',
            "score_source" => 1.0,
            "score_source_type" => 1.0,
            "score_result_count" => 0.84799690655495,
            "score" => 1.209599381311,
            "flags" => 151
        ];
        $this->assertEquals($expected, $accual);*/
    }
/*
    public function testUpdate()
    {
        $data = [
            "spidered" => 0
        ];

        $output = $this->resultSave->update($data, null, "https://www.facebook.com/rob.douglas.7923");
        $this->assertEquals(1, $output);
    }

    public function testUpdateIdentities()
    {
        $url = "https://www.facebook.com/rob.douglas.7923";
        $resultData = new ResultData($url);
        $resultData->addName(Name::create(["full_name"=>"Rob Douglas"], $resultData->mainSource));
        $resultData->addLocation(Address::create(["full_address"=>"Oyster Bay, NY"], $resultData->mainSource));
        $resultData->username = "rob.douglas.7923";
        $matchStatus["name"] = [
            "status" => true,
            "identities" => [
                "fn" => true,
                "mn" => true,
                "ln" => true,
                "input_name" => false,
                "unq_name" => false,
                "fzn" => false,
            ],
            "matchWith" => "Rob Douglas"
        ];
        
        $resultData->setMatchStatus($matchStatus);
        $resultData->resultsCount = 2;
        $resultData->setIsProfile(true);
        $this->resultSave->save($resultData);

        $identities = ["fn","mn","ln","exct-sm","st","em","input_em"];
        $output = $this->resultSave->updateIdentities([$resultData], $identities);
        $this->assertEquals(['1'=>1], $output);
    }

    public function testDeleteResult()
    {
        $url = "https://www.facebook.com/rob.douglas.7923";
        $resultData = new ResultData($url);
        $resultData->addName(Name::create(["full_name"=>"Rob Douglas"], $resultData->mainSource));
        $resultData->addLocation(Address::create(["full_address"=>"Oyster Bay, NY"], $resultData->mainSource));
        $resultData->username = "rob.douglas.7923";
        $matchStatus["name"] = [
            "status" => true,
            "identities" => [
                "fn" => true,
                "mn" => true,
                "ln" => true,
                "input_name" => false,
                "unq_name" => false,
                "fzn" => false,
            ],
            "matchWith" => "Rob Douglas"
        ];
        
        $resultData->setMatchStatus($matchStatus);
        $resultData->resultsCount = 2;
        $resultData->setIsProfile(true);
        $this->resultSave->save($resultData);

        $output = $this->resultSave->delete([1], 1);
        $this->assertEquals(1, $output);
    }

    public function testSaveRejected()
    {
        $url = "https://www.facebook.com/rob.douglas.7923";
        $resultData = new ResultData($url);
        $resultData->addName(Name::create(["full_name"=>"Rob Douglas"], $resultData->mainSource));
        $resultData->addLocation(Address::create(["full_address"=>"Oyster Bay, NY"], $resultData->mainSource));
        $resultData->username = "rob.douglas.7923";
        $matchStatus["name"] = [
            "status" => true,
            "identities" => [
                "fn" => true,
                "mn" => true,
                "ln" => true,
                "input_name" => false,
                "unq_name" => false,
                "fzn" => false,
            ],
            "matchWith" => "Rob Douglas"
        ];
        
        $resultData->setMatchStatus($matchStatus);
        $resultData->resultsCount = 2;
        $resultData->setIsProfile(true);

        $output = $this->resultSave->saveRejected($resultData, 1);
        $this->assertTrue($output);
    }

    
    /*public function testAfterSave()
    {
        $r = Artisan::call('migrate:refresh', ['--path' => 'packages/skopenow/result/src/database/migrations']);
        $result = factory(Result::class)->make();

        Event::fake();
        $entryPoint = new EntryPoint();
        $output = $entryPoint->afterSave($result) ;

        Event::assertDispatched(AfterSaveEvent::class , function ($e) use($result){
            return $e->getResult() === $result ;
        });
    }*/
}
