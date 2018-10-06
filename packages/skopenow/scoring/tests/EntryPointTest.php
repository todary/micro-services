<?php

/** 
 * Write test cases for the scoring entry point class .
 * 
 * @author Ahmed Samir <ahmedsamir732@gmail.com>
 */
use Illuminate\Support\Facades\Artisan ;

class EntryPointTest extends TestCase 
{
	public $data = array(
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

	protected $oldScore = array(
		"listCountScore" => 0.25,
		"resultTypeScore"	=>	1,
		"sourceTypeScore"	=>	1,
		"identities"	=>	["fn","ln","exct-sm","st"],
		"identifiers"	=>	["Rob Douglas" , "Oyster Bay , NY"]
	);
	public function testInit()
	{
		// $r = Artisan::call('migrate:refresh', ['--path' => 'packages/skopenow/scoring/database/migrations']);
		$entryPoint = new Skopenow\Scoring\EntryPoint() ;
			
		$output = $entryPoint->init($this->data);
		
		$this->assertEquals(0.25 , $output['listCountScore']);
		$this->assertEquals(1 , $output['resultTypeScore']);
		$this->assertEquals(1 , $output['sourceTypeScore']);
		$this->assertEquals(1.35 , $output['identityScore']);
		$this->assertEquals(.99 , $output['finalScore']);
		
		$exectedIdentities = ["fn","ln","exct-sm","st"];
		$this->assertEquals($output['identities'] , $exectedIdentities);
		
		$expectedIdentifiers = ["Rob Douglas" , "Oyster Bay , NY"];
		$this->assertEquals($output['identifiers'] , $expectedIdentifiers);		
	}

	public function testRescoreWithCache()
	{
		$r = Artisan::call('migrate:refresh', ['--path' => 'packages/skopenow/scoring/database/migrations']);
		config(["state.report_id" => 12548]);
		$entryPoint = new Skopenow\Scoring\EntryPoint;
		$resultUrl = "http://facebook.com/rob.douglas.7923";
		$key = md5(config("state.report_id").$resultUrl);
		\Cache::put($key , $this->oldScore, 120);
		$this->data['matchingData']['email'] = array(
			"status"	=> true,
			"identities"=>	["em" => true],
			"matchWith" => "ahmed.samir@queentechsolution.net",
		);
		$output = $entryPoint->rescore($this->data, $resultUrl, true);

		$this->assertEquals(0.25 , $output['listCountScore']);
		$this->assertEquals(1 , $output['resultTypeScore']);
		$this->assertEquals(1 , $output['sourceTypeScore']);
		$this->assertEquals(2.35 , $output['identityScore']);
		$this->assertEquals(1.39 , $output['finalScore']);
		
		$exectedIdentities = ["fn","ln","exct-sm","st","em"];
		$this->assertEquals($output['identities'] , $exectedIdentities);
		
		$expectedIdentifiers = [
		    "Rob Douglas",
		    "Oyster Bay , NY",
		    "ahmed.samir@queentechsolution.net",
		];
		$this->assertEquals($output['identifiers'] , $expectedIdentifiers);
	}

	public function testRescoreWithoutCache()
	{
		$r = Artisan::call('migrate:refresh', ['--path' => 'packages/skopenow/scoring/database/migrations']);
		$result = factory(App\Models\Result::class)->create();

		config(["state.report_id" => 12555]);
		$entryPoint = new Skopenow\Scoring\EntryPoint;
		$result->unique_content = "http://facebook.com";
		$result->flags = 5;
		$result->save();
		$resultUrl = $result->unique_content;
		$key = md5(config("state.report_id").$resultUrl);
		\Cache::forget($key);
		$this->data['matchingData']['email'] = array(
			"status"	=> true,
			"identities"=>	["em" => true],
			"matchWith" => "ahmed.samir@queentechsolution.net",
		);
		$output = $entryPoint->rescore($this->data, $resultUrl, true);

		$this->assertEquals(0.25 , $output['listCountScore']);
		$this->assertEquals(1 , $output['resultTypeScore']);
		$this->assertEquals(1 , $output['sourceTypeScore']);
		$this->assertEquals(2.35 , $output['identityScore']);
		$this->assertEquals(1.39 , $output['finalScore']);
		
		$exectedIdentities = ["fn","ln","exct-sm","st","em"];
		$this->assertEquals($output['identities'] , $exectedIdentities);
		
		$expectedIdentifiers = [
		    "Rob Douglas",
		    "Oyster Bay , NY",
		    "ahmed.samir@queentechsolution.net",
		];
		$this->assertEquals($output['identifiers'] , $expectedIdentifiers);	
	}
	
	// public function testInitWithoutCache()
	// {
	// 	$r = Artisan::call('migrate:refresh', ['--path' => 'packages/skopenow/scoring/database/migrations']);
	// 	$entryPoint = new Skopenow\Scoring\EntryPoint() ;
	// 	\Cache::forget('#topSites_facebook.com');
	// 	\Cache::forget('score_type');
	// 	\Cache::forget('score_identity');
	// 	\Cache::forget('score_single_result');
	// 	\Cache::forget('score_results_count');
	// 	\Cache::forget('score_sources');
	// 	\Cache::forget('quantcastList');
	// 	\Cache::forget('score_single_result');
		
	// 	$output = $entryPoint->init($this->data);
		
	// 	$this->assertEquals(0.25 , $output['listCountScore']);
	// 	$this->assertEquals(1 , $output['resultTypeScore']);
	// 	$this->assertEquals(1 , $output['sourceTypeScore']);
	// 	$this->assertEquals(1.35 , $output['identityScore']);
		
	// 	$exectedIdentities = ["fn","ln","exct-sm","st"];
	// 	$this->assertEquals($output['identities'] , $exectedIdentities);
		
	// 	$expectedIdentifiers = ["Rob Douglas" , "Oyster Bay , NY"];
	// 	$this->assertEquals($output['identifiers'] , $expectedIdentifiers);	
	// }
	
}
