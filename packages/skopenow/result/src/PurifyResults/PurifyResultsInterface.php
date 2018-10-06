<?php

/**
 * interface of purifing Results by rules.
 * 
 * @author Ahmed Samir <ahmedsamir732@gmail.com>
 */
namespace Skopenow\Result\PurifyResults;

interface PurifyResultsInterface 
{

	public function __construct(\Iterator $rules);

	public function setRules(\Iterator $rules);

	public function purify(\Iterator $results): \Iterator;
}