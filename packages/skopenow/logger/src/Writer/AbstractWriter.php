<?php

/** 
 * Abstract writer used to init the initial data for evey writer .
 * 
 * @author Ahmed Samir <ahmedsamir732@gmail.com>
 */

namespace Skopenow\Logger\Writer ;

use Skopenow\Logger\DataModel ;

abstract class AbstractWriter 
{
	
	/**
	 * instance of the connection should be used to to write the log .
	 * @param type $connection
	 */
	public function __construct($connection) 
	{
		$this->connection = $connection ;
	}

	/**
	 * The main write function which handle the write functionlaity of the write .
	 * @param DataModel $dataModel 
	 * @return bool if the write done successfully or not .
	 */
	abstract protected function write(DataModel $dataModel) : bool ;

	/**
	 * initialize the main dependencies for the writer .
	 */
	abstract protected function init () ;
	
	/**
	 * get the main record of the data model class .
	 * @param \ArrayIterator $dataIterator
	 * @return array
	 */
	public function getRecord(\ArrayIterator $dataIterator) : array 
	{
		$record = array() ;
		while($dataIterator->valid()){
			$current = $dataIterator->current();
			if($current['name'] == "message"){
				$this->message = $current['value'] ;
				$dataIterator->next();
			}
			$record[$current['name']] = $current['value'] ;
			$dataIterator->next();
		}
		return $record ;
	}
}

