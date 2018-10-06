<?php

/**
 * LocationStringParserInterface
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
 * LocationStringParserInterface
 *
 * PHP version 7.0
 * 
 * @category Interface
 * @package  Location
 * @author   Queen tech <info@queentechsolutions.net>
 * @license  license http://www.queentechsolutions.net/
 * @link     http://www.queentechsolutions.net/
 */
interface LocationStringParserInterface
{
    
    /**
     * Extract state from given format "city, state"
     * Example "Oster Bay, NY" will return NY
     * 
     * @param string $cityState city state string
     * 
     * @return string   state extracted from city state format or NULL
     */
    public function extractState(string $cityState);

    /**
     * Extract city from given format "city, state"
     * Example "Oster Bay, NY" will return "Oster Bay"
     * 
     * @param string $cityState city state string
     * 
     * @return string city extracted from city, state format or NULL
     */
    public function extractCity(string $cityState);
}
