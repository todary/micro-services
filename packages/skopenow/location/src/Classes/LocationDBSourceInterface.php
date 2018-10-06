<?php

/**
 * LocationDBSourceInterface
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
 * LocationDBSourceInterface
 *
 * @category Interface
 * @package  Location
 * @author   Queen tech <info@queentechsolutions.net>
 * @license  license http://www.queentechsolutions.net/
 * @link     http://www.queentechsolutions.net/
 */
interface LocationDBSourceInterface
{
    /**
     * [getCity description]
     *
     * @param  string $cityName  [description]
     * @param  string $stateName [description]
     *
     * @return array             [description]
     */
    //public function getCity(string $cityName, string $stateName);
    public function getCity(\ArrayIterator $cityState);

    /**
     * [getCityZipCodes description]
     *
     * @param  string $cityName  [description]
     * @param  string $stateName [description]
     *
     * @return array             [description]
     */
    public function getCityZipCodes(\ArrayIterator $cityState);

    /**
     * [getNearestCities description]
     *
     * @param string|double $lat    latitude of search location
     * @param string|double $lon    longitude of search location
     * @param string|double $radius radius of search area
     *
     * @return array                [description]
     */
    public function getNearestCities(\ArrayIterator $locationData);

    /**
     * [getGeoCodeCache description]
     *
     * @param string $address [description]
     *
     * @return string          [description]
     */
    public function getGeoCodeCache(string $address): string;

    /**
     * [setGeoCodeCache description]
     *
     * @param string $address [description]
     * @param string $req     [description]
     *
     */
    public function setGeoCodeCache(string $address, string $req);
}
