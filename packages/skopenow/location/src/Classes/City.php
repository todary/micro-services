<?php
/**
 * City Class
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
 * City Class
 *
 * @category Classes
 * @package  Location
 * @author   Queen tech <info@queentechsolutions.net>
 * @license  license http://www.queentechsolutions.net/
 * @link     http://www.queentechsolutions.net/
 */
class City implements CityInterface
{
    /**
     * Name of the city
     * 
     * @var string
     */
    protected $name;

    /**
     * Latitude and Longitude
     * 
     * @var \Skope\LatLng
     */
    protected $latLng;
 
    /**
     * Zip code of city
     * 
     * @var string
     */
    protected $zipCode;

    /**
     * Number of people in that city
     * 
     * @var integer
     */
    protected $population;

    /**
     * City size
     * 
     * @var string
     */
    protected $size;

    /**
     * [__construct description]
     * 
     * @param [type] $name       [description]
     * @param [type] $latLng     [description]
     * @param [type] $zipCode    [description]
     * @param [type] $population [description]
     * @param [type] $size       [description]
     *
     * @return void
     */
    public function __construct(
        $name, 
        $latLng, 
        $zipCode = null, 
        $population = null, 
        $size = null
    ) {
        $this->name = $name;
        $this->latLng = $latLng;
        $this->zipCode = $zipCode;
        $this->population = $population;
        $this->size = $size;
    }

    /**
     * [getName description]
     * 
     * @return void
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * [getLatLng description]
     * 
     * @return void
     */
    public function getLatLng()
    {
        return $this->latLng;
    }

    /**
     * [getZipCode description]
     * 
     * @return void
     */
    public function getZipCode()
    {
        return $this->zipCode;
    }

    /**
     * [getPopulation description]
     * 
     * @return void
     */
    public function getPopulation()
    {
        return $this->population;
    }

    /**
     * [getSize description]
     * 
     * @return void
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * [isBigCity description]
     * 
     * @return void
     */
    public function isBigCity()
    {
        return $this->size == 'bigCity'?true:false;
    }

    /**
     * [setLatLng description]
     * 
     * @param [type] $latLng [description]
     *
     * @return void
     */
    public function setLatLng($latLng)
    {
        $this->latLng = $latLng;
    }

    /**
     * [setZipCode description]
     * 
     * @param [type] $zipCode [description]
     *
     * @return void
     */
    public function setZipCode($zipCode)
    {
        $this->zipCode = $zipCode;
    }

    /**
     * [setName description]
     * 
     * @param [type] $name [description]
     *
     * @return void
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * [setPopulation description]
     * 
     * @param [type] $population [description]
     *
     * @return void
     */
    public function setPopulation($population)
    {
        $this->population = $population;
    }

    /**
     * [setSize description]
     * 
     * @param [type] $size [description]
     *
     * @return void
     */
    public function setSize($size)
    {
        $this->size = $size;
    }
}
