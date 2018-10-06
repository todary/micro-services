<?php

/**
 * LatLng
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
 * LatLngInterface
 *
 * @category Interface
 * @package  Location
 * @author   Queen tech <info@queentechsolutions.net>
 * @license  license http://www.queentechsolutions.net/
 * @link     http://www.queentechsolutions.net/
 */
interface LatLngInterface
{
    /**
     * Return latitude component of latlng
     * 
     * @return float
     */
    public function getLat();

    /**
     * Return longitude component of latlng
     * 
     * @return float
     */
    public function getLng();
}
