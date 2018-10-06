<?php
/**
 * Location validation code
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

use Skopenow\Validation\Interfaces\LocationValidationInterface as LValidate;

/**
 * Location validation class
 *
 * @category Micro_Services-phase_1
 * @package  Validation
 * @author   Mahmoud AboElnouR <aboelnoor@queentecksolutions.com>
 * @license  QueenTechSolutions, Inc.
 * @link     http://www.queentecksolutions.com
 **/
class LocationValidation extends Validation implements LValidate
{
    const VALIDATION_PATTERN = '/^\w[\w_\s,\.\-\/]*\w$/u';
    const FILTER_WORDS = [', United States', ', us', ', usa'];

    /**
     * Check if location is valid
     *
     * @param string $location location to validate
     *
     * @return bool
     */
    protected function isValid(&$location): bool
    {
        $this->error = 330;
        $location = trim($location, ',');
        if (strlen($location) < 2) {
            return false;
        }

        // check if the location end with 2 character with out ,
        // Oyster Bay NY = Oyster Bay, NY
        if (!strrpos($location, ',')) {
            $this->commaLocation($location);
        }

        $checkChar = preg_match(self::VALIDATION_PATTERN, $location);

        if (!$checkChar) {
            return false;
        }

        if (!$this->isValidCity($location)) {
            return false;
        }

        $this->error = null;
        return true;
    }

    /**
     * Comma seperate location city
     *
     * @param string $location location to seperate
     *
     * @return string
     */
    public function commaLocation(string &$location): string
    {
        if (substr_count($location, ' ') > 1) {
            $suffix = strrchr($location, ' ');
            if (strlen($suffix) == 3) {
                //Oyster Bay NY => Oyster Bay
                $location = substr($location, 0, strrpos($location, $suffix));

                //Oyster Bay => Oyster Bay, NY
                $location .= substr($location, -1) == ',' ? $suffix : ",$suffix";
            }
        }
        return trim($location);
    }

    /**
     * Get state from the location
     *
     * @param string $location location to get state
     *
     * @return string
     */
    public function getState(string $location): string
    {
        $location = preg_replace('#\d|\-#', '', $location);
        $city = strrchr($location, ',');
        return trim(trim($city, ','));
    }

    public function isValidCity(string &$location): bool
    {
        $location = preg_replace('#,\s*#i', ', ', $location);

        $CommaCount = substr_count($location, ',');
        if ($CommaCount < 3 && $CommaCount > 0) {
            $location = str_ireplace(self::FILTER_WORDS, '', $location);
            if ($_state = $this->getState($location)) {
                if (strlen($_state) < 2) {
                    return false;
                }
            }
        }
        return true;
    }
}
