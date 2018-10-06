<?php
/**
 * usename validation code
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
 * usename validation class
 *
 * @category Micro_Services-phase_1
 * @package  Validation
 * @author   Mahmoud AboElnouR <aboelnoor@queentecksolutions.com>
 * @license  QueenTechSolutions, Inc.
 * @link     http://www.queentecksolutions.com
 **/
class UsernameValidation extends Validation
{
    const VALIDATION_PATTERN = '/^[A-z]+(?:[@._\-A-Za-z0-9])*$/';
    /**
     * Check if username is valid
     *
     * @param string $username username to validate
     *
     * @return bool
     */
    protected function isValid(&$username): bool
    {
        if (preg_match(self::VALIDATION_PATTERN, $username)) {
            return true;
        }
        $this->error = 390;
        return false;
    }
}
