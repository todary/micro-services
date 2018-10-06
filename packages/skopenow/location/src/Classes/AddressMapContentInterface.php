<?php

/**
 * AddressMapContentInterface
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
 * AddressMapContentInterface
 *
 * @category Interface
 * @package  Location
 * @author   Queen tech <info@queentechsolutions.net>
 * @license  license http://www.queentechsolutions.net/
 * @link     http://www.queentechsolutions.net/
 */
interface AddressMapContentInterface
{
    public function getMapContent(array $queryData, string $accountPassword);

    public function curl_content(string $url);
}