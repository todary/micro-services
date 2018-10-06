<?php
/**
 * Address Validation code
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
 * Address Validation class
 *
 * @category Micro_Services-phase_1
 * @package  Validation
 * @author   Mahmoud AboElnouR <aboelnoor@queentecksolutions.com>
 * @license  QueenTechSolutions, Inc.
 * @link     http://www.queentecksolutions.com
 **/
class AddressValidation extends Validation
{
    const VALIDATION_PATTERN = '#^[\d-]+(\w{2})?\s.+,\s*[\w|\s]+$#';
    /**
     * Check if address is valid
     *
     * @param string $address address to validate
     *
     * @return bool
     */
    protected function isValid(&$address): bool
    {
        //11 Oyster Bay , NY
        $valid_address = preg_match(self::VALIDATION_PATTERN, $address);

        if ($valid_address) {
            return true;
        }

        $this->error = 335;
        return false;
    }
}
