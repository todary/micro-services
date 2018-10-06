<?php

/** 
 * The model that holds the data will be stored .
 * @author Ahmed Samir <ahmedsamir732@gmail.com>
 */

namespace Skopenow\Logger ;

class DataModel
{
	/**
	 * reportID the search report identifier .
	 * @var int
	 */
	protected $report_id ;
	
	/**
	 * combinationID the search combination identifier .
	 * @var int
	 */
	protected $combination_id ;
	
	/**
	 * combination level id
	 * @var int 
	 */
	protected $combination_level_id ;
	
	/**
	 * resultID the search result identifier .
	 * @var int
	 */
	protected $result_id ;
	
	/**
	 * user id
	 * @var int
	 */
	protected $user_id ;
	
	/**
	 * user role id
	 * @var int
	 */
	protected $role_id ;

	/**
	 * Local or production 
	 * @var string 
	 */
	protected $environment ;

	/**
	 * The environment data .
	 * @var Iterator 
	 */
	protected $state ;
	
	/**
	 * The data to be logged .
	 * @var Iterator
	 */
	protected $data ;
	
	/**
	 * stands as the folder or database which the log document will be stored into .
	 * @var string
	 */
	protected $folder = "Logger" ;
	
	/**
	 * stands as the document , table , collection which the log data will be stored into .
	 * @var string
	 */
	protected $document = "logs" ;
	
	/**
	 * sets the report identifier .
	 * @param int $reportID
	 * @return $this
	 */
	public function setAttribute (string $type , $value) 
	{
		if(!property_exists($this, $type)){
			throw new \Exception("No DataModel attribute called {$type} ");
		}
		$this->$type = $value ;
	}
	
	public function getAttribute (string $type)
	{
		if(!property_exists($this, $type)){
			throw new \Exception("No DataModel attribute called {$type} ");
		}
		return $this->$type ;
	}
	
	/**
	 * return the logged data .
	 * @return \Iterator
	 */
	public function getData(): \Iterator
	{
		return $this->data ;
	}
	
	public function getState() : \Iterator
	{
		return $this->state ;
	}
}


