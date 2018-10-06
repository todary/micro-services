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
interface NameValidationInterface extends ValidationInterface
{
    /**
     * Check if name matches the name pattern validation
     *
     * @param string $name name to match pattern
     *
     * @return bool
     */
    public function namePatternMatch(string $name): bool;

    /**
     * Check lastname suffix
     *
     * @param string $name name to check suffix
     *
     * @return bool
     */
    public function lastNameSuffix(string $name): bool;
}
