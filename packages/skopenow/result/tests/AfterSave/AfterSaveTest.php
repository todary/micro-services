<?php

/** 
  Test cases for the after save process .
  
  @author Ahmed Samir <ahmedsamir732@gmail.com>
 */

use Skopenow\Result\AfterSave\AfterSave ;
use App\Models\Result;
use App\Models\Report;
use Illuminate\Support\Facades\Artisan ;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class AfterSaveTest extends \TestCase
{

    use DatabaseMigrations;

    public $result ;
    
    public $dataPoints = array() ;
    
    public $relationships = array() ;

    public $dataPointService ;

    public $relationshipService ;

    public $scoringService ;

    public $matchingService ;
    
    public function setup()
    {

        $r = Artisan::call('migrate:refresh', ['--path' => 'packages/skopenow/result/src/database/migrations']);
        config(["state.report_id"=>12555]);

        $this->result = factory(Result::class, 30)->create();
        
        require __DIR__ . '/../data/dbData.php';
        \DB::table("persons")->insert($persons);

        $this->dataPoints =  array(
            "workExperiences" => array(
                [
                    "main_value" =>  "skopenow" ,
                ],
            ),
            "schools" => [
                [
                    "main_value" =>  "vanderbelt" , 
                ],  
            ],
            "username" => array(
                "main_value" =>  "romado" ,
            )
        );

        $this->dataPointService = $this->getMockBuilder(stdClass::class)->setMethods(array("getDataPoints"))->getMock();
        $this->dataPointService //->expects($this->exactly(2))
                        ->method("getDataPoints")
                        ->with($this->logicalOr(
                                $this->equalTo("work_experiences"),
                                $this->equalTo('schools') ,
                                $this->equalTo('username') 
                            ))
                        ->will( $this->returnCallback(array($this, 'dataPointServiceCallback')) );


        $this->relationshipService = $this->getMockBuilder(stdClass::class)->setMethods(array("setRelationship"))->getMock();

        $this->scoringService = $this->getMockBuilder(stdClass::class)->setMethods(array("rescore"))->getMock();
        $this->scoringService->method("rescore")->willReturn([
            "identities" => ["fn","ln","cm"], "flags" => 1254885 ,  "finalScore" => 2.5
        ]) ;

        $this->matchingService = $this->getMockBuilder(stdClass::class)->setMethods(array("match"))->getMock();

    }
    
    public function testinit()
    {
        // $relationshipService->method("setRelationship")->willReturn(true);
        
        $afterSave = new AfterSave($this->result[0] , $this->dataPointService , $this->relationshipService , $this->scoringService , $this->matchingService) ;
        $output = $afterSave->process($this->dataPoints , []);

        ## matched results .
        $matchedResults = array([
              "result_id" => 2,
              "reason" => 4,
              "scoreFlag" => "cm",
            ], [
              "result_id" => 2,
              "reason" => 8,
              "scoreFlag" => "sc",
            ],[
              "result_id" => 8,
              "reason" => 16,
              "scoreFlag" => "un",
            ]);
        $this->assertEquals($matchedResults , $output['matchedResults']);
        ## non-matched results 
        $nonMatchedResults = array([
              "result_id" => 3,
              "reason" => 4
            ],[
              "result_id" => 3,
              "reason" => 8
            ]); 
        $this->assertEquals($nonMatchedResults , $output['nonMatchedResults']);

        ## set relationship
        $this->assertEquals(true , $output['setRelationship']);

        // $r = Artisan::call('migrate:rollback', ['--path' => 'packages/skopenow/result/src/database/migrations']);
    }


    public function testFirstLevelVerified()
    {
        $this->result[0]['flags'] = 262149 ;
        $afterSave = new AfterSave($this->result[0] , $this->dataPointService , $this->relationshipService , $this->scoringService , $this->matchingService) ;
        $output = $afterSave->process($this->dataPoints , []);

        ## matched results .
        $matchedResults = array([
              "result_id" => 2,
              "reason" => 4,
              "scoreFlag" => "cm",
            ], [
              "result_id" => 2,
              "reason" => 8,
              "scoreFlag" => "sc",
            ],[
              "result_id" => 8,
              "reason" => 16,
              "scoreFlag" => "un",
            ]);
        $this->assertEquals($matchedResults , $output['matchedResults']);
        ## non-matched results 
        $nonMatchedResults = array([
              "result_id" => 3,
              "reason" => 4
            ],[
              "result_id" => 3,
              "reason" => 8
            ]); 
        $this->assertEquals($nonMatchedResults , $output['nonMatchedResults']);

        ## set relationship
        $this->assertEquals(true , $output['setRelationship']);

        // $r = Artisan::call('migrate:rollback', ['--path' => 'packages/skopenow/result/src/database/migrations']);
    }

    public function testNonVerifiedResult()
    {
        $this->result[0]['flags'] = 5 ;
        $afterSave = new AfterSave($this->result[0] , $this->dataPointService , $this->relationshipService , $this->scoringService , $this->matchingService) ;
        $output = $afterSave->process($this->dataPoints , []);

        ## matched results .
        $matchedResults = array([
              "result_id" => 2,
              "reason" => 4,
              "scoreFlag" => "cm",
            ], [
              "result_id" => 2,
              "reason" => 8,
              "scoreFlag" => "sc",
            ],[
              "result_id" => 8,
              "reason" => 16,
              "scoreFlag" => "un",
            ]);
        $this->assertEquals($matchedResults , $output['matchedResults']);
        ## non-matched results 
        $nonMatchedResults = array([
              "result_id" => 3,
              "reason" => 4
            ],[
              "result_id" => 3,
              "reason" => 8
            ]); 
        $this->assertEquals($nonMatchedResults , $output['nonMatchedResults']);

        ## set relationship
        $this->assertEquals(true , $output['setRelationship']);

        $r = Artisan::call('migrate:rollback', ['--path' => 'packages/skopenow/result/src/database/migrations']);
    }   

    public function dataPointServiceCallback($type)
    {
        if($type == "work_experiences"){
            return [
                    ["main_value" => "skopenow" , "res" => 2] ,
                    ["main_value" => "queen tech solutions" , "res" => 3] ,
                ] ;
        }elseif($type == "schools"){
            return [
                    ["main_value" => "vanderbelt" , "res" => 2] ,
                    ["main_value" => "oyster bay high school" , "res" => 3] ,
                ];
        }elseif($type == "username"){
            return [
                    ["main_value" => "robdouglas01" , "res" => 6] ,
                    ["main_value" => "romado" , "res" => 8] ,
                ];
        }
    }

}
