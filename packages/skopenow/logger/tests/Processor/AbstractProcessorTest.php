<?php

/** 
 * write test cases for the abstract processor class .
 * 
 * @author Ahmed Samir <ahmedsamir732@gmail.com>
 */

use PHPUnit\Framework\TestCase ;
use Skopenow\Logger\DataModel;

class AbstractProcessorTest extends TestCase
{
	public function testSetDataModelAttribute () : void
	{
		$dataModel = $this->createMock(DataModel::class);
		$dataModel->method("getAttribute")->will($this->returnArgument(0))->willReturn(array("data" => "data"));
		$expected = $dataModel->getAttribute("state");
		
		$abstractProcessor = $this->getMockForAbstractClass("Skopenow\Logger\Processor\AbstractProcessor" , array("type" => 40));
		$setAttribute = $abstractProcessor->setDataModelAttribute("state", array("data" => "data"));
		$this->assertEquals(true , $setAttribute );
		
		$abstractDataModel = $abstractProcessor->getModelData();
		$actual = $abstractDataModel->getAttribute("state");
		
		$this->assertEquals($expected , $actual);
	}
	
	public function testPrepareData()
	{
		$data = array(
			array(
				"name"	=> "report_id" ,
				"type"	=> "integer" ,
				"value"	=>  123,
			)
		);
		$expected = new \ArrayIterator($data) ;
		
		$abstractProcessor = $this->getMockForAbstractClass("Skopenow\Logger\Processor\AbstractProcessor" , array("type" => 40));
		$actual = $abstractProcessor->prepareData(array("report_id" => 123));
		$this->assertEquals($expected , $actual) ;
		
	}
	
	public function testsetData()
	{
		$dataModel = $this->createMock(DataModel::class);
		$dataModel->method("getAttribute")->will($this->returnArgument(0))->willReturn(array("data" => "data"));
		$expected = $dataModel->getAttribute("data");
		
		$abstractProcessor = $this->getMockForAbstractClass("Skopenow\Logger\Processor\AbstractProcessor" , array("type" => 40));
		$setAttribute = $abstractProcessor->setData( array("data" => "data"));
		$this->assertEquals(true , $setAttribute );
		
		$abstractDataModel = $abstractProcessor->getModelData();
		$actual = $abstractDataModel->getAttribute("data");
		
		$this->assertEquals($expected , $actual);
	}
}

