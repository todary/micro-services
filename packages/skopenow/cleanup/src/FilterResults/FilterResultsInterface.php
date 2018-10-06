<?php

/**
 * an interface for the results filtertion with their flags .
 * 
 * @author Ahmed Samir <ahmedsamir732@gmail.com>
 */

namespace Skopenow\Cleanup\FilterResults;

use App\Models\Result ;
use Skopenow\Result\EntryPoint as ResultService ;

interface FilterResultsInterface 
{
	
	public function __construct( $results , ResultService $resultService);
	
	public function process(): array;
	
	public function getMatchedResults();
	
	public function getNonMatchedResults();
	
}
