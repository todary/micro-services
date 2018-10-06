<?php

namespace Skopenow\Cleanup\Helpers ;


trait CommonHelpers
{
	
	public function uniqueResults(array $results)
	{
		$uniqueResults = array() ;
		foreach ($results as $key=> $result){
			if(!in_array($result, $uniqueResults)){
				$uniqueResults[] = $result ;
			}
		}
		return $uniqueResults ;
	}
	
	
	

	public function getResultsByIds($allResults , array $resultsIds)
	{
		$returnedResults = array() ;
		foreach ($allResults as $result) {
			if(in_array($result['id'], $resultsIds)){
				$returnedResults[] = $result ;
			}
		}
		return $returnedResults ;
	}
	
}

