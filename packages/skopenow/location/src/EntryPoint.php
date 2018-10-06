<?php

/**
 * LocationEntrypoint
 *
 * PHP version 7.0
 *
 * @category Class
 * @package  Location
 * @author   Queen tech <info@queentechsolutions.net>
 * @license  license http://www.queentechsolutions.net/
 * @link     http://www.queentechsolutions.net/
 */

namespace Skopenow\Location;

use Skopenow\Location\Classes\GeoHelper;
use Skopenow\Location\Classes\LatLng;
use Skopenow\Location\Classes\LocationStringAnalyzer;
use Skopenow\Location\Classes\LocationStringParser;
use Skopenow\Location\Classes\CitiesFinder;
use Skopenow\Location\Classes\AddressFinder;
use Skopenow\Location\Classes\AddressMapContent;
use Skopenow\Location\Classes\LocationDynamoDB;

/**
 * LocationEntrypoint
 *
 * @category Class
 * @package  Location
 * @author   Queen tech <info@queentechsolutions.net>
 * @license  license http://www.queentechsolutions.net/
 * @link     http://www.queentechsolutions.net/
 */
class EntryPoint //implements EntrypointInterface
{
    public function __construct()
    {
        $locationStringAnalyzer = new LocationStringAnalyzer();
        $locationStringParser = new LocationStringParser();
        $citiesFinder = new CitiesFinder();

        $mapContentObj = new AddressMapContent();
        $locationDynamoDB = new LocationDynamoDB();
        $addressFinder = new AddressFinder($mapContentObj, $locationDynamoDB);

        $this->geoHelper = new GeoHelper($citiesFinder, $addressFinder, $locationStringAnalyzer, $locationStringParser);
    }

    /**
     *[calculateDistance description]
     *
     *@param array $firstlatLnginp  [description]
     *@param array $secondlatLnginp [description]
     *
     *@return \arrayIterator        [description]
     */
    public function calculateDistance($firstlatLnginp, $secondlatLnginp)
    {
        $fitstLat = 0;
        $firstLng = 0;
        $secondLat = 0;
        $secondLng = 0;

        if (isset($firstlatLnginp["lat"])) {
            $fitstLat = floatval($firstlatLnginp["lat"]);
        }

        if (isset($firstlatLnginp["lng"])) {
            $firstLng = floatval($firstlatLnginp["lng"]);
        }

        if (isset($secondlatLnginp["lat"])) {
            $secondLat = floatval($secondlatLnginp["lat"]);
        }

        if (isset($secondlatLnginp["lng"])) {
            $secondLng = floatval($secondlatLnginp["lng"]);
        }

        $firstlatLng = new LatLng($fitstLat, $firstLng);
        $secondlatLng = new LatLng($secondLat, $secondLng);

        $distance = $this->geoHelper->calculateDistance($firstlatLng, $secondlatLng);

        $input = ["firstlatLnginp"=>$firstlatLnginp,"secondlatLnginp"=>$secondlatLnginp];

        $this->addLog($input, [$distance], "calculateDistance");

        return new \arrayIterator(["distance"=>$distance]);
    }

    /**
     *[findCities description]
     *
     *@param  array  $cityStrings [description]
     *
     *@return arrayIterator       [description]
     */
    public function findCities(array $cityStrings)
    {
        $output = [];
        $cities = $this->geoHelper->findCities($cityStrings);
        foreach ($cities as $key => $city) {
            $cityData = array(
                "name"=>"",
                "latLng"=> [],
                "zipCode"=>"",
                "population"=>0,
                "size"=>null
            );

            if ($city) {
                $latLong = ["lat"=>$city["loc_lat"], "lng"=>$city["loc_lng"]];
                $cityData = array(
                    "name"=>$city["city"],
                    "latLng"=> $latLong,
                    "zipCode"=>$city["zipCode"],
                    "population"=>$city["population"]??0,
                    "size"=>$city["bigCity"]??1
                );
            }

            $output[$key] = $cityData;
        }
        $this->addLog($cityStrings, $output, "findCities");
        return new \arrayIterator($output);
    }

