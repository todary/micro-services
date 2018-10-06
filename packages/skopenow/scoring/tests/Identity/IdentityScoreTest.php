<?php

/** 
 * Tests for score identity class .
 * 
 * @author Ahmed Samir <ahmedsamir732@gmail.com>
 */


class IdentityScoreTest extends TestCase 
{
	
//	public $score_identity = array(
//		array('type' => 'adr', 'title' => 'Address', 'score' => '0.25'),
//		array('type' => 'age', 'title' => 'Age', 'score' => '0.25'),
//		array('type' => 'cm', 'title' => 'Company', 'score' => '0.3'),
//		array('type' => 'dob', 'title' => 'Date of Birth', 'score' => '0.3'),
//		array('type' => 'em', 'title' => 'Email', 'score' => '1'),
//		array('type' => 'exct', 'title' => 'Exact City', 'score' => '0.6'),
//		array('type' => 'exct-bg', 'title' => 'Exact Big City', 'score' => '0.3'),
//		array('type' => 'exct-sm', 'title' => 'Exact Sm City', 'score' => '0.6'),
//		array('type' => 'fn', 'title' => 'First Name', 'score' => '0.25'),
//		array('type' => 'fzn', 'title' => 'Fuzzy Name', 'score' => '0.2'),
//		array('type' => 'ln', 'title' => 'Last Name', 'score' => '0.25'),
//		array('type' => 'mn', 'title' => 'Middle Name', 'score' => '0.25'),
//		array('type' => 'onlyOne', 'title' => 'OnlyOne profile', 'score' => '0.5'),
//		array('type' => 'pct', 'title' => 'Partial City', 'score' => '0.25'),
//		array('type' => 'ph', 'title' => 'Phone', 'score' => '0.75'),
//		array('type' => 'pic', 'title' => 'Profile Picture', 'score' => '0.1'),
//		array('type' => 'rltv', 'title' => 'Relative Found', 'score' => '0.5'),
//		array('type' => 'sc', 'title' => 'School', 'score' => '0.3'),
//		array('type' => 'st', 'title' => 'State', 'score' => '0.25'),
//		array('type' => 'un', 'title' => 'Username', 'score' => '0.5')
//	);

	public function testInit()
	{
		$entryPoint = new Skopenow\Scoring\EntryPoint();
		$matchingData = array(
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
		);
		
		$identityScore = new Skopenow\Scoring\Identity\IdentityScore(config("scoreIdentities"));
		$output = $identityScore->init($matchingData);
		
		$this->assertEquals($output['score'], 1.35);
		
		$exectedIdentities = ["fn","ln","exct-sm","st"];
		$this->assertEquals($output['identities'] , $exectedIdentities);
		
		$expectedIdentifiers = ["Rob Douglas" , "Oyster Bay , NY"];
		$this->assertEquals($output['identifiers'] , $expectedIdentifiers);
	}
	
	public function testException()
	{
		$entryPoint = new Skopenow\Scoring\EntryPoint();
		$matchingData = array(
			"name"	=> array(
				"status"	=> true ,
				"identites"	=> array(
					"fn"	=> true ,
					"mn	"	=> false ,
					"ln"	=> true ,
					"otherName"	=> false ,
				),
				"matchWith"	=> "Rob Douglas" ,
			) ,
			"location"	=> array(
				"status" => true ,
				"identity"	=> array(
					"exct-sm"	=> true ,
					"exct-bg"	=> true ,
					"st"	=> true ,
					"pct"	=> true ,
				),
				"matchWith"	=> "Oyster Bay , NY",
			),
		);
		
		$identityScore = new Skopenow\Scoring\Identity\IdentityScore(config("scoreIdentities"));
		$message = "";
		try{
			$output = $identityScore->init($matchingData);
		} catch (\Exception $ex) {
			$message = $ex->getMessage();
		}
		
		$this->assertEquals("Undefined index: identities" , $message);
		
	}
	
	
}

