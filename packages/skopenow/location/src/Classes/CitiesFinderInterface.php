<?php

/**
 * CitiesFinderInterface
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

use Skopenow\Location\Classes\LocationDBSourceInterface;

/**
 * CitiesFinderInterface
 *
 * @category Interface
 * @package  Location
 * @author   Queen tech <info@queentechsolutions.net>
 * @license  license http://www.queentechsolutions.net/
 * @link     http://www.queentechsolutions.net/
 */
interface CitiesFinderInterface
{
    /**
     * [getCity description]
     * 
     * @param string $cityName  [description]
     * @param string $stateName [description]
     * 
     * @return City            [description]
     */
    public function getCity(string $cityName, string $stateName, $dbSource);

    /**
     * Get city zip codes
     * 
     * @param string $city  name of the city
     * @param string $state name of the state
     * 
     * @return array        array of zip codes strings
     */
    public function getCityZipCodes(string $city, string $state, $dbSource);

    /**
     * Get nearst cities to given lat lng within certain radius
     * 
     * @param string|double $lat    latitude of search location
     * @param string|double $lon    longitude of search location
     * @param string|double $radius radius of search area
     * 
     * @return array[]:\Skope\CityInterface    array of cities
     */
    public function getNearestCities($lat, $lon, $dbSource, $radius);
}
