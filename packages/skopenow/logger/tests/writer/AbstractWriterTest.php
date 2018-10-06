<?php

/** 
 * write test cases for the null writer test class .
 * 
 * @author Ahmed Samir <ahmedsamir732@gmail.com>
 */

use Skopenow\Logger\DataModel ;
use PHPUnit\Framework\TestCase ;

class AbstractWriterTest extends TestCase
{
	
	public function testGetRecord()
	{
		$writer = new Skopenow\Logger\Writer\NullWriter();
		$data[] = array(
			"name" => "report_id",
			"type" => "integer",
			"value" => 123
		);
		$data[] = array(
			"name" => "message",
			"type" => "string",
			"value" => "message"
		);
		$dataIterator = new \ArrayIterator($data);
		$expected = $writer->getRecord($dataIterator);
		$data = array("report_id" => 123 , "message" => "message");
		$this->assertEquals($data , $expected);
	}
}