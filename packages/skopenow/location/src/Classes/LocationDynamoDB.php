<?php

/**
 * LocationDynamoDB
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

use Skopenow\Location\Classes\LocationDBSourceInterface;

/**
 * LocationDynamoDB
 *
 * @category Class
 * @package  Location
 * @author   Queen tech <info@queentechsolutions.net>
 * @license  license http://www.queentechsolutions.net/
 * @link     http://www.queentechsolutions.net/
 */
class LocationDynamoDB implements LocationDBSourceInterface
{
    /**
     * [getCity description]
     *
     * @param  string $cityName  [description]
     * @param  string $stateName [description]
     *
     * @return array             [description]
     */
    public function getCity(\ArrayIterator $cityStates)
    {
        $output = [
            "state" => "",
            "bigCity" => false,
            "city" => "",
            "population" => 0,
            "loc_lng" => 0,
            "loc_lat" => 0,
            "country" => "",
            "zipCode" => ""
        ];

        foreach ($cityStates as $cityState) {
            $populations = app()->DynamoDB->query(array(
                'TableName' => 'USPopulations',
                'IndexName' => 'state-city-index',
                'ExpressionAttributeNames' => array('#st' => 'state','#ct' => 'city'),
                'KeyConditionExpression' => '#ct = :city and #st = :state',
                'ExpressionAttributeValues' =>  array (
                    ':city' => array('S' => "{$cityState['cityName']}"),
                    ':state' => array('S' => "{$cityState['stateName']}")
                )
            ));

            $foundPopulations = (isset($populations['Count']) && $populations['Count'])?true:false;

            $data = [];
            if ($foundPopulations) {
                $populations = $populations['Items'][0];

                $output["state"] = reset($populations["state"]);
                $output["bigCity"] = reset($populations["bigCity"]);
                $output["city"] = reset($populations["city"]);
                $output["population"] = reset($populations["population"]);
            }

            $zipCodes = app()->DynamoDB->query(array(
                'TableName' => 'USZipCodes',
                'IndexName' => 'state-city-index',
                'ExpressionAttributeNames' => array('#st' => 'state','#ct' => 'city'),
                'KeyConditionExpression' => '#ct = :city and #st = :state',
                'ExpressionAttributeValues' =>  array (
                    ':city' => array('S' => "{$cityState['cityName']}"),
                    ':state' => array('S' => "{$cityState['stateName']}")
                )
            ));

            $foundZipCodes = (isset($zipCodes['Count']) && $zipCodes['Count'])?true:false;
            $data2 = [];
            if ($foundZipCodes) {
                $zipCodes = $zipCodes['Items'][0];
                $output["loc_lng"] = reset($zipCodes["loc_lng"]);
                $output["loc_lat"] = reset($zipCodes["loc_lat"]);
                $output["country"] = reset($zipCodes["country"]);
                $output["zipCode"] = reset($zipCodes["zipCode"]);
                
            }

            return $output;
        }

        
        if (count($data)) {
            return $data;
        }

        return false;
    }

    /**
     * [getCityZipCodes description]
     *
     * @param  string $cityName  [description]
     * @param  string $stateName [description]
     *
     * @return array             [description]
     */
    //public function getCityZipCodes(string $cityName, string $stateName)
    public function getCityZipCodes(\ArrayIterator $cityStates)
    {
        $data = [];
        foreach ($cityStates as $cityState) {
            $getPostCodes = app()->DynamoDB->query(array(
                'TableName' => 'USZipCodes',
                'IndexName' => 'state-city-index',
                'ExpressionAttributeNames' => array('#st' => 'state','#ct' => 'city'),
                'KeyConditionExpression' => '#ct = :city and #st = :state',
                'ExpressionAttributeValues' =>  array (
                    ':city' => array('S' => "{$cityState['cityName']}"),
                    ':state' => array('S' => "{$cityState['stateName']}")
                )
            ));

            $foundPostcodes = (isset($getPostCodes['Count']) && $getPostCodes['Count'])?true:false;

            if ($foundPostcodes) {
                $data = array_map(function ($e) {
                    return str_pad($e['zipCode']['N'], 5, "0", STR_PAD_LEFT) ;
                }, $getPostCodes['Items']);
            }
        }

        if (count($data)) {
            return $data;
        }

        return false;
    }

    /**
     * [getNearestCities description]
     *
     * @param string|double $lat    latitude of search location
     * @param string|double $lon    longitude of search location
     * @param string|double $radius radius of search area
     *
     * @return array                [description]
     */
    public function getNearestCities(\ArrayIterator $locationData)
    {
        throw new \Exception('getNearestCities Method For Mono DB');
    }

    public function getGeoCodeCache(string $address): string
    {
        $getLocation = app()->DynamoDB->query(array(
            'TableName' => 'GeocodeCache',
            'ExpressionAttributeNames' => array('#loc' => 'location'),
            'KeyConditionExpression' => '#loc = :location',
            'ExpressionAttributeValues' =>  array (
                ':location' => array('S' => "{$address}")
            )
        ));
        $res = "";
        if (isset($getLocation['Count'])  && $getLocation['Count']) {
            $geocodeCacheStatus = true;
            $res = $getLocation['Items'][0]['data']['S'];
        }
        return $res;
    }

    public function setGeoCodeCache(string $address, string $data)
    {
        $geocodeCache= array(
            "location"=> $address,
            "data"=> $data,
        );

        if (app()->DynamoDB->marshaler) {
            $items= app()->DynamoDB->marshaler->marshalItem($geocodeCache);
            app()->DynamoDB->putItem(array(
                'TableName' => 'GeocodeCache',
                'Item' => $items
            ));
        }

        return true;
    }
}
