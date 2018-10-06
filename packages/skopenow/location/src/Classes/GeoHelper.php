<?php

/**
 * GeoHelper
 *
 * PHP version 7.0
 * 
 * @category Helper
 * @package  Location
 * @author   Queen tech <info@queentechsolutions.net>
 * @license  license http://www.queentechsolutions.net/
 * @link     http://www.queentechsolutions.net/
 */
namespace Skopenow\Location\Classes;

use Skopenow\Location\Classes\LocationDynamoDB;
use Skopenow\Location\Classes\LocationMongoDB;
/**
 * LocationStringAnalyzer
 * 
 * @category Helper
 * @package  Location
 * @author   Queen tech <info@queentechsolutions.net>
 * @license  license http://www.queentechsolutions.net/
 * @link     http://www.queentechsolutions.net/
 */
class GeoHelper
{
    /**
     * [__construct description]
     * 
     * @param CitiesFinderInterface           $citiesFinder     [description]
     * @param AddressFinderInterface          $addressFinder    [description]
     * @param LocationStringAnalyzerInterface $locationAnalyzer [description]
     * @param LocationStringParserInterface   $locationParser   [description]
     */
    public function __construct(
        $citiesFinder,
        $addressFinder,
        $locationAnalyzer,
        $locationParser
    ) {
    
        $this->addressFinder = $addressFinder;
        $this->citiesFinder = $citiesFinder;
        $this->locationStringAnalyzer = $locationAnalyzer;
        $this->locationStringParser = $locationParser;
        $this->usStatesAbbreviations = include __DIR__.'/../data/states_abv.php';
        $this->usStatesAreaCodes = include __DIR__.'/../data/states_area_codes.php';
    }

    /**
     * Return disance between two lat lng points
     * 
     * @param [type] $firstlatLng  [description]
     * @param [type] $secondlatLng [description]
     * 
     * @return [type]               [description]
     */
    public function calculateDistance($firstlatLng, $secondlatLng)
    {
        $earthRadius = 6371000;
        // convert from degrees to radians
        $latFrom = deg2rad($firstlatLng->getLat());
        $lngFrom = deg2rad($firstlatLng->getLng());
        $latTo = deg2rad($secondlatLng->getLat());
        $lngTo = deg2rad($secondlatLng->getLng());
        
        $lonDelta = $lngTo - $lngFrom;
        $a = pow(cos($latTo) * sin($lonDelta), 2) 
        + pow(
            cos($latFrom) * sin($latTo) - sin($latFrom) 
            * cos($latTo) * cos($lonDelta), 2
        );
        $b = sin($latFrom) * sin($latTo) 
        + cos($latFrom) * cos($latTo) * cos($lonDelta);
        $angle = atan2(sqrt($a), $b);
        return $angle * $earthRadius; // in meter
    }

    /**
     * Find city data using city string for example "Oyster Bay, New York"
     *
     * @param string $cityString []
     * 
     * @return \Skope\City
     */
    public function findCity($cityString)
    {
        $dbSource = new LocationDynamoDB;
        $cityName = $this->locationStringParser->extractCity($cityString);
        $stateName = $this->locationStringParser->extractState($cityString);
        $stateName = $this->getStateName($stateName);
        return $this->citiesFinder->getCity($cityName, $stateName, $dbSource);
    }

    /**
     * Find array of cities
     *
     * @param array $cityStrings []
     * 
     * @return array \Skope\City array of (cities model or null)
     */
    public function findCities(array $cityStrings)
    {
        $cities = [];
        foreach ($cityStrings as $cityString) {
            $cities[$cityString] = $this->findCity($cityString);
        }
        return $cities;
    }

    /**
     * Find address using address string
     *
     * @param string $addressString 
     * address string for example '299 SW 8th St, Miami, FL 33130, USA'
     * 
     * @return \Skope\Address   Address Model
     */
    public function findAddress($addressString)
    {
        return $this->addressFinder->find($addressString);
    }

