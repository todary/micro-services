<?php

namespace Skopenow\Cleanup\Helpers;

/**
 * Description of Results
 *
 * @author ahmedsamir
 */
class Results {
	
	protected $results ;
	
	public function __construct($results) 
	{
		$this->results = $results ;
	}
	
	public function getResultsFromSameSource(array $results)
	{
		$resultsIds = array_column($results, "id") ;
		$sources = array_column($results, "source_id") ;
		$resultsFromSameSoure = []; 
		foreach($this->results as $result){
			if(in_array($result['source_id'] , $sources) && !in_array($result['id'] , $resultsIds)){
				$resultsFromSameSoure[] = $result ;
			}
		}
		return $resultsFromSameSoure ;
	}
}
