<?php
/**
 * interface for all operators.
 *
 * @author  Ahmed Samir <ahmed.samir@queentechsolution.net>
 * 
 */
namespace Skopenow\Result\Siblings\Operators;

use App\Models\ResultData;
use App\Models\SubResultDataInterface;

interface OperatorsInterface
{

	public function __construct(ResultData $result);

	public function getDefaultSiblings(): \Iterator;

	public function save(SubResultDataInterface $result): bool;

	public function saveBulk(\Iterator $results): bool;

}