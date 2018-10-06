<?php

namespace Skopenow\Result\PurifyResults;

use Skopenow\Result\PurifyResults\AbstractPurifyResults;

class PurifyResults extends AbstractPurifyResults
{

	protected $priority = 0;

	public function purify(\Iterator $results): \Iterator
	{
		$results = $this->setPriorities($results);
		$results = $this->getPurifiedResults($results, $this->priority);

		return $results;
	}

	

	protected function getResultPriority($result): int
	{
		$this->rules->rewind();
		$returnValue = 0;
		while ($this->rules->valid()) {
			$rule = $this->rules->key();
			$priority = $this->rules->current();
			$flags = $result->flags;

			if(($flags&$rule) == $rule) {
				if($priority != 0 && $priority<$this->priority) $this->priority = $priority;
				elseif($this->priority == 0)	$this->priority = $priority;
				$returnValue = $priority;
				break;
			}

			$this->rules->next();
		}
		return $returnValue;
	}



}