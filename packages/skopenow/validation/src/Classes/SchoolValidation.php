<?php
/**
 * School validation code
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
 * School validation class
 *
 * @category Micro_Services-phase_1
 * @package  Validation
 * @author   Mahmoud AboElnouR <aboelnoor@queentecksolutions.com>
 * @license  QueenTechSolutions, Inc.
 * @link     http://www.queentecksolutions.com
 **/
class SchoolValidation extends Validation
{
    /**
     * Check if school is valid
     *
     * @param string $school school to validate
     *
     * @return bool
     */
    protected function isValid(&$school): bool
    {
        if (trim($school)) {
            return true;
        }

        $this->error = 'Input must not be only spaces';
        return false;
    }
}