    /**
     * Try to find latitude and longitude of given location keyword
     * either address or city
     *
     * @param string $keyword address or city keyword
     * 
     * @return \Skope\LatLng
     */
    public function findLatLng(string $keyword)
    {
        if ($this->locationStringAnalyzer->isAddressString($keyword)) {            
            $address = $this->findAddress($keyword);
            if ($address) {
                return $address->getLatLng();
            }

            return false;
        }

        if ($this->locationStringAnalyzer->isCityString($keyword)) {
            //dd($keyword);
            $city = $this->findCity($keyword);

            if ($city) {
                return $city->getLatLng();
            }
            
            return false;
        }
    }

    /**
     * Check if given city or state name is in us
     *
     * @param string $cityOrStateString city or state or abv ... 
     * any thing that might be city
     * 
     * @return boolean
     */
    public function isLocatedInUS(string $stateKeyword)
    {
        if (empty($stateKeyword)) {
            return false;
        }
        $stateKeyword = strtoupper($stateKeyword);
        $stateName = $this->getStateAbv($stateKeyword);
        
        if (isset($this->usStatesAbbreviations[$stateName])) {
            return true;
        } 
       
        return false;
    }

    /**
     * Return nearst cities to given lat lng in radius
     * 
     * @param double $lat    [description]
     * @param double $lng    [description]
     * @param double $radius [description]
     * 
     * @return [type]         [description]
     */
    public function findNearestCities($lat, $lng, $radius = 1/69)
    {
        $dbSource = new LocationMongoDB;
        return $this->citiesFinder->getNearestCities($lat, $lng, $dbSource, $radius);
    }

    /**
     * Return city zip codes
     * 
     * @param string $cityState city name
     * 
     * @return array string
     */
    public function getCityZipCodes(string $cityState)
    {
        $dbSource = new LocationDynamoDB;

        $city = $this->locationStringParser->extractCity($cityState);
        $state = $this->locationStringParser->extractState($cityState);
        $zipCodes = $this->citiesFinder->getCityZipCodes($city, $state, $dbSource);
        return $zipCodes;
    }

    /**
    * Return state abreviation for given state keyword (name or abbreviation)
    * 
    * @param string $stateKeyword state name or abbreviation
    * 
    * @return string   state abreviations for given state keyword
    */
    public function getStateAbv(string $stateKeyword)
    {
        $abvs = array_flip($this->usStatesAbbreviations);
        if (array_key_exists($stateKeyword, $abvs)) {
            return $abvs[$stateKeyword];
        } elseif (array_search($stateKeyword, $abvs) !== false) {
            return $stateKeyword;
        }
    }

    /**
    * Return state name for given state keyword (name or abbreviation)
    *
    * @param string $stateKeyword state name or abbreviation
    *
    * @return string   state abreviations for given keyword
    */
    public function getStateName(string $stateKeyword)
    {
        if (array_key_exists(strtoupper($stateKeyword), $this->usStatesAbbreviations)) {
            return $this->usStatesAbbreviations[strtoupper($stateKeyword)];
        } elseif (array_search($stateKeyword, $this->usStatesAbbreviations) !== false) {
            return $stateKeyword;
        } else {
            return "";
        }
    }

    /**
     * Return state name for given area code for example 201 return New Jersey
     * 
     * @param string $areaCode [description]
     * 
     * @return string           [description]
     */
    public function getStateNameByAreaCode($areaCode)
    {
        if (isset($this->usStatesAreaCodes[$areaCode])) {
            return $this->getStateName($this->usStatesAreaCodes[$areaCode]);
        }
    }

    /**
     * Normalize state name if needed
     * (new york city) => New York City, New York
     * 
     * @param string $stateName [description]
     * 
     * @return string            [description]
     */
    public function normalizeStateName($stateName)
    {
        $re = '/((\s|\b)(new york city)(\W|,| |$))/ims';
        preg_match($re, $stateName, $match);
        if (!empty($match[3])) {
            $stateName = "New York City, New York" ;
        }
        return $stateName ;
    }
}
