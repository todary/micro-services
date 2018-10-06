<?php

/**
 * Check flags vs flags as a helper for the check results Flags .
 *
 * @author Ahmed Samir <ahmedsamir732@gmail.com>
 */

namespace Skopenow\Result\Verify;

trait CheckFlags
{
	public function checkFlags(int $mainFlags ,array $checkedFlags)
	{
		$status = false ;
		foreach ($checkedFlags as $flag) {
			if (($mainFlags&$flag) == $flag) {
				$status = true ;
				break ;
			}
		}
		return $status ;
	}
}
