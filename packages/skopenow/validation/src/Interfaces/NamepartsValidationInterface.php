<?php
/**
 * Name Validation interface
 *
 * PHP version 7.0
 *
 * @category Micro_Services-phase_1
 * @package  Validation
 * @author   Mahmoud AboElnouR <aboelnoor@queentecksolutions.com>
 * @license  QueenTechSolutions, Inc.
 * @link     http://www.queentecksolutions.com
 **/
namespace Skopenow\Validation\Interfaces;

/**
 * Name Validation interface
 *
 * @category Micro_Services-phase_1
 * @package  Validation
 * @author   Mahmoud AboElnouR <aboelnoor@queentecksolutions.com>
 * @license  QueenTechSolutions, Inc.
 * @link     http://www.queentecksolutions.com
 **/
interface NamepartsValidationInterface extends ValidationInterface
{
    /**
     * Check if part matches the part pattern validation
     *
     * @param string $part part to match pattern
     *
     * @return bool
     */
    public function partPatternMatch(string $part): bool;
}
