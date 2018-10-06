<?php

/**
 * abstract class for purifing results by rules.
 * 
 * @author Ahmed Samir <ahmedsamir732@gmail.com>
 */
namespace Skopenow\Result\PurifyResults;

use Skopenow\Result\PurifyResults\PurifyResultsInterface;

abstract class AbstractPurifyResults implements PurifyResultsInterface
{

	protected $rules;

	protected $priority = 0;

	protected $returnZeroPriority = false;

	public function __construct(\Iterator $rules, bool $returnZeroPriority = false)
	{
		$this->rules = $rules;
		$this->returnZeroPriority = $returnZeroPriority;
	}

	public function setRules(\Iterator $rules)
	{
		$this->rules = $rules;
	}

	abstract function purify(\Iterator $results): \Iterator;


	protected function getPurifiedResults(\Iterator $results, int $priority)
	{
		$purifiedResults = new \ArrayIterator();

		if (!$priority && !$this->returnZeroPriority) {
			return $purifiedResults;
		}

		while ($results->valid()) {
			$result = $results->current();
			if ($result['priority'] == $priority) {
				$purifiedResults->append($result);
			}
			$results->next();
		}
		return $purifiedResults;
	}

	protected function setPriorities(\Iterator $results)
	{
		$sortedResults = new \ArrayIterator();
		while ($results->valid()) {
			$result = $results->current();
			$result['priority'] = $this->getResultPriority($result);
			$sortedResults->append($result);
			$results->next();
		}

		return $sortedResults;
	}

	abstract protected function getResultPriority($result): int;

}