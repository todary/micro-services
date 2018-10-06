<?php

/**
 * Address Class
 *
 * PHP version 7.0
 * 
 * @category Class
 * @package  Location
 * @author   Queen tech <info@queentechsolutions.net>
 * @license  license http://www.queentechsolutions.net/
 * @link     http://www.queentechsolutions.net/
 */
namespace Skopenow\Location\Classes;

/**
 * Address Class
 * 
 * @category Class
 * @package  Location
 * @author   Queen tech <info@queentechsolutions.net>
 * @license  license http://www.queentechsolutions.net/
 * @link     http://www.queentechsolutions.net/
 */
class Address implements AddressInterface
{
    /**
     * Title of address
     * 
     * @var string
     */
    protected $title;

    /**
     * City Name
     * 
     * @var string
     */
    protected $city;

    /**
     * Country Name
     * 
     * @var string
     */
    protected $country;

    /**
     * Latitude and Longitude of address
     * 
     * @var \Skope\LatLng
     */
    protected $latLng;

    /**
     * Zipcode of address
     * 
     * @var string
     */
    protected $zipCode;

    /**
     * [__construct description]
     * 
     * @param [type] $title   [description]
     * @param [type] $latLng  [description]
     * @param [type] $city    [description]
     * @param [type] $country [description]
     * @param [type] $zipCode [description]
     */
    public function __construct($title, $latLng, $city, $country, $zipCode)
    {
        $this->title = $title;
        $this->city = $city;
        $this->country = $country;
        $this->latLng = $latLng;
        $this->zipCode = $zipCode;
    }

    /**
     * [getTitle description]
     * 
     * @return [type] [description]
     */
    public function getTitle() :string
    {
        return $this->title;
    }

    /**
     * [getCity description]
     * 
     * @return string [description]
     */
    public function getCity(){
        return $this->city;
    }

    /**
     * [getCountry description]
     * 
     * @return string [description]
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * [getLatLng description]
     * 
     * @return LatLng [description]
     */
    public function getLatLng() :LatLngInterface
    {
        return $this->latLng;
    }

    /**
     * [getZipCode description]
     * 
     * @return string [description]
     */
    public function getZipCode()
    {
        return $this->zipCode;
    }
}
