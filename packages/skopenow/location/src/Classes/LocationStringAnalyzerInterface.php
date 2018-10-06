<?php

/**
 * LocationStringAnalyzerInterface
 *
 * PHP version 7.0
 * 
 * @category Interface
 * @package  Location
 * @author   Queen tech <info@queentechsolutions.net>
 * @license  license http://www.queentechsolutions.net/
 * @link     http://www.queentechsolutions.net/
 */
namespace Skopenow\Location\Classes;

/**
 * LocationStringAnalyzerInterface
 * 
 * @category Interface
 * @package  Location
 * @author   Queen tech <info@queentechsolutions.net>
 * @license  license http://www.queentechsolutions.net/
 * @link     http://www.queentechsolutions.net/
 */
interface LocationStringAnalyzerInterface
{
    /**
     * Check if test string is an address string
     * 
     * @param string $testString string under test
     * 
     * @return boolean
     */
    public function isAddressString(string $testString);

    /**
     * Check if test string is a city string
     * 
     * @param string $testString string under test
     * 
     * @return boolean
     */
    public function isCityString(string $testString);
}
