<?php

/**
 * CityInterface
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
 * CityInterface
 * 
 * @category Interface
 * @package  Location
 * @author   Queen tech <info@queentechsolutions.net>
 * @license  license http://www.queentechsolutions.net/
 * @link     http://www.queentechsolutions.net/
 */
interface CityInterface
{
    /**
     * [getName description]
     * 
     * @return void
     */
    public function getName();
   
    /**
     * [getLatLng description]
     * 
     * @return void
     */
    public function getLatLng();

    /**
     * [getZipCode description]
     * 
     * @return void
     */
    public function getZipCode();

    /**
     * [getPopulation description]
     * 
     * @return void
     */
    public function getPopulation();

    /**
     * [getSize description]
     * 
     * @return void
     */
    public function getSize();

    /**
     * [isBigCity description]
     * 
     * @return void
     */
    public function isBigCity();

    /**
     * [setLatLng description]
     * 
     * @param [type] $latLng [description]
     *
     * @return void
     */
    public function setLatLng($latLng);

    /**
     * [setZipCode description]
     * 
     * @param [type] $zipCode [description]
     *
     * @return void
     */
    public function setZipCode($zipCode);

    /**
     * [setName description]
     * 
     * @param [type] $name [description]
     *
     * @return void
     */
    public function setName($name);

    /**
     * [setPopulation description]
     * 
     * @param [type] $population [description]
     *
     * @return void
     */
    public function setPopulation($population);

    /**
     * [setSize description]
     * 
     * @param [type] $size [description]
     *
     * @return void
     */
    public function setSize($size);
}
