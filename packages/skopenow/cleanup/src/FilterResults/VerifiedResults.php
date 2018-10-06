<?php

namespace Skopenow\Cleanup\FilterResults;

/**
 * return the verified results .
 *
 * @author Ahmed Samir <ahmedsamir732@gmail.com>
 */

use Skopenow\Cleanup\FilterResults\FilterResultsInterface ;
use Skopenow\Cleanup\FilterResults\FilterResultsAbstract ;


class VerifiedResults extends FilterResultsAbstract implements FilterResultsInterface
{
	
	const ExtractRelatedResults = true ;

	const FilterResultsFromSameSource 	= true ;
	
	public function process() : array
	{
		$verifiedResults = $this->resultService->getVerifiedResults($this->results);
		$verifiedResults = $this->cleanRelativeProfiles($verifiedResults);
		$this->matchedResults = $verifiedResults ;
		return $this->matchedResults ;
	}
}
