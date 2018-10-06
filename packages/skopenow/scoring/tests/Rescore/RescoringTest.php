<?php

/** 
 * Tests for score identity class .
 * 
 * @author Ahmed Samir <ahmedsamir732@gmail.com>
 */
//namespace Skopenow\Scoring\Rescore ;

use Skopenow\Scoring\EntryPoint ;
//use PHPUnit\Framework\TestCase ;

class RescoringTest extends TestCase 
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
			"company"	=> array(
				"status" => true , 
				"identities" => array(
					"cm"	=> true ,
				),
				"matchWith"	=> "Skopenow",
			),
		),
		"isProfile"	=> true ,
		"isRelative" => false ,
		"mainSource" => "facebook" ,
		"source" => "facebook_by_company",
		"resultsCount" => 1 ,
		"link"	=> "https://www.facebook.com/rob.douglas.7923",
		"type"	=> "result",
	);
	
	public $oldScore = array(
		"listCountScore" => 0.25 ,
		"resultTypeScore" => 0 , 
		"sourceTypeScore" => 0 ,
		"identityScore" => 1,35 ,
		"identities" => ["fn","ln","exct-sm","st"] ,
		"identifiers" => ["Rob Douglas" , "Oyster Bay , NY"] ,
	);
	// public function testRescore()
	// {
	// 	$entryPoint = new EntryPoint() ;
		
	// 	$oldScore = $this->oldScore ;
	// 	$output = $entryPoint->rescore($this->data , $oldScore);
		
	// 	$this->assertEquals(0 , $output['listCountScore']);
	// 	$this->assertEquals(1 , $output['resultTypeScore']);
	// 	$this->assertEquals(1 , $output['sourceTypeScore']);
	// 	$this->assertEquals(1.65 , $output['identityScore']);
		
	// 	$exectedIdentities = ["fn","ln","exct-sm","st","cm"];
	// 	$this->assertEquals($output['identities'] , $exectedIdentities);
		
	// 	$expectedIdentifiers = [];
	// 	$this->assertEquals($output['identifiers'] , $expectedIdentifiers);
	// }
	
	// public function testRescoreWithMissingData()
	// {
	// 	$entryPoint = new EntryPoint() ;
	// 	$oldScore = $this->oldScore ;
	// 	unset($oldScore['listCountScore']);
	// 	unset($oldScore['sourceTypeScore']);
	// 	unset($oldScore['resultTypeScore']);
		
	// 	$output = $entryPoint->rescore($this->data , $oldScore);
		
	// 	$this->assertEquals(0 , $output['listCountScore']);
	// 	$this->assertEquals(1 , $output['resultTypeScore']);
	// 	$this->assertEquals(1 , $output['sourceTypeScore']);
	// 	$this->assertEquals(1.65 , $output['identityScore']);
		
	// 	$exectedIdentities = ["fn","ln","exct-sm","st","cm"];
	// 	$this->assertEquals($output['identities'] , $exectedIdentities);
		
	// 	$expectedIdentifiers = [];
	// 	$this->assertEquals($output['identifiers'] , $expectedIdentifiers);
		
	// }
	
	// public function testFailedRescore()
	// {
	// 	try{
	// 		$entryPoint = new EntryPoint() ;
	// 		unset($this->oldScore['identities']);
	// 		$output = $entryPoint->rescore($this->data , $this->oldScore);
	// 		$message = "";
	// 	} catch (\Exception $ex) {
	// 		$message = $ex->getMessage();
	// 	}
		
	// 	$this->assertEquals("Undefined index: identities" , $message);
	// }
	
	
}

