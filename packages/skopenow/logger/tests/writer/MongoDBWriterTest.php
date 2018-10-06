<?php

/** 
 * write test cases for the mongodb writer test class .
 * 
 * @author Ahmed Samir <ahmedsamir732@gmail.com>
 */

use Skopenow\Logger\DataModel ;
use PHPUnit\Framework\TestCase ;

class MongoDBWriterTest extends TestCase
{
	
	
	public function testHandle()
	{
		$mongo = $this->createMock(\MongoDB\Client::class);
		$collection = $this->getMockBuilder("stdClass")->setMethods(array("save"))->getMock();
		$collection->method('save')->willReturn(true);

        $mongo->expects($this->once())
            ->method('selectCollection')
            ->with('Logger', 'logs')
            ->will($this->returnValue($collection));

		$writer = new Skopenow\Logger\Writer\MongoDBWriter($mongo);
		
		$dataModel = new DataModel();
		$state = new \ArrayIterator(array("report_id" => 123));
		$data = new \ArrayIterator(array(["name" => "request" , "type" => "object" , "value" => new \stdClass()]));
		$dataModel->setAttribute("state", $state);
		$dataModel->setAttribute("data", $data);
		
		$expected = $writer->handle($dataModel);
		
		$this->assertTrue($expected);
	}
	
	public function testFailedMongoConnection()
	{
		try {
			$message = "success";
			
//			$mongo = $this->createMock(\MongoDB\Client::class);
//			$collection = $this->getMockBuilder("stdClass")->setMethods(array("save"))->getMock();
//			$collection->method('save')->willReturn(true);
//
//			$mongo->expects($this->once())
//				->method('selectCollection')
//				->with('Logger', 'request')
//				->will($this->returnValue($collection));
			$mongo = $this->createMock("stdClass");

			$writer = new Skopenow\Logger\Writer\MongoDBWriter($mongo);

			$dataModel = new DataModel();
			$state = new \ArrayIterator(array("report_id" => 123));
			$data = new \ArrayIterator(array(["name" => "request" , "type" => "object" , "value" => new \stdClass()]));
			$dataModel->setAttribute("state", $state);
			$dataModel->setAttribute("data", $data);

			$expected = $writer->handle($dataModel);

			$this->assertTrue($expected);
		} catch (\Exception $ex) {
			$message = $ex->getMessage(); 
		}
		
		$this->assertEquals($message, "There an exception in mongodb connection !");
	}
	
}