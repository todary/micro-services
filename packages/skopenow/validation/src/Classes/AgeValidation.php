<?php
/**
 * Age validation code
 *
 * PHP version 7.0
 *
 * @category Micro_Services-phase_1
 * @package  Validation
 * @author   Mahmoud AboElnouR <aboelnoor@queentecksolutions.com>
 * @license  QueenTechSolutions, Inc.
 * @link     http://www.queentecksolutions.com
 **/
namespace Skopenow\Validation\Classes;

/**
 * Age validation class
 *
 * @category Micro_Services-phase_1
 * @package  Validation
 * @author   Mahmoud AboElnouR <aboelnoor@queentecksolutions.com>
 * @license  QueenTechSolutions, Inc.
 * @link     http://www.queentecksolutions.com
 **/
class AgeValidation extends Validation
{
    /**
     * Check if age is valid
     *
     * @param int $age integer age to validate
     *
     * @return bool
     */
    protected function isValid(&$age): bool
    {
        $age = intval($age);
        if ($age >= 1 && $age <= 120) {
            return true;
        }

        $this->error = 355;
        return false;
    }
}
