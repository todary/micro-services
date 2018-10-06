<?php

namespace Skopenow\Result\PurifyResults;

use Skopenow\Result\PurifyResults\AbstractPurifyResults;

class PurifyPendingResults extends AbstractPurifyResults
{

	protected $priority = 0;

	public function purify(\Iterator $results): \Iterator
	{
		$results = $this->setPriorities($results);
		$results = $this->getPurifiedResults($results, $this->priority);

		return $results;
	}

	

	protected function getResultPriority(array $result): int
	{
		while ($this->rules->valid()) {
			$rule = $this->rules->key();
			$priority = $this->rules->current();
			$resultData = @unserialize($result['resultData']);
			$flags = $this->getFlags($resultData->getMatchStatus());
			$resultData->setFlags($flags);

			if($flags&$rule == $rule) {
				if($priority != 0 && $priority<$this->priority) $this->priority = $priority;
				elseif($this->priority == 0)	$this->priority = $priority;
				return $priority;
			}

			$this->rules->next();
		}
		return 0;
	}

	protected function getFlags(array $matchingData)
	{
		$scoringService = loadService('scoring');
		$flags = $scoringService->getFlags($matchingData);

		return $flags;
	} 
}