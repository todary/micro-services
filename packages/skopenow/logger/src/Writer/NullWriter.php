<?php

/** 
 * NullWriter will be used when we want nothing to be logged.
 * @author Ahmed Samir <ahmedsamir732@gmail.com>
 */

namespace Skopenow\Logger\Writer ;

use Skopenow\Logger\Writer\WriterInterface;
use Skopenow\Logger\Writer\AbstractWriter ;
use Skopenow\Logger\DataModel ;



class NullWriter extends AbstractWriter implements WriterInterface
{
	
	/**
	 * an instance of the monolog logger class .
	 * @var object 
	 */
	protected $logger ;
	
	/**
	 * the main message of the log .
	 * @var string 
	 */
	protected $message ;

	/**
	 * override the main constructor of the abstract class because here we do not want
	 * any configuration .
	 */
	public function __construct() 
	{
		
	}
	
	/**
	 * The entry point for every writer which handle the request and initialize the 
	 * dependencies and write the log .
	 * @param DataModel $dataModel
	 * @return what happened in the write function.
	 */
	public function handle(DataModel $dataModel) 
	{
		$this->init();
		return $this->write($dataModel);
	}
	
	/**
	 * initialize the dependencies of the class .
	 */
	protected function init() 
	{
		
	}
	
	/**
	 * The place which handle the write of the log .
	 * @param DataModel $dataModel
	 * @return bool
	 */
	protected function write(DataModel $dataModel) : bool
	{
		$status = false ;
		if(!empty($dataModel)){
			// nothing will be written here because this class was designed to do nothing .
			$status = true ;
		}
		return $status ;
	}
	
	
	
}

