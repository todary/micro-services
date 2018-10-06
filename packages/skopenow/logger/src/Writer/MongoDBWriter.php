<?php

namespace Skopenow\Logger\Writer ;

use Skopenow\Logger\Writer\WriterInterface;
use Skopenow\Logger\Writer\AbstractWriter ;
use Skopenow\Logger\DataModel ;
use Monolog\Logger ;
use Monolog\Handler\MongoDBHandler ;

/**
 * MongoDBWritter a writer class for writing logs into mongodb database .
 * @author  Ahmed Samir <ahmedsamir732@gmail.com>
 *
 */


class MongoDBWriter extends AbstractWriter implements WriterInterface
{
	
	/**
	 * an instance of the monolog logger class .
	 * @var object 
	 */
	protected $logger ;
	
	/**
	 * The main message of the log .
	 * @var string 
	 */
	protected $message ;

	/**
	 * the main connection 
	 * @var object  
	 */
	protected $connection ;

	protected $database ;
	
	protected $collection ;


	/**
	 * The entry point for every writer which handle the request and initialize the 
	 * dependencies and write the log .
	 * @param DataModel $dataModel
	 * @return type
	 */
	public function handle(DataModel $dataModel) 
	{
		$this->database = $dataModel->getAttribute("folder");
		$this->collection = $dataModel->getAttribute("document") ;
		$this->init() ;
		
		return $this->write($dataModel);
	}
	
	/**
	 * initialize the dependencies of the class .
	 */
	protected function init() 
	{
		$this->logger = new Logger("", [] , []);
		
		if(! $this->connection instanceof \MongoDB\Client){
			throw new \Exception("There an exception in mongodb connection !");
		}
		
		$handler = new MongoDBHandler($this->connection, $this->database, $this->collection);
		$this->logger->pushHandler($handler);
	}
	
	/**
	 * The place which handle the write of the log .
	 * @param DataModel $dataModel
	 * @return bool
	 */
	protected function write(DataModel $dataModel) : bool
	{
		$dataIterator = $dataModel->getData() ;
		$stateIterator = $dataModel->getState() ;
		$context = array() ;
		$context['data'] = $this->getRecord($dataIterator);
		$context['state'] = $this->getRecord($stateIterator);

		if(!empty($this->logger)){
			$status = $this->logger->addRecord(Logger::INFO, $this->message, $context);
			return $status ;
		}
		return false;
	}
	
	/**
	 * initialize 
	 * @return $this
	 * @throws \Exception
	 */
//	protected function instantiateMongoDBHandler () 
//	{
//		//$mongo = new \MongoDB\Client('mongodb://localhost:27017');
//		if(! $this->connection instanceof \MongoDB\Client){
//			throw new \Exception("There an exception in mongodb connection !");
//		}
//		
//		$handler = new MongoDBHandler($this->connection, "Logger", "request");
//		$this->logger->pushHandler($handler);
//		
//		return $this ;
//	}
}