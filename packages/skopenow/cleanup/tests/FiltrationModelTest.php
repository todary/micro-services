<?php

/**
 * Description of FiltrationModelTest
 *
 * @author ahmedsamir
 */
use Skopenow\Cleanup\FiltrationModel;

class FiltrationModelTest extends TestCase
{
	
	public function testSetAttribute ()
	{
		$data = array("data" => "data");
		$dataModel = new FiltrationModel();
		$dataModel->setAttribute("allResults", $data);
		$this->assertEquals($data , $dataModel->getAttribute("allResults"));
		
	}
	
	public function testFailedSetAttribute()
	{
		try {
			$data = array("data" => "data");
			$dataModel = new FiltrationModel();
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
			$dataModel = new FiltrationModel();
			$dataModel->setAttribute("allResults", $data);
			$message = $dataModel->getAttribute("dtta");
		} catch (\Exception $ex) {
			$message = $ex->getMessage();
		}
		
		$this->assertEquals($message , "No DataModel attribute called dtta ");
	}
	
	
}
