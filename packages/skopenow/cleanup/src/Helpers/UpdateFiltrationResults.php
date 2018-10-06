<?php

namespace Skopenow\Cleanup\Helpers;

/**
 * Description of UpdateFiltrationResults
 *
 * @author ahmedsamir
 */
class UpdateFiltrationResults {
	
	protected $allResults ;
	
	protected $resultsToBeVisible = array() ;
	
	protected $resultsToBeDeleted = array() ;


	public function __construct($allResults , array $resultsToBeVisible , array $resultsToBeDeleted) 
	{
		$this->allResults = $allResults ;
		$this->resultsToBeVisible = $resultsToBeVisible ;
		$this->resultsToBeDeleted = $this->filterDeletedResults($resultsToBeDeleted, $resultsToBeVisible) ;
	}
	
	public function update()
	{
		foreach ($this->allResults as &$result){
			if($this->IsVisible($result['id'])){
				$result['invisible']  = 0 ;
				$result['is_deleted'] = 0 ;
			}elseif($this->IsDeleted($result['id'])){
				$result['is_deleted'] = 1 ;
				$result['invisible'] = 1 ;
			}
		}
	}
	
	public function filterDeletedResults(array $resultsToBeDeleted , array $resultsToBeVisible) : array
	{
		$results = array() ;
		$toBeVisibleIds = array_column($resultsToBeVisible, "id");
		foreach ($resultsToBeDeleted as $resultToBeDeleted){
			if(!in_array($resultToBeDeleted['id'], $toBeVisibleIds)) {
				$results[] = $resultToBeDeleted ;
			}
		}
		return $results ;
	}
	
	protected function IsVisible($resultId)
	{
		$status = false ;
		foreach($this->resultsToBeVisible as $result){
			if($resultId == $result['id']){
				$status = true ;
				break;
			}
		}
		return $status ;
	}
	
	protected function IsDeleted($resultId)
	{
		$status = false ;
		foreach($this->resultsToBeDeleted as $result){
			if($resultId == $result['id']){
				$status = true ;
				break;
			}
		}
		return $status ;
	}
	
	public function getAllResults()
	{
		return $this->allResults ;
	}
	
	public function getResultsToBeVisible()
	{
		return $this->resultsToBeVisible ;
	}
	
	public function getResultsToBeDeleted()
	{
		return $this->resultsToBeDeleted ;
	}
}
