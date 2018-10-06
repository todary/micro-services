<?php
/**
 * Birthdate validation code
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
 * Birthdate validation class
 *
 * @category Micro_Services-phase_1
 * @package  Validation
 * @author   Mahmoud AboElnouR <aboelnoor@queentecksolutions.com>
 * @license  QueenTechSolutions, Inc.
 * @link     http://www.queentecksolutions.com
 **/
class BirthdateValidation extends Validation
{
    const VALIDATION_PATTERN = '/([\d]+)/s';
    /**
     * Check if date of birth is valid
     *
     * @param string $birthdate date to validate
     *
     * @return bool
     */
    protected function isValid(&$birthdate): bool
    {
        if (preg_match(self::VALIDATION_PATTERN, $birthdate)) {
            $dob = str_replace('--', '00', $birthdate);

            if (@strtotime(trim($dob))) {
                return true;
            }
        }

        $this->error = 'Input must be valid date.';
        return false;
    }
}
