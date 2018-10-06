<?php

/**
 * AddressFinder Class
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

use Log;
use Illuminate\Support\Facades\Cache;
use Skopenow\Location\Classes\Address;
use Skopenow\Location\Classes\LatLng;

/**
 * AddressFinder Class
 *
 * @category Class
 * @package  Location
 * @author   Queen tech <info@queentechsolutions.net>
 * @license  license http://www.queentechsolutions.net/
 * @link     http://www.queentechsolutions.net/
 */
class AddressFinder implements AddressFinderInterface
{
    protected $locationDynamoDB;
    protected $mapContentObj;


    public function __construct($mapContentObj, $locationDynamoDB)
    {
        $this->mapContentObj = $mapContentObj;//new AddressMapContent;
        $this->locationDynamoDB = $locationDynamoDB;//new LocationDynamoDB;
    }

    /**
     * [find description]
     *
     * @param string $address [description]
     *
     * @return Address
     */
    public function find(string $address)
    {
        $output = [];
        if (empty($address) || is_numeric($address)) {
            return $output;
        }

        if (strtolower($address) == "new york, new york") {
            $address = "New York, NY";
        }

        $prepAddr = array('address'=>$address);
        $mapResult = $this->getMapData($prepAddr);

        if (isset($mapResult['content']) && $mapResult['content']) {
            $result = json_decode($mapResult['content'], true);
            $gentatedAddress = $this->fromatGeolocationData($result["results"][0]);

            $latLng = new LatLng($gentatedAddress["lat"], $gentatedAddress["lon"]);
            $addressObj = new Address(
                $gentatedAddress["add"], $latLng,
                $gentatedAddress["city"],
                $gentatedAddress["country"],
                $gentatedAddress["zip"]
            );
            $output = $addressObj;
        }
        return $output;
    }

    /**
     * [getMapData description]
     *
     * @param  array       $queryData     [description]
     * @param  int|integer $try           [description]
     *
     * @return [type]                     [description]
     */
    public function getMapData(array $queryData, int $try = 0)
    {
        ksort($queryData);
        $cacheTime = 60*60*24*7;

        $data = [];
        //create key for cache
        $cacheKey = "Address_" . strtolower($queryData["address"]);
        //check if data in cache
        Log::info("check if data in Cache for: " . $queryData["address"]);
        if ($data['content'] = Cache::get($cacheKey)) {
            Log::debug("Data found in cache : ".print_r($data['content'], true));
            return $data;
        }

        //check data in DynamoDB
        Log::info("check if data in DynamoDB");
        $response = $this->locationDynamoDB->getGeoCodeCache($queryData["address"]);
        if ($response) {
            Log::debug("Data found in DynamoDB", [$response]);
            Log::info("Set found data in cache");

            Cache::put($cacheKey, $response, $cacheTime);
            $data['content'] = $response;
            return $data;
        }

        Log::info("get data from google");

        $account = getApiAccount('geolocation');
        if (!count($account) or $try == 3) {
            $checkArray = array(
                'type'=>"map_api_key",
                'status'=>0
            );
            return false;

        } elseif (!count($queryData)) {
            return false;
        }

        $req = $this->mapContentObj->getMapContent($queryData, $account["password"]);

        $body = "";
        if (isset($req['body'])) {
            $body = $req['body'];
        }

        if (isset($req['error']) or isset($req['error_no'])) {
            setApiAccountStatus($account, false, "Err#" . $req['error_no'] . ': ' . $req['error'], null, null, $body);
            return $this->getMapData($queryData, $try+1);
        } else {
            $result = json_decode($req['content'], true);

            if (is_array($result)) {
                if ($result['status'] != 'ok' and $result['status'] != 'OK') {
                    $error_message = "";

                    if (array_key_exists('error_message', $result)) {
                        $error_message = $result['error_message'];
                    }
                    setApiAccountStatus($account, false, $error_message, null, null, $body);

                    return $this->getMapData($queryData, $try+1);
                } else {
                    setApiAccountStatus($account, true);
                }
            } else {
                setApiAccountStatus($account, false, 'unexpected data', null, null, $body);
                return $this->getMapData($queryData, $try+1);
            }
        }

        Log::debug("Data found in Google ", $req);

        if (!empty($req['content']) && is_string($req['content'])) {
            Log::info("Set found data in cache");
            //set reponse in cache
            Cache::put($cacheKey, $req["content"], $cacheTime);

            Log::info("Set found data in DynamoDB");
            //set response in dynamo
            $this->locationDynamoDB->setGeoCodeCache($queryData["address"], $req["content"]);
        }

        return $req;
    }

    /**
     * [fromatGeolocationData description]
     *
     * @param  [type] $geoData [description]
     * @param  array  &$data   [description]
     *
     * @return [type]          [description]
     */
    public function fromatGeolocationData($geoData, &$data = [])
    {
        $data['lat'] = null;
        $data['lon'] = null;
        $data['city']= null;
        $data['country']= null;
        $data['zip']= null;
        $data['add'] = null;

        if (isset($geoData['geometry']['location']['lat']) && isset($geoData['geometry']['location']['lng'])) {
            $data['lat'] = $geoData['geometry']['location']['lat'];
            $data['lon'] = $geoData['geometry']['location']['lng'];
        }

        if (!empty($geoData['address_components']) and is_array($geoData['address_components'])) {
            foreach ($geoData['address_components'] as $locationDetails) {
                if (in_array('locality', $locationDetails['types']) && empty($data['city'])) {
                    $data['city'] = $locationDetails['short_name'];
                } elseif (in_array('administrative_area_level_1', $locationDetails['types']) && empty($data['country'])) {
                    $data['country'] = $locationDetails['short_name'];

                } elseif (in_array('country', $locationDetails['types']) && empty($data['country'])) {
                    $data['country'] = $locationDetails['short_name'];

                } elseif (in_array('postal_code', $locationDetails['types']) && empty($data['zip'])) {
                    $data['zip'] = $locationDetails['short_name'];

                }
            }
        }

        if (!empty($geoData['formatted_address'])) {
            $data['add'] = $geoData['formatted_address'];
        }

        return $data;
    }
}
