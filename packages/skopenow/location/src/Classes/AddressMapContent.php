<?php

/**
 * AddressMapContent
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
 * AddressMapContent
 *
 * @category Class
 * @package  Location
 * @author   Queen tech <info@queentechsolutions.net>
 * @license  license http://www.queentechsolutions.net/
 * @link     http://www.queentechsolutions.net/
 */
class AddressMapContent implements AddressMapContentInterface
{
    /**
     * [getMapContent description]
     *
     * @param  array  $queryData       [description]
     * @param  string $accountPassword [description]
     *
     * @return array                   [description]
     */
    public function getMapContent(array $queryData, string $accountPassword): array
    {
        $query = "&";
        foreach ($queryData as $key => $value) {
            if (strtolower($value) == "new york, new york") {
                $value = "New York, NY";
            }
            $query .= rawurlencode($key).'="'.rawurlencode($value).'"&';
        }

        $url = "https://maps.google.com/maps/api/geocode/json?v=3&sensor=false" . $query."key=".$accountPassword;

        return $this->curl_content($url);
    }

    /**
     * [curl_content description]
     *
     * @param  string $url [description]
     *
     * @return array       [description]
     */
    public function curl_content(string $url)
    {
    	$agent = 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.9.0.8) Gecko/2009032609 Firefox/3.0.8';
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, 1);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
		curl_setopt($ch, CURLOPT_USERAGENT, $agent);
		$result = curl_exec($ch);
		return array(
				'content' => $result
		);
    }
}
