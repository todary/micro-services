<?php

namespace Skopenow\Cleanup;

/**
 * Description of FiltrationModel
 *
 * @author ahmedsamir
 */
class FiltrationModel {
	
	protected $scoringFlags = array() ;

	protected $allResults = array() ;

	protected $relationships = array() ;

	protected $resultService ;
	
	protected $resultsToBeVisible = array() ;
	
	protected $resultsToBeDeleted	= array() ;
	
	protected $filtrationObjects = array() ;

	protected $pendingResults = array(); 
	
	
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
}
