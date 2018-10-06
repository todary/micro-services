<?php

namespace Skopenow\Cleanup\FilterResults;

/**
 * Filter Results abstract class will include the common functionality between different filtering classes 
 *
 * @author ahmedsamir
 */

use Skopenow\Result\EntryPoint as ResultService ;


abstract class FilterResultsAbstract 
{
	
	protected $results ;
	
	protected $resultService ;
	
	protected $matchedResults = array() ;
	
	protected $nonMatchedResults = array() ;
	
	public function __construct($results , ResultService $resultService) 
	{
		$this-> results = $results ;
		$this->resultService = $resultService ;
	}
	
	public function getMatchedResults()
	{
		return $this->matchedResults ;
	}
	
	public function getNonMatchedResults()
	{
		return $this->nonMatchedResults ;
	}

	public function getAllResults()
	{
		return $this->results ;
	}

	public function cleanRelativeProfiles(array $results)
	{
		$CleanedResults = [];
		foreach ($results as $result) {
			if (!$result['is_relative']) {
				$CleanedResults[] = $result;
			}
		}
		return $CleanedResults;
	}
}
