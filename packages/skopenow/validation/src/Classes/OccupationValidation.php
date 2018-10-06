<?php
/**
 * Occupation validation code
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
 * Occupation validation class
 *
 * @category Micro_Services-phase_1
 * @package  Validation
 * @author   Mahmoud AboElnouR <aboelnoor@queentecksolutions.com>
 * @license  QueenTechSolutions, Inc.
 * @link     http://www.queentecksolutions.com
 **/
class OccupationValidation extends Validation
{
    /**
     * Check if occupation is valid
     *
     * @param string $occupation occupation to validate
     *
     * @return bool
     */
    protected function isValid(&$occupation): bool
    {
        if (substr_count($occupation, ',') < 2) {
            return true;
        }

        $this->error = 380;
        return false;
    }
}
