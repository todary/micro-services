<?php

/**
 * AddressFinderInterface
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
 * AddressFinderInterface
 *
 * @category Interface
 * @package  Location
 * @author   Queen tech <info@queentechsolutions.net>
 * @license  license http://www.queentechsolutions.net/
 * @link     http://www.queentechsolutions.net/
 */
interface AddressFinderInterface
{
    /**
     * [findAddress description]
     * 
     * @param string $addressString address string to get its information
     * 
     * @return \Skope\AddressInterface
     */
    public function find(string $addressString);
}