    /**
     *[findAddress description]
     *
     *@param  array  $addressString [description]
     *
     *@return arrayIterator         [description]
     */
    public function findAddress(array $addressString)
    {
        $output = [];
        foreach ($addressString as $address) {
            $newAddress = $this->geoHelper->findAddress($address);

            $title = "";
            $city = "";
            $country = "";
            $zipCode = "";
            $latLng["lat"] ="";
            $latLng["lng"] = "";

            if (!empty($newAddress)) {
                $title = $newAddress->getTitle();
                $city = $newAddress->getCity();
                $country = $newAddress->getCountry();
                $zipCode = $newAddress->getZipCode();

                $latLngObj = $newAddress->getLatLng();
                $latLng["lat"] = $latLngObj->getLat();
                $latLng["lng"] = $latLngObj->getLng();
            }
            $output[$address] = array(
                "title"=>$title ,
                "city"=>$city,
                "state"=>$country,
                "country"=>'',
                "latLng"=>$latLng,
                "zipCode"=>$zipCode
                ) ;
        }
        $this->addLog($addressString, $output, "findAddress");
        return new \arrayIterator($output);
    }

    /**
     *[findLatLng description]
     *
     *@param array  $keywords [description]
     *
     *@return arrayIterator   [description]
     */
    public function findLatLng(array $keywords)
    {
        $output=[];
        foreach ($keywords as $keyword) {
            $latLng = $this->geoHelper->findLatLng($keyword);
            if ($latLng) {
                $output[$keyword] = ["lat"=>$latLng->getLat(), "lng"=>$latLng->getLng()];
            }

            $output[$keyword] = false;
        }
        $this->addLog($keywords, $output, "findLatLng");
        return new \arrayIterator($output);
    }

    /**
     *[isLocatedInUS description]
     *
     *@param array   $cityOrStateStrings [description]
     *
     *@return boolean                     [description]
     */
    public function isLocatedInUS(array $StateStrings)
    {
        $output=[];
        foreach ($StateStrings as $StateString) {
            $located = $this->geoHelper->isLocatedInUS($StateString);

            $output[$StateString] = $located;
        }
        $this->addLog($StateStrings, $output, "isLocatedInUS");
        return new \arrayIterator($output);
    }

    /**
     *[findNearestCities description]
     *
     *@param  \ArrayIterator $locations [description]
     *
     *@return \ArrayIterator            [description]
     */
    public function findNearestCities(\ArrayIterator $locations)
    {
        $output=[];
        foreach ($locations as $location) {
            $cities = $this->geoHelper->findNearestCities(
                $location["lat"],
                $location["lng"],
                $location["radius"] = 1/69
            );

            $output[] = new \ArrayIterator(["location"=>$location,"cities"=>$cities]);
        }

        $this->addLog($locations, $output, "findNearestCities");
        return new \ArrayIterator($output);
    }

    /**
     *[getCityZipCodes description]
     *
     *@param \ArrayIterator $cityStates [description]
     *
     *@return \ArrayIterator            [description]
     */
    public function getCityZipCodes(\ArrayIterator $cityStates)
    {
        $output=[];
        foreach ($cityStates as $cityState) {
            $zipcodes = $this->geoHelper->getCityZipCodes($cityState);

            $output[$cityState] = $zipcodes;
        }

        $this->addLog($cityStates, $output, "getCityZipCodes");
        return new \arrayIterator($output);
    }

    /**
     *[getStateAbv description]
     *
     *@param \ArrayIterator $statesKeyword [description]
     *
     *@return \ArrayIterator               [description]
     */
    public function getStateAbv(\ArrayIterator $statesKeyword)
    {
        $output=[];
        foreach ($statesKeyword as $stateKeyword) {
            $stateAbv = $this->geoHelper->getStateAbv($stateKeyword);
            $output[$stateKeyword] = $stateAbv;
        }

        $this->addLog($statesKeyword, $output, "getStateAbv");
        return new \arrayIterator($output);
    }

