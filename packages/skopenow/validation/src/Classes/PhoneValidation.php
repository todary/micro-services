<?php
/**
 * Phone validation code
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
 * Phone validation class
 *
 * @category Micro_Services-phase_1
 * @package  Validation
 * @author   Mahmoud AboElnouR <aboelnoor@queentecksolutions.com>
 * @license  QueenTechSolutions, Inc.
 * @link     http://www.queentecksolutions.com
 **/
class PhoneValidation extends Validation
{
    /**
     * Check if phone is valid
     *
     * @param string $phone phone number to validate
     *
     * @return bool
     */
    protected function isValid(&$phone): bool
    {
        $phone = preg_replace('/[^0-9]/', '', $phone);

        if (strlen($phone) >= 10) {
            return true;
        }

        $this->error = 340;
        return false;
    }
}
