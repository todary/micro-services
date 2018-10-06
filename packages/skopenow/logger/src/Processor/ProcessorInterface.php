<?php

/** 
 * The processor interface for all processors .
 * @author Ahmed Samir <ahmedsamir732@gmail.com>
 */

namespace Skopenow\Logger\Processor ;

use Skopenow\Logger\DataModel ;

interface ProcessorInterface
{
	/**
	 * the processor constructor and what should be set before the processor work .
	 * @param int $type
	 */
	public function __construct(int $type) ;
	
	/**
	 * checks if the data provided to the processor is valid or not .
	 * @return bool
	 */
	public function isValid():bool ;
	
	/**
	 * create the record and set the data into the data model .
	 * @param array $state
	 * @param array $data
	 */
	public function createRecord (array $state , array $data) ;
	
	/**
	 * return the validation notes if there is any .
	 * @return array 
	 */
	public function getValidationNotes() : array;
	
	/**
	 * return the data model .
	 * @return DataModel the data model filled by the processor .
	 */
	public function getModelData() : DataModel ;
	
}