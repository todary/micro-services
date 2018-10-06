<?php

namespace Skopenow\Cleanup\ShowInvisible;

use Skopenow\Cleanup\FiltrationModel ;

/**
 *
 * @author ahmedsamir
 */
interface ShowInvisibleInterface {
	
	
	public function __construct(FiltrationModel $filtrationModel) ;
	
	public function process() :array ;
}
