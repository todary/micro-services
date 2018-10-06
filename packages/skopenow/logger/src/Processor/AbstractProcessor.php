<?php

/** 
 * The Abstract class for the processors .
 * @author Ahmed Samir <ahmedsamir732@gmail.com>
 */

namespace Skopenow\Logger\Processor ;

use Skopenow\Logger\DataModel ;

abstract class AbstractProcessor
{
	/**
	 *
	 * @var object of class dataModel
	 */
	public $dataModel ;
	
	/**
	 * the entry point function for every processor .
	 */
	abstract function process(array $state, array $data) : bool ;
	
	/**
	 * validating the state array sent by the logging client .
	 * the state array is like the environment parameters for each logging message .
	 */
	abstract protected function validateStateData(array $state);
	
	/**
	 * validating the data to be logged .
	 */
	abstract protected function validateData(array $data);
	
	
	/**
	 * The log type of the processor .
	 * @param int $type
	 */
	public function __construct(int $type) 
	{
		$this->type = $type ;
		$this->dataModel = new DataModel() ;
	}
	
	/**
	 * setting the data into the data model .
	 * @param string $type
	 * @param multiple types $value
	 * @return bool
	 */
	public function setDataModelAttribute($type , $value) : bool
	{
		$status = false ;
		if($this->dataModel instanceof DataModel){
			$this->dataModel->setAttribute($type , $value);
			$status = true ;
		}
		return $status ;
	}
	
	/**
	 * return the data model after being filled by the processor .
	 * @return DataModel
	 */
	public function getModelData () : DataModel 
	{
		return $this->dataModel ;
	}
	
	/**
	 * prepare the data before being set to the data model .
	 * @param array $data
	 * @return \ArrayIterator
	 */
	public function prepareData(array $data) : \ArrayIterator
	{
		$dataArray = array();
		foreach ($data as $singleKey => $singleValue){
			$type = gettype($singleValue);
			$singleAttribute = array(
				"name"	=> $singleKey ,
				"type"	=> $type ,
				"value"	=> $singleValue ,
			);
			$dataArray[] = $singleAttribute ;
		}
		$data = new \ArrayIterator($dataArray) ;
		return $data ;
		
	}
	
	/**
	 * set the data attribute into the data model .
	 * @param type $data
	 * @return boolean
	 */
	public function setData($data) 
	{
		$status = false ;
		if($this->dataModel instanceof DataModel){
			$this->dataModel->setAttribute("data" , $data);
			$status = true ;
		}
		return $status ;
	}
	
	/**
	 * return the log type .
	 * @return int
	 */
	public function getLogType()
	{
		return $this->type ;
	}
}

