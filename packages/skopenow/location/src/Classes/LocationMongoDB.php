<?php

/**
 * LocationMongoDB
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
 * LocationMongoDB
 *
 * @category Class
 * @package  Location
 * @author   Queen tech <info@queentechsolutions.net>
 * @license  license http://www.queentechsolutions.net/
 * @link     http://www.queentechsolutions.net/
 */
class LocationMongoDB implements LocationDBSourceInterface
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
		throw new \Exception('getNearestCities Method For DynamoDB');
	}

	/**
     * [getCityZipCodes description]
     * 
     * @param  string $cityName  [description]
     * @param  string $stateName [description]
     * 
     * @return array             [description]
     */
	public function getCityZipCodes(\ArrayIterator $cityStates)
	{
		throw new \Exception('getNearestCities Method For DynamoDB');
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
		$personId = config("state.report_id");

        $data = [];
        foreach ($locationData as $location) {
            $collection = \SearchApis::selectMongoCollection('USZipCodes', $personId);
            if ($collection and $lat and $lon) {
                $results = array();
                $query = array(
                        'loc' => array(
                                '$near'=>array((float)$location['lon'],(float)$location['lat']),
                                '$maxDistance'=>$location['radius']
                        )
                );
                $data[] = $collection->find($query);
            }
        }
        return $data;
	}

    public function getGeoCodeCache(string $address): string
    {
        throw new \Exception('getNearestCities Method For DynamoDB');
    }

    public function setGeoCodeCache(string $address, string $data)
    {
        throw new \Exception('getNearestCities Method For DynamoDB');
    }
}