    /**
     *[getStateName description]
     *
     *@param \ArrayIterator $statesKeyword [description]
     *
     *@return \ArrayIterator                [description]
     */
    public function getStateName(\ArrayIterator $statesKeyword)
    {
        $output=[];
        foreach ($statesKeyword as $stateKeyword) {
            if ($stateKeyword) {
                $stateAbv = $this->geoHelper->getStateName($stateKeyword);

            } else {
                $stateAbv = "";
            }

            $output[$stateKeyword] = $stateAbv;
        }

        $this->addLog($statesKeyword, $output, "getStateName");
        return new \arrayIterator($output);
    }

    /**
     *[getStateNameByAreaCode description]
     *
     *@param ArrayIterator $areasCode [description]
     *
     *@return ArrayIterator             [description]
     */
    public function getStateNameByAreaCode(\ArrayIterator $areasCode)
    {
        $output=[];
        foreach ($areasCode as $areaCode) {
            if ($areasCode) {
                $stateName = $this->geoHelper->getStateNameByAreaCode($areaCode);
            } else {
                $stateName = "";
            }

            $output[$areaCode] = $stateName;
        }

        $this->addLog($areasCode, $output, "getStateNameByAreaCode");
        return new \arrayIterator($output);
    }

    /**
     *[normalizeStateName description]
     *
     *@param  \ArrayIterator $statesName [description]
     *
     *@return \ArrayIterator             [description]
     */
    public function normalizeStateName(\ArrayIterator $statesName)
    {
        $output=[];
        foreach ($statesName as $stateName) {
            $normalizeStateName = $this->geoHelper->normalizeStateName($stateName);

            $output[$stateName] = $normalizeStateName;
        }

        return new \arrayIterator($output);
    }

    /**
     *[extractState description]
     *
     *@param \ArrayIterator $cityStates [description]
     *
     *@return \ArrayIterator            [description]
     */
    public function extractState(\ArrayIterator $cityStates)
    {
        $output=[];
        $parserObj = new LocationStringParser();

        foreach ($cityStates as $cityState) {
            if (empty($cityState)) {
                continue;
            }

            $state = $parserObj->extractState($cityState);

            $output[$cityState] = $state;
        }

        return new \arrayIterator($output);
    }

    /**
     *[extractCity description]
     *
     *@param \ArrayIterator $cityStates [description]
     *
     *@return \ArrayIterator            [description]
     */
    public function extractCity(\ArrayIterator $cityStates)
    {
        $output=[];
        $parserObj = new LocationStringParser();

        foreach ($cityStates as $cityState) {
            if (empty($cityState)) {
                continue;
            }

            $city = $parserObj->extractCity($cityState);

            $output[$cityState] = $city;
        }

        return new \arrayIterator($output);
    }

    public function splitLocation(\ArrayIterator $cityStates)
    {
        $output=[];
        $parserObj = new LocationStringParser();

        foreach ($cityStates as $cityState) {
            if (empty($cityState)) {
                continue;
            }

            $city = $parserObj->extractCity($cityState);
            $state = $parserObj->extractState($cityState);

            $output[] = ['city' => $city, 'state' => $state];
        }

        return new \arrayIterator($output);
    }

    protected function addLog($input, $output, $method)
    {
        $state = [
            "report_id" => config("state.report_id"),
            "combination_id" => config("state.combination_id"),
            "combination_level_id" => config("state.combination_level_id"),
            "environment" => env("APP_ENV")
        ];

        $loggerData = [
            "input" => $input,
            "method" => $method,
            "output" => $output
        ];

        $logger = loadService("logger", [140]);
        $output = $logger->addLog($state, $loggerData);
        //dd($output);
    }
}
