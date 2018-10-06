<?php

/** 
 * one of the search log type is the combination creation process .
 * Data[
 *	
 * ]
 * State[
 *	report_id : the search report id .
 *	combination_id : the combination id .
 *	combination_level_id : the combination level id .
 *	environment . 
 *	user_id .
 *	role_id .
 * ]
 *  
 * @author Ahmed Samir <ahmedsamir732@gmail.com>
 */

namespace Skopenow\Logger\Processor ;

use Skopenow\Logger\Processor\ProcessorInterface ;
use Skopenow\Logger\Processor\AbstractProcessor ;
use Skopenow\Logger\DataModel ;

class CombinationCreateProcessor extends AbstractProcessor implements ProcessorInterface
{
	/**
	 *
	 * @var type The log type .
	 */
	protected $type ;
	
	/**
	 *
	 * @var type The validation notes .
	 */
	protected $validation_notes = array();
	
	/**
	 * the main functionality of the processor done here .
	 * @param array $state
	 * @param array $data
	 * @return bool
	 */
	public function process(array $state, array $data) :bool
	{
		$this->validateStateData($state);
		$this->validateData($data);

		$isValid = $this->isValid();
		if(!$isValid){
			return false ;
		}
		
		$this->createRecord($state , $data);
		return true ;
	}
	
	/**
	 * checks if the data provided to the processor is valid or not .
	 * @return bool 
	 */
	public function isValid() : bool
	{
		$status = true ;
		if(!empty($this->getValidationNotes())){
			$status = false;
		}
		return $status;
	}
	
	/**
	 * validate the state data .
	 * @param array $state
	 * @return $this
	 */
	protected function validateStateData(array $state)
	{
		if(empty($state['report_id']) && empty($state['combination_id']) && empty($state['result_id']) && empty($state['user_id']) && empty($state['role_id']) ){
			$this->validation_notes['required'] = "You must provide at least one state variable (report_id , combination_id , result_id , user_id , role_id).";
		}
		return $this ;
	}
	
	/**
	 * validate the main data of the log .
	 * @param array $data
	 * @return $this
	 */
	protected function validateData(array $data) 
	{
		if(empty($data['request_link'])){
			$this->validation_notes['request_link'] = "You must provide the request link !" ;
		}
		if(empty($data['request'])){
			$this->validation_notes['request'] = "You must provide the request object !" ;
		}
		if(empty($data['response'])){
			$this->validation_notes['response'] = "You must provide the response object !" ;
		}
		if(empty($data['message'])){
			$this->validation_notes['message'] = "You must provide the message !" ;
		}
		if(empty($data['time_taken'])){
			$this->validation_notes['time_taken'] = "You must provide the time_taken !" ;
		}
		return $this ;
	}


	/**
	 * create the main record of data model .
	 * @param array $state
	 * @param array $data
	 * @return $this
	 */
	public function createRecord(array $state , array $data) 
	{
		$state['log_type'] = $this->getLogType();
		$state = $this->prepareData($state);
		$this->setDataModelAttribute("state" , $state);
		
		$data = $this->prepareData($data);
		$this->setDataModelAttribute("data", $data);
		return $this;
	}
		
	/**
	 * return the validation notes .
	 * @return array
	 */
	public function getValidationNotes() : array
	{
		return $this->validation_notes ;
	}
	
}
