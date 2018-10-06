<?php

/**
 * CitiesFinder Class
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

use Illuminate\Support\Facades\Cache;
use Skopenow\Location\Classes\LocationDynamoDB;
use Skopenow\Location\Classes\LocationMongoDB;

/**
 * CitiesFinder Class
 *
 * PHP version 7.0
 * 
 * @category Class
 * @package  Location
 * @author   Queen tech <info@queentechsolutions.net>
 * @license  license http://www.queentechsolutions.net/
 * @link     http://www.queentechsolutions.net/
 */
class CitiesFinder implements CitiesFinderInterface
{
    /**
     * [getCity description]
     * 
     * @param string $cityName  [description]
     * @param string $stateName [description]
     * 
     * @return City            [description]
     */
    public function getCity(string $cityName, string $stateName, $dbSource)
    {
        $cityName = trim(strtolower($cityName));
        $stateName = trim(strtolower($stateName));

        if (empty($cityName) || empty($stateName)) {
            return false;
        }

        $cacheKey = 'USPopulations-cache-'.$cityName.','.$stateName;
        $cacheTime = 60*60*24*7; // one week

        //Get Data From Cache
        $results = Cache::get($cacheKey);

        /*if ($results == "empty") {
            return false;
        } elseif ($results != false) {
            return $results;
        }*/

        $cityState = new \ArrayIterator();
        $cityState->append(["cityName"=>$cityName, "stateName"=>$stateName]);
        $data = $dbSource->getCity($cityState);

        if ($data) {
            //cash new data
            Cache::put($cacheKey, $data, $cacheTime);
            return $data;
        }

        Cache::put($cacheKey, 'empty', $cacheTime);
        return false;
    }

    /**
     * [getCityZipCodes description]
     * 
     * @param string $city  [description]
     * @param string $state [description]
     * 
     * @return array        [description]
     */
    public function getCityZipCodes(string $city, string $state, $dbSource)
    {
        $city  = strtolower($city);
        $state  = strtolower($state);
        if (empty($city) || empty($state)) {
            return [];
        }

        $cacheTime = 60*60*24*7;
        $cacheKey = 'getZipCode-cache-'.$city.'-'.$state;
        $ret = null;
        
        if (Cache::has($cacheKey)) {
            $ret = Cache::get($cacheKey);
        }

        $cityStates = new \ArrayIterator();
        $cityStates->append(["cityName"=>$city, "stateName"=>$state]);
        $data = $dbSource->getCityZipCodes($cityStates);

        if($data){
            Cache::put($cacheKey, $data, $cacheTime);
            return $data;
        }
        
        return false;
    }

    /**
     * [getNearestCities description]
     * 
     * @param double $lat    [description]
     * @param double $lon    [description]
     * @param double $radius [description]
     * 
     * @return array         [description]
     */
    public function getNearestCities($lat, $lon, $dbSource, $radius = 1/69)
    {
        $locationData = new \ArrayIterator();
        $locationData->append(["lat"=>$lat, "lon"=>$lon, "radius"=>$radius]);
        $data = $dbSource->getNearestCities($locationData);

        $results = array();
        foreach ($data as $doc) {
            $results[] = strtolower(trim($doc['city']));
        }
        return $results;        
    }
}
