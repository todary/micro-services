<?php

/**
 * The identity bit mask flags for calculating the flags in easy way .
 * 
 * @author Ahmed Samir <ahmedsamir732@gmail.com>
 */

namespace Skopenow\Scoring\Identity ;

use App\Libraries\ScoringFlags;

class IdentityFlags 
{
	
	protected  $scoringFlags ;
	public function __construct(array $scoringFlags) 
	{
		$this->scoringFlags = $scoringFlags ;
	}
	
	public function getFlags(array $identities)
	{
		$flags = 0 ;
		foreach ($this->scoringFlags as $scoringFlag){
			if(in_array($scoringFlag['identity'] , $identities , true)){
				$flags = $flags|$scoringFlag['value'] ;
			}
		}
		return $flags ;
	}

	public function getAllFlags(array $identities): array
	{
		$scoringFlags = new ScoringFlags();
		$data = $scoringFlags->getFlagsFromIdentities($identities);

		$data['flags'] = 0;

		foreach ($this->scoringFlags as $scoringFlag){
			if(in_array($scoringFlag['identity'] , $identities , true)){
				$data['flags'] = $data['flags'] | $scoringFlag['value'] ;
			}
		}
		return $data ;
	}
}

