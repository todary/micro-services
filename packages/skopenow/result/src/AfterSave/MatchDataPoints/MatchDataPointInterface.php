<?php

/** 
 * Interface for the matching data points classes .
 * @author Ahmed Samir <ahmedsamir732@gmail.com>
 */
namespace Skopenow\Result\AfterSave\MatchDataPoints ;

use App\Models\ResultData as Result ;

interface MatchDataPointInterface 
{
	
	public function __construct(Result $result);
	
	public function match(\Iterator $dataPoints, array $resultsDataPoints) ;

	// public function matchOne(string $firstEntity, string $secondEntity);
	
	
			
}

