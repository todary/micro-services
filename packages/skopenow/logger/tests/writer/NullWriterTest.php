<?php

/** 
 * write test cases for the null writer test class .
 * 
 * @author Ahmed Samir <ahmedsamir732@gmail.com>
 */

use Skopenow\Logger\DataModel ;
use PHPUnit\Framework\TestCase ;

class NullWriterTest extends TestCase
{
		
	public function testSetDataModel()
	{
		$dataModel = $this->createMock(DataModel::class);
		$writer = new Skopenow\Logger\Writer\NullWriter();
		$expected = $writer->handle($dataModel);
		
		$this->assertTrue($expected);
	}
	
	public function testGetRecord()
	{
		$writer = new Skopenow\Logger\Writer\NullWriter();
		$data[] = array(
			"name" => "report_id",
			"type" => "integer",
			"value" => 123
		);
		$dataIterator = new \ArrayIterator($data);
		$expected = $writer->getRecord($dataIterator);
		$data = array("report_id" => 123);
		$this->assertEquals($data , $expected);
	}
}