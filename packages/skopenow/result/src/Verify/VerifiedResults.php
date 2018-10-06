<?php

namespace Skopenow\Result\Verify;

/**
 * returns the verified results depends on flags .
 *
 * @author Ahmed Samir <ahmedsamir732@gmail.com>
 */

use App\Models\Result ;

class VerifiedResults {
	
	use CheckFlags ;
	
	protected $results ;
	
	protected $verifiedFlags ;

	protected $checkVerifiedResults ;

	public function __construct($results) 
	{
		$this->results = $results ;
		$this->verifiedFlags = new VerifiedFlags();
		$this->checkVerifiedResults = new CheckVerifiedResults();
	}
	
	public function getNeverDeletedResults()
	{
		$checkFlags = $this->verifiedFlags->getNeverDeletedIdentities() ;
		$neverDeletedResults = $this->checkVerifiedResults->matchResultsWithFlags($this->results, $checkFlags);
		
		return $neverDeletedResults ;
	}

	public function getVerifiedResults()
	{
		$checkFlags = $this->verifiedFlags->getVerifiedIdentities() ;
		$verifiedResults = $this->checkVerifiedResults->matchResultsWithFlags($this->results , $checkFlags);
		return $verifiedResults ;
	}

	public function getOnlyOneRelatives()
	{
		## get the onlyOneRelative Flag in array .
		$checkFlags = $this->verifiedFlags->getOnlyOneRelativeIdentities() ;
		$onlyOneRelatives = $this->checkVerifiedResults->matchResultsWithFlags($this->results , $checkFlags);
		return $onlyOneRelatives ;
	}
}
