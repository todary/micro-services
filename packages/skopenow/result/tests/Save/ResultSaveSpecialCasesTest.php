<?php
namespace Skopenow\Result\Save;

use App\Models\ResultData;
use Skopenow\Result\DataSourceBridge;
use Skopenow\Result\DataSourceLuman;
use Skopenow\Result\Save\RejectedResult;
use Skopenow\Scoring\EntryPoint as Scoring;
use App\DataTypes\Name;
use App\DataTypes\Address;
use Illuminate\Support\Facades\Artisan;
use App\Models\Result;

class ResultSaveSpecialCasesTest extends \TestCase
{
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
        config(["state.report_id"=>5]);
        config(["state.combination_id"=>6]);
        $this->dbSource = new DataSourceLuman();
        $this->rejected = \Mockery::mock(RejectedResult::class);
        

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
        
        $this->scoring = loadService("scoring");
    }

    public function testSaveFirstTime()
    {
    /******************************Save first time********************************/
        $url = "https://www.facebook.com/rob.douglas.7923";
        $resultSave = new ResultSave($this->dbSource, $this->scoring, $this->rejected);
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
        
        $output = $resultSave->saveResult($resultData);
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
    /******************************Save secound time******************************/
        $url = "https://www.facebook.com/rob.douglas.7923";
        $resultSave = new ResultSave($this->dbSource, $this->scoring, $this->rejected);
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
        
        $output = $resultSave->saveResult($resultData);

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
        $this->assertEquals($expected, $accual);
    /******************************Save third time********************************/
        $url = "https://www.facebook.com/rob.douglas.7923";
        $resultSave = new ResultSave($this->dbSource, $this->scoring, $this->rejected);
        $resultData = new ResultData($url);
        $resultData->addName(Name::create(["full_name"=>"Rob Douglas"], $resultData->mainSource));
        $resultData->addLocation(Address::create(["full_address"=>"Oyster Bay, NY"], $resultData->mainSource));
        $resultData->username = "rob.douglas.7923";

        $matchStatus["email"] = [
            "status" => true,
            "identities" => [
                "em" => true,
                "input_em" => true,
            ],
            "matchWith" => "romado12187",
        ];
        
        $resultData->setMatchStatus($matchStatus);
        $resultData->resultsCount = 2;
        $resultData->setIsProfile(true);
        
        $output = $resultSave->saveResult($resultData);

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
            "identifiers" => '["Rob Douglas","romado12187"]',
            "score_identity" => '["fn","mn","ln","exct-sm","st","em","input_em"]',
            "score_source" => 1.0,
            "score_source_type" => 1.0,
            "score_result_count" => 0.84799690655495,
            "score" => 1.609599381311,
            "flags" => 262551
        ];
        $this->assertEquals($expected, $accual);
    /******************************update*****************************************/
        $url = "https://www.facebook.com/rob.douglas.7923";
        $updateData = array();
        $matchStatus["phone"] = [
            "status" => true,
            "identities" => [
                "ph" => true,
                "input_ph" => true,
            ],
            "matchWith" => "",
        ];
        $updateData["matchingData"] = $matchStatus;
        $updateData["identities"] = ["fn","mn","ln","exct-sm","st","em","input_em","ph","input_ph"];

        $output = $resultSave->updateResult($updateData, null, $url);

        $result = Result::find($output);

        $accual = [
            "result_id" => $output,
            "identifiers" => $result->identifiers,
            "score_identity" => $result->score_identity,
            "score_source" => $result->score_source,
            "score_source_type" => $result->score_source_type,
            "score_result_count" => $result->score_result_count,
            "score" => $result->score,
            "flags" => $result->flags
        ];

        $expected = [
            "result_id" =>  1,
            "identifiers" => '["Rob Douglas","romado12187"]',
            "score_identity" => '["fn","mn","ln","exct-sm","st","em","input_em","ph","input_ph"]',
            "score_source" => 1.0,
            "score_source_type" => 1.0,
            "score_result_count" => 0.84799690655495,
            "score" => 1.809599381311,
            "flags" => 787351
        ];

        $this->assertEquals($expected, $accual);
    /******************************save with relative*****************************/
        /*$url = "https://www.facebook.com/rob.douglas.7923";
        $resultSave = new ResultSave($this->dbSource, $this->scoring, $this->rejected);
        $resultData = new ResultData($url);
        $resultData->addName(Name::create(["full_name"=>"Rob Douglas"], $resultData->mainSource));
        $resultData->addLocation(Address::create(["full_address"=>"Oyster Bay, NY"], $resultData->mainSource));
        $resultData->username = "rob.douglas.7923";
        
        $matchStatus["work"] = [
            "status" => true,
            "identities" => [
                "cm" => true,
                "input_cm" => false,
            ],
            "matchWith" => "",
        ];

        $resultData->setMatchStatus($matchStatus);
        $resultData->resultsCount = 2;
        $resultData->setIsProfile(true);
        $resultData->addLink(["url"=>"https://www.facebook.com/kim.k.douglas","reason"=>2]);
        
        $output = $resultSave->saveResult($resultData);
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
            "identifiers" => '["Rob Douglas","romado12187"]',
            "score_identity" => '["fn","mn","ln","exct-sm","st","em","input_em","ph","input_ph","cm"]',
            "score_source" => 1.0,
            "score_source_type" => 1.0,
            "score_result_count" => 0.84799690655495,
            "score" => 1.929599381311,
            "flags" => 788375
        ];
        $this->assertEquals($expected, $accual);
    /******************************save and it is relative************************/
        /*$url = "https://www.facebook.com/kim.k.douglas";
        $resultSave = new ResultSave($this->dbSource, $this->scoring, $this->rejected);
        $resultData = new ResultData($url);
        $resultData->addName(Name::create(["full_name"=>"Kim Douglas"], $resultData->mainSource));
        $resultData->addLocation(Address::create(["full_address"=>"Oyster Bay, NY"], $resultData->mainSource));
        $resultData->username = "kim.k.douglas";
        $resultData->addLink(["url"=>"https://www.facebook.com/rob.douglas.7923","reason"=>2]);
        
        $matchStatus["name"] = [
            "status" => true,
            "identities" => [
                "fn" => false,
                "mn" => false,
                "ln" => true,
            ],
            "matchWith" => "Kim Douglas",
        ];
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
        $matchStatus["age"] = [
            "status" => true,
            "identities" => [
                "age" => true,
            ],
            "matchWith" => "",
        ];

        $links = $resultData->getLinks();
        foreach ($links as $key => &$linkData) {
            if (!empty($linkData['result'])) {
                $linkData['result'] = $linkData['result']->setMatchStatus($matchStatus);
            }
        }
        
        $resultData->setMatchStatus($matchStatus);
        $resultData->resultsCount = 2;
        $resultData->setIsProfile(true);

        $output = $resultSave->saveResult($resultData);
        //get result data
        $result = Result::find(1);
        $accual = [
            "identifiers" => $result->identifiers,
            "score_identity" => $result->score_identity,
            "score_source" => $result->score_source,
            "score_source_type" => $result->score_source_type,
            "score_result_count" => $result->score_result_count,
            "score" => $result->score,
            "flags" => $result->flags
        ];

        $expected = [
            "identifiers" => '["Rob Douglas","romado12187","Kim Douglas"]',
            "score_identity" => '["fn","mn","ln","exct-sm","st","em","input_em","ph","input_ph","cm","age"]',
            "score_source" => 1.0,
            "score_source_type" => 1.0,
            "score_result_count" => 0.84799690655495,
            "score" => 2.029599381311,
            "flags" => 792471
        ];

        $this->assertEquals($expected, $accual);*/
    }

    public function testWithRelative()
    {
        //Result::whereIn("id", [1, 2])->delete();
        Result::where("id", "<>", null)->delete();
        $url = "https://www.facebook.com/rob.douglas.7923";
        $resultSave = new ResultSave($this->dbSource, $this->scoring, $this->rejected);
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
        $matchStatus["work"] = [
            "status" => true,
            "identities" => [
                "cm" => true,
                "input_cm" => false,
            ],
            "matchWith" => "",
        ];

        $resultData->setMatchStatus($matchStatus);
        $resultData->resultsCount = 2;
        $resultData->setIsProfile(true);
        $resultData->addLink(["url"=>"https://www.facebook.com/kim.k.douglas","reason"=>2]);
        
        $output = $resultSave->saveResult($resultData);
        //get result data
        $result = Result::find($output[$url]["resultId"]);
        
        $accual = [
            "result_id" => $output[$url]["resultId"],
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
            "result_id" => 1,
            "status" =>  "save",
            "identifiers" => '["Rob Douglas"]',
            "score_identity" => '["fn","mn","ln","cm"]',
            "score_source" => 1.0,
            "score_source_type" => 1.0,
            "score_result_count" => 0.84799690655495,
            "score" => 0.98959938131099,
            "flags" => 1031
        ];
        $this->assertEquals($expected, $accual);
    }

    public function testItisRelative()
    {
        Result::where("id", "<>", null)->delete();
        $url = "https://www.facebook.com/kim.k.douglas";
        $resultSave = new ResultSave($this->dbSource, $this->scoring, $this->rejected);
        $resultData = new ResultData($url);
        $resultData->addName(Name::create(["full_name"=>"Kim Douglas"], $resultData->mainSource));
        $resultData->addLocation(Address::create(["full_address"=>"Oyster Bay, NY"], $resultData->mainSource));
        $resultData->username = "kim.k.douglas";
        $resultData->addLink(["url"=>"https://www.facebook.com/rob.douglas.7923","reason"=>2]);
        
        $matchStatus["name"] = [
            "status" => true,
            "identities" => [
                "fn" => true,
                "mn" => true,
                "ln" => true,
            ],
            "matchWith" => "Kim Douglas",
        ];
        $matchStatus["username"] = [
            "status" => true,
            "identities" => [
                "un" => true,
            ],
            "matchWith" => "",
        ];

        $resultData->setMatchStatus($matchStatus);

        $links = $resultData->getLinks();
        foreach ($links as $key => &$linkData) {
            if (!empty($linkData['result'])) {
                $linkData['result'] = $linkData['result']->setMatchStatus($matchStatus);
            }
        }
        
        $resultData->resultsCount = 2;
        $resultData->setIsProfile(true);

        $output = $resultSave->saveResult($resultData);
        //get result data
        $result = Result::find(2);
        $accual = [
            "status" => $output[$url]["action"],
            "identifiers" => $result->identifiers,
            "score_identity" => $result->score_identity,
            "score_source" => $result->score_source,
            "score_source_type" => $result->score_source_type,
            "score_result_count" => $result->score_result_count,
            "score" => $result->score,
            "flags" => $result->flags
        ];

        $expected = [
            "status" => "save",
            "identifiers" => '["Kim Douglas"]',
            "score_identity" => '["fn","mn","ln","un"]',
            "score_source" => 1.0,
            "score_source_type" => 0.0,
            "score_result_count" => 0.0,
            "score" => 0.7,
            "flags" => 32775
        ];

        $this->assertEquals($expected, $accual);
    }
}
