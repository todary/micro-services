<?php

/** 
 * write test cases data model class .
 * 
 * @author Ahmed Samir <ahmedsamir732@gmail.com>
 */

use PHPUnit\Framework\TestCase ;
use Skopenow\Logger\DataModel;

class DataModelTest extends TestCase 
{
	
	public function testSetAttribute ()
	{
		$data = array("data" => "data");
		$dataModel = new DataModel();
		$dataModel->setAttribute("data", $data);
		$this->assertEquals($data , $dataModel->getAttribute("data"));
		
	}
	
	public function testFailedSetAttribute()
	{
		try {
			$data = array("data" => "data");
			$dataModel = new DataModel();
			$dataModel->setAttribute("dtta", $data);
			$message =  "";
		} catch (\Exception $ex) {
			$message = $ex->getMessage();
		}
		
		$this->assertEquals($message , "No DataModel attribute called dtta ");
	}
	
	public function testFailedGetAttribute()
	{
		try {
			$data = array("data" => "data");
			$dataModel = new DataModel();
			$dataModel->setAttribute("data", $data);
			$message = $dataModel->getAttribute("dtta");
		} catch (\Exception $ex) {
			$message = $ex->getMessage();
		}
		
		$this->assertEquals($message , "No DataModel attribute called dtta ");
	}
	
	public function testGetData()
	{
		$data = new \ArrayIterator(array("data" => "data")) ;
		$dataModel = new DataModel();
		$dataModel->setAttribute("data", $data);
		$this->assertEquals($data , $dataModel->getData());
	}
	
	public function testGetState()
	{
		$data = new \ArrayIterator(array("data" => "data")) ;
		$dataModel = new DataModel();
		$dataModel->setAttribute("state", $data);
		$this->assertEquals($data , $dataModel->getState());
	}
}