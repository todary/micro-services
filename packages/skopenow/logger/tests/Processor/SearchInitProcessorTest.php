<?php

/** 
 * write test cases for the Search init processor class .
 * 
 * @author Ahmed Samir <ahmedsamir732@gmail.com>
 */

namespace Skopenow\Logger\Processor ;

use PHPUnit\Framework\TestCase ;
use Skopenow\Logger\DataModel;

class SearchInitProcessorTest extends TestCase
{
	
	public function testSuccessProcess()
	{
		$state = array("report_id" => 123);
		$data = array(
			"names" => array(["name" => "rob douglas" , "unique_name" => 1 ]),
			"locations" => array(["location" => "Oyster bay , ny" , "big_city" => 0]),
		); 
		$requestProcess = new SearchInitProcessor(10);
		$expected = $requestProcess->process($state, $data);

		$this->assertTrue( $expected);
	}
	
	public function testFailedProcess()
	{
		$state = array();
		$data = array(); 
		$requestProcess = new SearchInitProcessor(10);
		$expected = $requestProcess->process($state, $data);

		$this->assertFalse( $expected);
	}
}
