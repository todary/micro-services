<?php

/**
 * LocationStringParser
 *
 * PHP version 7.0
 *
 * @category Classes
 * @package  Location
 * @author   Queen tech <info@queentechsolutions.net>
 * @license  license http://www.queentechsolutions.net/
 * @link     http://www.queentechsolutions.net/
 */
namespace Skopenow\Location\Classes;

/**
 * LocationStringParser
 *
 * PHP version 7.0
 *
 * @category Classes
 * @package  Location
 * @author   Queen tech <info@queentechsolutions.net>
 * @license  license http://www.queentechsolutions.net/
 * @link     http://www.queentechsolutions.net/
 */
class LocationStringParser implements LocationStringParserInterface
{
    /**
     * -----------old name => getState---------
     * Extract state from given format "city, state"
     * Example "Oster Bay, NY" will return NY.
     *
     * @param string $cityState city state string
     *
     * @return string state extracted from city state format or NULL
     */
    public function extractState(string $cityState)
    {
        $cityState = preg_replace('#,\s+USA\s*$#', "", $cityState);

        if (!$cityState) {
            return "";
        }

        $cityState = preg_replace("#\d|\-#", "", $cityState);
        $cit = explode(',', $cityState);
        if (isset($cit[1])) {
            return trim(end($cit));
        }
        return "";
    }

    /**
     * -----------old name => getCity---------
     * Extract city from given format "city, state"
     * Example "Oster Bay, NY" will return "Oster Bay"
     *
     * @param string $cityState [<description>]
     *
     * @return string   city extracted from city, state format or NULL
     */
    public function extractCity(string $cityState)
    {
        if (!$cityState) {
            return "";
        }

        $index = strrpos($cityState, ",");

        if ($index === false) {
            return "";
        }

        //Ex: 92 Sunken Orchard ln, Oyster Bay, NY
        if (substr_count($cityState, ',') > 1) {
            $cityState = trim(strstr($cityState, ','), ','); //, Oyster Bay, NY
            return trim(strstr($cityState, ',', true)); // Oyster Bay
        }

        $cit = substr($cityState, 0, $index);

        return trim($cit);
    }
}
