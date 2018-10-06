<?php

/** 
 * write test cases for the request processor class .
 * 
 * @author Ahmed Samir <ahmedsamir732@gmail.com>
 */

use PHPUnit\Framework\TestCase ;
use Skopenow\Logger\DataModel;

class RequestProcessorTest extends TestCase
{
	
	public function testSuccessProcess()
	{
		$state = array("report_id" => 123);
		$data = array(
			"request_link" => "facebook.com/ahmedsamir732",
			"request_options" => array("header" => "Content-Type:application/json;"),
			"proxies" => array() ,
			"trials" => 1 ,
			"max_trials" => 5,
			"trial_delay" => 30 ,
			"trial_reason" => "beacuse there is noting",
			"status" => 1,
			"request" => new \stdClass() ,
			"response" => new \stdClass(),
			"message" => "ddddd",
			"time_taken" => 1.2
		); 
		$requestProcess = new Skopenow\Logger\Processor\RequestProcessor(40);
		$expected = $requestProcess->process($state, $data);

		$this->assertTrue( $expected);
	}
	
	public function testFailedProcess()
	{
		$state = array();
		$data = array(); 
		$requestProcess = new Skopenow\Logger\Processor\RequestProcessor(40);
		$expected = $requestProcess->process($state, $data);

		$this->assertFalse( $expected);
	}
}
