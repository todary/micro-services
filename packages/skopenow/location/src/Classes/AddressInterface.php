<?php

/**
 * AddressInterface
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
 * AddressInterface
 *
 * @category Interface
 * @package  Location
 * @author   Queen tech <info@queentechsolutions.net>
 * @license  license http://www.queentechsolutions.net/
 * @link     http://www.queentechsolutions.net/
 */
interface AddressInterface
{
    /**
     * [getTitle description]
     * 
     * @return [type] [description]
     */
    public function getTitle() :string;

    /**
     * [getLatLng description]
     * 
     * @return [type] [description]
     */
    public function getLatLng() :LatLngInterface;
}
