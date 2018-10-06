<?php

/**
 * Check verified results , checks if the results are verified or primary profiles ,
 * in order to use them and their data points to match other results or verify them .
 * 
 * @author Ahmed Samir <ahmedsamir732@gmail.com>
 */
namespace Skopenow\Result\Verify;

use Skopenow\Result\Verify\CheckFlags;
use App\Libraries\ScoringFlags;
use App\Models\ResultData;

class CheckVerifiedResults 
{

	use CheckFlags ;

	/**
	 * [$scoringFlags description]
	 * @var array
	 */
	protected $scoringFlags = array();

	protected $verifiedFlags ;
	
	public function __construct() 
	{
		$this->scoringFlags = loadData("scoringFlags") ;
		$this->verifiedFlags = new VerifiedFlags();
	}
	
	public function check(int $flags)
	{
		$data = $this->formateReturnData(false , null) ;
		switch (true) {
			case $this->checkFlags($flags , $this->verifiedFlags->getFirstLevelFlags()):
				$data = $this->formateReturnData(true , "first") ;
				break;
			
			case $this->checkFlags($flags , $this->verifiedFlags->getSecondFlags()) :
				$data = $this->formateReturnData(true , "second") ;
				break;

			default:
				$data = $this->formateReturnData(false , null) ;
				break;
		}

		return $data ;
	}

	public function checkIdentities(ResultData $result)
	{
		$data = $this->formateReturnData(false , null) ;
		$matchingFlags = $result->getMatchingFlags();
		$inputFlags = $result->getInputFlags();
		$extraFlags = $result->getExtraFlags();
		switch (true) {
			case $this->matchWithFlags($this->verifiedFlags->getFirstLevelIdentities(), $matchingFlags, $inputFlags, $extraFlags):
				$data = $this->formateReturnData(true , "first") ;
				break;
			
			case $this->matchWithFlags($this->verifiedFlags->getSecondLevelIdentities(), $matchingFlags, $inputFlags, $extraFlags) :
				$data = $this->formateReturnData(true , "second") ;
				break;

			default:
				$data = $this->formateReturnData(false , null) ;
				break;
		}

		return $data ;
	}

	public function checkWithFlags(int $mainFlags ,array $checkedFlags)
	{
		return $this->checkFlags($mainFlags, $checkedFlags);
	}

	protected function formateReturnData($status , $level)
	{
		$data = array("status" => $status , "level" => $level) ;
		return $data ;
	}

	public function matchWithFlags(array $identities, int $matchWithFlags, int $inputFlags, int $extraFlags)
	{
		$scoringFlags = new ScoringFlags();		
		$status = $scoringFlags->checkBulk($identities, $matchWithFlags, $inputFlags, $extraFlags);
			
		return $status ;
	}
	
	public function matchResultsWithFlags($results , $checkFlags)
	{
		$matchedResults = array() ;
		$scoringFlags = new ScoringFlags();
		
		foreach ($results as $result)
		{
			// $status = $this->checkFlags($result['flags'] , $checkFlags);
			$status = $scoringFlags->checkBulkByResult($checkFlags, $result);
			if($status){
				$matchedResults[] = $result ;
			}
		}
		return $matchedResults ;
	}
}

