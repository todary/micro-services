<?php

namespace Skopenow\Cleanup\Helpers;

/**
 * Description of FilterProcessor
 *
 * @author ahmedsamir
 */

use Skopenow\Cleanup\ShowInvisible\ShowInvisibleInterface ;
use Skopenow\Cleanup\Helpers\Relationships ;
use Skopenow\Cleanup\Helpers\CommonHelpers ;

class ShowInvisibleProcessor 
{
	
	use CommonHelpers ;

	protected $ShowInvisibleObj ;
		
		
	public function __construct(ShowInvisibleInterface $ShowInvisibleObj ) 
	{
		$this->ShowInvisibleObj = $ShowInvisibleObj ;
	}
	
	public function process()
	{
		$matchedResults = $this->ShowInvisibleObj->process() ;

		return $matchedResults;
	}
	
	
}
