<?php

namespace Skopenow\Logger\Writer ;

use Skopenow\Logger\DataModel ;

interface WriterInterface
{			
	
	/**
	 * The entry point for every writer which handle the request and initialize the 
	 * dependencies and write the log .
	 * @param DataModel $dataModel
	 */
	public function handle(DataModel $dataModel) ;
}