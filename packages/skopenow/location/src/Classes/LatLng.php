<?php

/**
 * LatLng
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
 * LatLng
 * 
 * @category Classes
 * @package  Location
 * @author   Queen tech <info@queentechsolutions.net>
 * @license  license http://www.queentechsolutions.net/
 * @link     http://www.queentechsolutions.net/
 */
class LatLng implements LatLngInterface
{
    /**
     * Latitude component of coordinates
     * 
     * @var double
     */
    protected $lat;

    /**
     * Longitude component of coordinates
     * 
     * @var double
     */
    protected $lng;

    /**
     * [__construct description]
     * 
     * @param [type] $lat [description]
     * @param [type] $lng [description]
     *
     * @return void [<description>]
     */
    public function __construct($lat, $lng)
    {
        $this->lat = $lat;
        $this->lng = $lng;
    }

    /**
     * [getLat description]
     * 
     * @return double [description]
     */
    public function getLat()
    {
        return $this->lat;
    }

    /**
     * [getLng description]
     * 
     * @return double
     */
    public function getLng()
    {
        return $this->lng;
    }
}
