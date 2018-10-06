<?php

/** 
 * write test cases search logger class .
 * 
 * @author Ahmed Samir <ahmedsamir732@gmail.com>
 */

use Skopenow\Logger\Writer\WriterInterface;
use Skopenow\Logger\Writer\NullWriter ;
use monolog\Logger as MonologLogger;
use PHPUnit\Framework\TestCase ;

class SearchLoggerTest extends TestCase
{
	
	public function testConstructor()
	{
		$writers = [];
		$searchLogger = new Skopenow\Logger\EntryPoint(40, $writers);
		
		$this->assertEquals(40 , $searchLogger->getType()) ;
		$this->assertEquals([] , $searchLogger->getWriters()) ;
		
	}
	
	public function testFailedConstructor()
	{
		try {
			$writers = [];
			$searchLogger = new Skopenow\Logger\EntryPoint(400021, $writers);
			$message = "";
		} catch (\Exception $ex) {
			$message = $ex->getMessage();
		}
		
		
		$this->assertEquals($message , "You must declare a valid log type!") ;
	}
	
	public function testPushWriter()
	{
		$writers = [];
		$searchLogger = new Skopenow\Logger\EntryPoint(40, $writers);
		
		$writer = $this->createMock(Skopenow\Logger\Writer\MongoDBWriter::class) ;
		$searchLogger->pushWriter($writer);
		
		$writers = $searchLogger->getWriters() ;
		$this->assertEquals($writer , array_pop($writers));
	}
	
	public function testAddLog()
	{
		$writers = [$this->createMock(Skopenow\Logger\Writer\MongoDBWriter::class)];
		$searchLogger = new Skopenow\Logger\EntryPoint(40, $writers);
		$state = array("report_id" => 123) ;
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
		$expected = $searchLogger->addLog($state , $data);
		
		$this->assertTrue($expected['status']);
	}
	
	public function testNullWriteraddLog()
	{
		$writers = [];
		$searchLogger = new Skopenow\Logger\EntryPoint(40, $writers);
		$state = array("report_id" => 123) ;
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
		$expected = $searchLogger->addLog($state , $data);
		
		$this->assertTrue($expected['status']);
	}
	
	public function testFailedAddLog()
	{
		$writers = [$this->createMock(Skopenow\Logger\Writer\MongoDBWriter::class)];
		$searchLogger = new Skopenow\Logger\EntryPoint(40, $writers);
		$state = array("dffff" => 123) ;
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
		$expected = $searchLogger->addLog($state , $data);
		
		$this->assertFalse($expected['status']);
	}
	
	public function testNoTypeAddLog()
	{
		$writers = [$this->createMock(Skopenow\Logger\Writer\MongoDBWriter::class)];
		$searchLogger = new Skopenow\Logger\EntryPoint(100, $writers);
		$state = array("report_id" => 123) ;
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
		$expected = $searchLogger->addLog($state , $data);
		
		$this->assertFalse($expected['status']);
	}
}