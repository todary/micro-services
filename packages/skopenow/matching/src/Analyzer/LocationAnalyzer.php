<?php
/**
 * class Location Analyzer .
 * @author Ahmed Samir , Ahmed Fouad, Mohammed Attya
 *
 */

namespace Skopenow\Matching\Analyzer;

use Skopenow\Matching\Services\ReportService;
use Skopenow\Matching\Interfaces\AnalyzerInterface;

class LocationAnalyzer implements AnalyzerInterface
{

    /**
     * [$personLocations all person locations from prograss data]
     * @var array
     */
    private $personLocations = [];

    /**
     * [$locations locations will matched with person locations]
     * @var array
     */
    private $locations = [];

    /**
     * [$fullLocationsDetails description]
     * @var [array] $key = location name , $value = ['city' => '' , 'state' => '']
     */
    private $fullLocationsDetails = [];

    /**
     * [$locationsTextSimilar array of locations that have precentage > 85 in similar text]
     * @var $array = ['person location name ']['loaction name '] => [persentage , locationDetails]
     */
    private $locationsCityStateMatch= [];

    /**
     * [$locationsTextSimilar array of locations that have precentage > 85 in similar text]
     * @var $array = ['person location name ']['loaction name '] => [persentage , locationDetails]
     */
    private $locationsStateMatch= [];

    /**
     * [$exc_location store exct location]
     * @var array
     */
    private $exct_locations = [];

    /**
     * [$addressesMap store all peroson addresses ]
     * @var array $key = location name $value = $object from prograess data
     */
    private $addressesMap = [];

    /**
     * [$locationsMatch locations with exct or pct match ]
     * @var array
     */
    private $locationsMatch = [];

    /**
     * [$locationMissingLatLong store locations that doesn't have lat and long in progress data ]
     * @var array
     */
    private $locationMissingLatLong = [];

    /**
     * [$MapLocationsResults contain distance values , locations name have the same key for distance]
     * @var array using to calculate min distance with pct and st
     */
    private $MapLocationsResults = [
        'pct' => ['dist' => [] , 'locations' => [] , 'locations_key' => []],
        'st'  => ['dist' => [] , 'locations' => [] , 'locations_key' => []],
    ];

    /**
     * [$is_exct_terminate if match found exact terminate the match and return ]
     * @var boolean
     */
    private $is_exct_terminate = false;

    /**
     * [$bestLocation description]
     * @var array
     */
    private $bestLocation = [];

    /**
     * [$log description]
     * @var string
     */
    private $log = "\n";

    /**
     * [$cached_locations description]
     * @var array
     */
    private $cached_locations = [];

    /**
     * [$assume_match description]
     * @var boolean
     */
    private $assume_match = false;

    /**
     * [$locations_rejected description]
     * @var array
     */
    private $locations_rejected =[] ;

    /**
     * [$locations_inserted locations add from user to found]
     * @var array
     */
    private $locations_inserted = [];

    /**
     * [$exct_locations_small small locations and exect]
     * @var array
     */
    private $exct_locations_small = [];

    /**
     * [$match_only_input_locations description]
     * @var boolean
     */
    public $match_only_search_locations = false;

    /**
     * [$existing_locations get all locations in prograss data]
     * @var array
     */
    public $existing_locations = [];


    /**
     * [__invoke call the location analyzer class from the istantiated object]
     * @param  \Persons $person   [the person object]
     * @param  [array] $location [the found location]
     * @return [array]            [the location match detailes]
     */
    private $report;

    public function __construct(ReportService $report)
    {
        $this->report = $report;
    }

    public function __invoke($person, $locations){

        if (!$this->beforeMatchCriteria($locations)) {
            return ;
        }
        $this->person = $person;
        $personLocations = $this->getAllPersonLocations($person);
        $this->personLocations = $this->preparingLocations($personLocations);
        $this->locations = $this->preparingLocations($locations);
        if (empty($this->personLocations)) {
            return;
        }
        $this->checkTextSimilartyLocations();
        if (empty($this->exct_locations_small)) {
            $this->checkDistancLocations($person['id'],null);
        }
        $this->UserLocationsSmallCities_Criteria();


        // TODO
        /*
        if (\SearchApis::$testing) {
            echo '<pre>'.$this->log.'</pre>';
        }
        */
    }

    /**
     * [runLocationAnalyzer compare between two lcations to get similarty ]
     * @param  array  $locations1 [description]
     * @param  array  $locations2 [description]
     * @return [boolean]             [description]
     */
    public function runLocationAnalyzer(array $locations1, array $locations2)
    {
        if (
            !$this->beforeMatchCriteria($locations1) ||
            !$this->beforeMatchCriteria($locations2)
        ) {
            return;
        }
        $this->personLocations = $this->preparingLocations($locations1);
        $this->locations = $this->preparingLocations($locations2);

        $this->checkTextSimilartyLocations();

        if (empty($this->exct_locations_small)) {
            $this->checkDistancLocations();
        }

        // TODO
        /**
        if (\SearchApis::$testing) {
            echo '<pre>'.$this->log.'</pre>';
        }
        */

    }

    /**
     * [getBestLocations get locations depending on exect , pct (dist or percent) , state (dist , pct )]
     * @return [type] [description]
     */
    public function getBestLocations()
    {
        $this->log .= "[Location Analyzer] Begin Finding Best Location \n" ;
        /**
         * check first if there is exct locations
         */

        if (!empty($this->exct_locations)) {
            $data = [];
            $location1 = "";
            $location2 = "";
            if (!$this->getExectSmallLocations($location1, $location2)) {
                $location1 = $this->exct_locations[0]['location1'];
                $location2 = $this->exct_locations[0]['location2'];
            } else {
                $this->log .= "[Location Analyzer] Finding small cities in ($location1 and $location2) \n";
            }
            $data =  $this->preparingResult($location1, $location2);
            $this->bestLocation = $data;
            $this->log .= "[Location Analyzer] Finding exct Location  between($location1 and $location2) \n"
                        . "[Location Analyzer] End Finding Best Location \n ".print_r($data,true) ;
            return $this->bestLocation;
        }

        /**
         * check if there is pct location
         * get the minimum dist
         */
        if (!empty($this->MapLocationsResults['pct']['dist'])) {
            $data = [];
            $minDist = min($this->MapLocationsResults['pct']['dist']) ;
            $keys = array_keys($this->MapLocationsResults['pct']['dist'], $minDist);
            $data = $this->MapLocationsResults['pct']['locations'][$keys[0]];
            $this->bestLocation =  $this->preparingResult($data['location1'] ,$data['location2']);
            $this->log .= "[Location Analyzer] Finding pct Location  between({$data['location1']} and {$data['location2']}) \n"
                        . "distance = $minDist \n"
                        . "[Location Analyzer] End Finding Best Location \n " ;
            return $this->bestLocation;
        }


        /**
         * get min distance for state match
         */
        if (!empty($this->MapLocationsResults['st']['dist'])) {
            $data = [];
            $minDist = min($this->MapLocationsResults['st']['dist']) ;
            $keys = array_keys($this->MapLocationsResults['st']['dist'],$minDist);
            $data = $this->MapLocationsResults['st']['locations'][$keys[0]];
            $this->bestLocation =  $this->preparingResult($data['location1'] ,$data['location2']);
            $this->log .= "[Location Analyzer] Finding st Location  between({$data['location1']} and {$data['location2']}) \n"
                        . "distance = $minDist \n"
                        . "[Location Analyzer] End Finding Best Location \n " ;
            return $this->bestLocation;
        }

        $this->log .= "[Location Analyzer] There's No Matching Location\n End Finding Best Location \n\n" ;
        // if no location match
        return null;

    }

    /**
     * [is_match if there is location match]
     * @return boolean [description]
     */
    public function isMatch() : bool
    {
        if (!empty($this->locations_rejected)) {
            $this->log .= "\n[Location Analyzer] ***FINAL*** There's No Matching Location(s) --Rejected-- \n\n" ;
            return false;
        }

        if (!empty($this->exct_locations) || $this->assume_match) {
            $this->log .= "\n[Location Analyzer] ***FINAL*** There's Matching or assuming match Location(s) \n\n" ;
            return true;
        }

        if (
            !empty($this->locationsMatch) ||
            !empty($this->locationsStateMatch)
        ){
            $this->log .= "\n[Location Analyzer] ***FINAL*** There's Matching Location(s) \n\n" ;
            return true;
        }

        $this->log .= "\n[Location Analyzer] ***FINAL*** There's No Matching Location(s) \n\n" ;
        return false;
    }


    /**
     * [getLog print Log for locations match]
     * @return [type] [description]
     */
    public function getLog()
    {
        return $this->log;
    }

    /**
     * [searchMatchedLocation get info of location(s) had matched]
     * @param  [type] $locations [string or array of locations]
     * @return [type]            [description]
     */
    public function searchMatchedLocation($locations)
    {
        $this->stringToArray($locations);

        $result = [];

        foreach ($locations as $location) {
            $location = ucwords(strtolower(trim( $location )));

            if (isset($this->fullLocationsDetails[$location])) {
                $this->getLatLOngForLocations($location);
                $result[] = $this->preparingResult($location);
            }
        }
        return $result;
    }

    /**
     * [is_rejected small city with small city is rejected locations]
     * @param  [type]  $location1 [description]
     * @param  [type]  $location2 [description]
     * @return boolean            [description]
     */
    private function is_rejected($location1, $location2)
    {
        $city1 = $this->fullLocationsDetails[$location1];
        $city2 = $this->fullLocationsDetails[$location2];

        if (!$city1['bigCity'] && !$city2['bigCity'] && !$city1['stateOnly'] && !$city2['stateOnly'] )
        {
            $this->log .= "[Location Analyzer] Location Rejected small city with small city not match ($location1 - $location2) \n\n" ;

            $this->fullLocationsDetails[$location1]['rejected'] = true;
            $this->fullLocationsDetails[$location2]['rejected'] = true;
            $this->fullLocationsDetails[$location1]['rejectedWith'][] = $location2;
            $unique = array_unique($this->fullLocationsDetails[$location1]['rejectedWith']);
            $this->fullLocationsDetails[$location1]['rejectedWith'] = $unique;

            $this->locations_rejected[$location1] = $location2;
            return true;
        }
        return false;
    }

    /**
     * [runLocationAnalyzer make common filter and check for locations]
     * @param  [array] $Locations [description]
     * @return [array]            [description]
     */
    private function preparingLocations(array $locations)
    {
        if (!$locations) {
            return [];
        }

        $locationsTemp = [] ;
        $locations = $this->checkComplexLocation($locations);
        foreach ($locations as $location) {
            if (!is_string($location) || empty($location)) {
                continue ;
            }
            $location = strip_tags($location);
            $location = $this->checkLocationAlias($location);
            $location = $this->filterLocation($location);

            $location = ucwords(strtolower(trim($location)));

            if (!is_string($location) || empty($location)) {
                continue ;
            }

            $location = $this->setFullLocationsDetails($location);

            $locationsTemp[] = $location;
        }
        $locationsTemp = array_unique(array_filter($locationsTemp));
        if (empty($locationsTemp)) {
            $this->assume_match = true;
        }
        return $locationsTemp;
    }
    /**
     * [checkComplexLocation check if the location is complex like San Francisco and New York ].
     * @param  [type] $location [the location you want to check for alias in]
     * @return [type]           [the same location or array of locations]
     */
    private function checkComplexLocation($locations)
    {
        foreach ($locations as $location) {
            if (stripos($location, 'and')!== false) {
                $newLocations=explode('and', $location);
                $locations=array_merge($locations, $newLocations);
            }
        }
        return $locations;
    }

    /**
     * [checkLocationAlias check the locations for aliases to convert them ].
     * @param  [type] $location [the location you want to check for alias in]
     * @return [type]           [the same location after convert the alias]
     */
    private function checkLocationAlias($location)
    {
        if (
            stripos($location, 'nyc') !== false ||
            stripos($location, 'manhattan') !== false ||
            stripos($location, 'big apple') !== false ||
            stripos($location, "new york city usa") !== false
        ){
            $location = 'new york, new york';
        }
        return $location ;
    }

    /**
     * [MapLocationsResults description]
     * @param string $location1 [description]
     * @param string $location2 [description]
     */
    private function MapLocationsResults(string $location1, string $location2, $dist = 0, $score = 'st')
    {
        // dd(['location1' => $location1, 'location2' => $location2, 'dist' => $dist, 'score' => 'st']);
        if (isset($this->MapLocationsResults[$score]['locations_key'][$location1][$location2])) {
            $key = $this->MapLocationsResults[$score]['locations_key'][$location1][$location2];
            $this->MapLocationsResults[$score]['dist'][$key] = $dist;
        } else {
            $this->MapLocationsResults[$score]['dist'][] = $dist;
            $key = count($this->MapLocationsResults[$score]['dist']) - 1;
            $this->MapLocationsResults[$score]['locations'][$key] = ['location1' => $location1, 'location2' => $location2];
            $this->MapLocationsResults[$score]['locations_key'][$location1][$location2] = $key;
        }
    }

    /**
     * [beforeMatchCriteria description]
     * @param  array  $value [description]
     * @return boolean        if false will stop match
     */
    private function beforeMatchCriteria(&$locations)
    {
        if (!is_array($locations)) {
            // validate $locations
            if (is_string($locations)) {
                $locations = [$locations];
            } else {
                $locations = [];
            }
        }

        if (empty(array_filter($locations))) {
            $this->assume_match = true;
            return false;
        }
        return true;
    }

    /**
     * [UserLocationsSmallCities_Criteria check if all users locations enterd in search is small cities and rejected ]
     * @return boolean false if all Locations are small cities and rejected
     */
    private function UserLocationsSmallCities_Criteria()
    {
        $return = true;
        if (empty($this->exct_locations_small)) {
            foreach ($this->locations_inserted as $location) {
                if (
                    array_key_exists($location,$this->locations_rejected) &&
                    !$this->fullLocationsDetails[$location]['bigCity']
                ) {
                    $return = false;
                } elseif ($this->fullLocationsDetails[$location]['bigCity']) {
                    $return = true;
                    break;
                }
            }
        } else {
            $this->log .= "[Location Analyzer] Location already have match with small city ,so skipping small city reject case.  \n";
        }

        if (!$return) {
            $this->log .= "[Location Analyzer] Reject All Locations because all entered locations from user are small cities and mismatch with another small city  \n" ;
        } else {
            unset($this->locations_rejected);
            $this->log .= "[Location Analyzer] Not all Entered Location are small cities so any rejected location will be ignore  \n" ;
        }
        return $return;
    }

    /**
     * [stringToArray if the location is string put it into array]
     * @return [type] [description]
     */
    private function stringToArray(&$locations)
    {
        if (!is_array($locations)) {
            // validate $locations
            if (is_string($locations)) {
                $locations = [$locations];
            } else {
                $locations = [];
            }
        }
        $locations = array_filter($locations);
    }

    /**
     * [filterLocation description]
     * @param  [type] $location [description]
     * @return [type]           [description]
     */
    private function filterLocation($location)
    {
        $location = preg_replace('#,\s*(us|usa|united states)$#i', "", $location);

        // remove city from filter because there is cities in Dynmo (Plant City , ....)
        $location = trim(str_ireplace(array( "country", "state", "greater", "area"), "", $location));
        return $location;
    }

    /*
     * [getAllPersonLocations load all locations name from prograss data]
     * @param  \Persons $person [description]
     * @return [array]           [locations names]
     */
    private function getAllPersonLocations($person)
    {
        $location = [];
        // $addresses = $this->loadAllLocations($person);
        // foreach ($addresses as $address) {
        //     $locationName = \searchApis::getLocationNameFromAddressArray($address);
        //     $locationName = ucwords(strtolower(trim($locationName))) ;
        //     $this->addressesMap[$locationName] = $address ;
        //     $location[] = $locationName;
        // }
        $location = $this->report->getAllPersonLocations($person);
        $this->existing_locations = $location;
        $this->locations_inserted =  $person['cities'];//explode(";", strtolower($person['city']));
        $this->locations_inserted = $this->preparingLocations($this->locations_inserted);

        $location = array_unique(array_merge($location, $this->locations_inserted));

        if ($this->match_only_search_locations) {
            $location = $this->locations_inserted;
        }
        return $location;
    }

    /**
     * [setFullLocationsDetails set $fullLocationsDetails with location name as key and [ city , state ] as values ]
     * @param string $location [description]
     */
    private function setFullLocationsDetails(string $location)
    {
        $locationService = loadService("location");
        $code = new \ArrayIterator([$location]);
        $address = $locationService->findAddress([$location]);
        $output = $locationService->extractState($code);
        $state = strtoupper(trim($output[$location]));
        $cities = $locationService->extractCity($code);
        $city = ucwords(strtolower(trim($cities[$location])));
        if (empty($city)) {
            $city = $address[$location]['city'];
        }

        if (empty($state)) {
            $state = $address[$location]['state'];
        }
        $state_code = array_key_exists($state, loadData('states_abv'));
        if ($state_code) {
            $state = strtoupper(loadData('states_abv')[$state]);
        }
        if ($state_code && !empty($city)) {
            $location = ucwords(strtolower(trim($city . ", " . $state)));
        } elseif(!empty($state) && !empty($city)) {
            $location = ucwords(strtolower(trim($city . ", " . $state)));
        } elseif (!empty($state)) {
            $location = ucwords(strtolower(trim($state)));
        }

        $this->fullLocationsDetails[$location] = [
            'city' => $city,
            'state' => $state,
            'stateOnly' => empty($city),
            'assumingMatch' => empty($state) && empty($city),
            'bigCity' => $this->isBigCity($location, $city, $state),
            'locationInfo' => $this->getLatLongPersonLocations($location),
            'rejected' => false,
            'rejectedWith' => [],
        ];
        return $location;
    }

    /**
     * [setExctSmallCities set 2 locations matched and small (exect) ]
     * @param [type] $location1 [description]
     * @param [type] $location2 [description]
     */
    private function setExectSmallLocations($location1, $location2)
    {
        $city1 = $this->fullLocationsDetails[$location1];
        $city2 = $this->fullLocationsDetails[$location2];
        if (
            !$city1['bigCity'] &&
            !$city2['bigCity'] &&
            !$city1['stateOnly'] &&
            !$city2['stateOnly']
        ) {
            $this->exct_locations_small = [
                'location1' => $location1,
                'location2' => $location2
            ];
        }
    }

    /**
     * [getExectSmallLocations get 2 locations matched and small (exect)]
     * @param  [type] $location1 [description]
     * @param  [type] $location2 [description]
     * @return [type]            [description]
     */
    private function getExectSmallLocations(&$location1, &$location2)
    {
        if (!empty($this->exct_locations_small)) {
            $location1 = $this->exct_locations_small['location1'] ;
            $location2 = $this->exct_locations_small['location2'] ;
            return true;
        }
        return false;
    }

    /**
     * [isBigCity check if the city is big or small]
     * @param  [string]  $location [description]
     * @param  [string]  $city     [description]
     * @param  [string]  $state    [description]
     * @return boolean           [description]
     */
    public function isBigCity(string $location, $city = null, $state = null)
    {
        if (isset($this->addressesMap[$location]['bigCity'])) {
            return $this->addressesMap[$location]['bigCity'];
        }
        // if (is_null($city) || is_null($state) || empty($city) || empty($state)) {
        //     $locationService = loadService("location");
        //     $citySates = new \ArrayIterator([$location]);
        //     $output = $locationService->getStateNameByAreaCode($citySates);
        //     dd($citySates);
        //     $state = ucwords(strtolower(trim($output[$location])));

        //     $cities = $locationService->extractCity($citySates);
        //     $city = ucwords(strtolower(trim($cities[$location])));
        // }
        $locationService = loadService("location");
        $cities = $locationService->findCities([$location]);
        $data = $cities->getArrayCopy()[$location];
        if (empty($data['name']) && !empty($city) && !empty($state)) {
            $location = [trim(str_ireplace("city", "", $city)) . ", ".$state];
            $cities = $locationService->findCities($location);
            $data = $cities->getArrayCopy()[$location[0]];
        }
        if (empty($data['name'])) {
            return true;
        }
        return $data['size'];
    }

    /**
     * [getLatLongPersonLocations get lat and lng for location in progress data]
     * @param  string $location [description]
     * @return [type]           [description]
     */
    private function getLatLongPersonLocations(string $location)
    {
        $lat = null;
        $lng = null;
        $fullAdress = null;
        $locationData = [];
        if (
            isset($this->addressesMap[$location]['locationLat']) &&
            isset($this->addressesMap[$location]['locationLng'])
        ) {
            $lat = $this->addressesMap[$location]['locationLat'];
            $lng = $this->addressesMap[$location]['locationLng'] ;
            if (isset($this->addressesMap[$location]['fullAddress'])) {
                $fullAddress = $this->addressesMap[$location]['fullAddress'];
            }
            $locationData[] = [
                'lat' => $lat,
                'lng' => $lng,
                'city' => $this->addressesMap[$location]['city']??'',
                'state' => $this->addressesMap[$location]['state']??'',
                'fullAddress' => $fullAddress,
            ];
            return $locationData;
        }
        $this->locationMissingLatLong[] = $location;
        return null;


    }

    /**
     * [getLatLOngForMissingLocations get lat and lng from google api for locations ]
     * @return [type] [description]
     */
    private function getLatLOngForMissingLocations()
    {
        if (!empty($this->locationMissingLatLong)) {
            $locationService = loadService('location');
            $foundedAddresses = $locationService->findAddress($this->locationMissingLatLong);
            foreach ($this->locationMissingLatLong as $key => $location) {
                $locationLatLong = $foundedAddresses[$location];
                $locationData = [];
                $lat = null;
                $lng = null;
                $fullAdress = null ;
                if (!empty($locationLatLong)) {
                    $lat = $locationLatLong['latLng']['lat'];
                    $lng = $locationLatLong['latLng']['lng'];
                    $fullAddress = $locationLatLong['title'];
                    $city = $locationLatLong['city'];
                    $state = $locationLatLong['state'];
                    $country = $locationLatLong['country'];
                    $zipCode = $locationLatLong['zipCode'];
                    $locationData[] = [
                        'lat' => $lat,
                        'lng' => $lng,
                        'fullAddress' => $fullAddress,
                        'city' => $city,
                        'state' => $state,
                        'country' => $country,
                        'zipCode' => $zipCode
                    ];

                    if (empty($lat) || empty($lng)) {
                        $this->log .= "[Location Analyzer] Can not find lat/lng for $fullAddress  \n\n" ;
                    }

                } else {
                    $this->log .= "[Location Analyzer] Can not find lat/lng for " . print_r($location, true) . "  \n\n" ;
                }
                $this->fullLocationsDetails[$location]['locationInfo'] = $locationData;
                unset($this->locationMissingLatLong[$key]);
            }
        }
    }

    /**
     * [getLatLOngForLocations get lat and lng from google api for locations ]
     * @return [type] [description]
     */
    private function getLatLOngForLocations($locations)
    {
        $this->stringToArray($locations);
        $locationService = loadService('location');
        $foundedAddresses = $locationService->findAddress($locations);
        if (!empty($locations)) {
            foreach ($locations as $key => $location) {
                if (empty($foundedAddresses[$location])) {
                    continue;
                }
                $locationLatLong = $foundedAddresses[$location];
                $locationData = [];
                $lat = null;
                $lng = null;
                $fullAdress = null ;
                if (!empty($locationLatLong)) {
                    $lat = $locationLatLong['latLng']['lat'];
                    $lng = $locationLatLong['latLng']['lng'];
                    $fullAddress = $locationLatLong['title'];
                    $city = $locationLatLong['city'];
                    $state = $locationLatLong['state'];
                    $country = $locationLatLong['country'];
                    $zipCode = $locationLatLong['zipCode'];
                    $locationData[] = [
                        'lat' => $lat,
                        'lng' => $lng,
                        'fullAddress' => $fullAddress,
                        'city' => $city,
                        'state' => $state,
                        'country' => $country,
                        'zipCode' => $zipCode
                    ];

                    if (empty($lat) || empty($lng)) {
                        $this->log .= "[Location Analyzer] Can not find lat/lng for $fullAddress  \n\n" ;
                    }
                }
                $this->fullLocationsDetails[$location]['locationInfo'] = $locationData;
            }
        }
    }

    /**
     * [getDistanceInfo get the distance between two location]
     * @param  [type] $location1 [description]
     * @param  [type] $location2 [description]
     * @return [type]            [description]
     */
    private function getDistanceInfo($location1, $location2)
    {
        $locationInfo_1 = $this->fullLocationsDetails[$location1]['locationInfo'];
        $locationInfo_2 = $this->fullLocationsDetails[$location2]['locationInfo'];

        if (is_null($locationInfo_1) || is_null($locationInfo_2)) {
            return null;
        }

        $states1FromGoogle = [];
        $states2FromGoogle = [];


        $minDist = null;
        $maxDist = null;

        $sumDists = 0;
        $countDists = 0;
        $avgDist = null;

        foreach ($locationInfo_1 as $loc1) {
            foreach ($locationInfo_2 as $loc2) {
                if (empty($loc1['lat']) || empty($loc1['lng']) || empty($loc2['lat']) || empty($loc2['lng'])) {
                    continue;
                }
                $dist = $this->vdistance($loc1['lat'], $loc1['lng'], $loc2['lat'], $loc2['lng']);

                if (is_null($minDist)) {
                    $minDist = $dist;
                } else {
                    $minDist = min($minDist, $dist);
                }

                if (is_null($maxDist)) {
                    $maxDist = $dist;
                } else {
                    $maxDist = max($maxDist, $dist);
                }

                $sumDists += $dist;
                $countDists++;

                $states1FromGoogle[] = $loc1['state'];
                $states2FromGoogle[] = $loc2['state'];
            }
        }

        if ($minDist === null) {
            return null;
        }

        return [
            'minDist' => $minDist,
            'maxDist' => $maxDist,
            'sumDists' => $sumDists,
            'countDists' => $countDists,
            'avgDist' => $avgDist,
            'states1FromGoogle' => $states1FromGoogle,
            'states2FromGoogle' => $states2FromGoogle,
        ];
    }

    /**
     * [preparingResult prepare array with result for 2 locations]
     * @param  string $location1 [description]
     * @param  string $location2 [description]
     * @return array
     * [
     * 'loactions' => [ loaction1name , location2name ] ,
     * 'location1name' => array
     * [
     *      'city' => string ,
     *      'state' => string ,
     *      'bigCity' => boolean ,
     *      'lat' => float ,
     *      'lng' => float ,
     *      'fullAdress' => string ,
     *  ] ,
     *
     * 'location2name' => array
     * [
     *      'city' => string ,
     *      'state' => string ,
     *      'bigCity' => boolean ,
     *      'lat' => float ,
     *      'lng' => float ,
     *      'fullAdress' => string ,
     *  ] ,
     *
     *  'locationDetails' => array
     *  [
     *      'dist' => number (if location checked with google)
     *      'matchScore' => [ exct | pct , st ]
     *      '$matchTyp' =>  number 1 =>  BigCityWithBigCity | 2 => SmallCityWithBigCity | 3  => SmallCityWithSmallCity
     *      '$matchTypeName' => string BigCityWithBigCity | SmallCityWithBigCity | SmallCityWithSmallCity
     *  ]
     *
     * ]
     */
    private function preparingResult(string $location1, string $location2 = '')
    {
        if (empty($location1)) {
            return [];
        }
        $data = [];
        $location1_data = $this->fullLocationsDetails[$location1];
        $data['locations'][] = $location1;
        $data[$location1] = [
            'city' =>  $location1_data['city'],
            'state' => $location1_data['state'],
            'bigCity' => $location1_data['bigCity'],
            'lat' => (!is_null($location1_data['locationInfo']) && !empty($location1_data['locationInfo'])) ?
                        $location1_data['locationInfo'][0]['lat'] : null,
            'lng' => (!is_null($location1_data['locationInfo']) && !empty($location1_data['locationInfo'])) ?
                        $location1_data['locationInfo'][0]['lng'] : null,
            'fullAdress' => (!is_null($location1_data['locationInfo']) && !empty($location1_data['locationInfo'])) ?
                        $location1_data['locationInfo'][0]['fullAddress'] : null,
        ];

        if (!empty($location2)) {
            $location2_data = $this->fullLocationsDetails[$location2];
            $data['locations'][] = $location2 ;

            $data[$location2] = [
                'city' =>  $location2_data['city'],
                'state' => $location2_data['state'],
                'bigCity' => $this->fullLocationsDetails[$location1]['bigCity'],
                'lat' => (!is_null($location2_data['locationInfo']) && !empty($location2_data['locationInfo'])) ?
                        $location2_data['locationInfo'][0]['lat'] : null,
                'lng' => (!is_null($location2_data['locationInfo']) && !empty($location2_data['locationInfo'])) ?
                         $location2_data['locationInfo'][0]['lng'] : null,
                'fullAdress' => (!is_null($location2_data['locationInfo']) && !empty($location2_data['locationInfo'])) ?  $location2_data['locationInfo'][0]['fullAddress'] : null,

            ] ;
        }

        if (isset($this->fullLocationsDetails[$location1][$location2]) &&
            isset($this->fullLocationsDetails[$location1][$location2]['locationDetails']) &&
            !is_null($this->fullLocationsDetails[$location1][$location2]['locationDetails'])
        ) {
            $score = [];
            $locationDetails =  $this->fullLocationsDetails[$location1][$location2]['locationDetails'] ;

            if (in_array('exct', $locationDetails)) {
                $score[] = 'exct';
            } elseif (in_array('pct', $locationDetails)) {
                $score[] = 'pct';
            }

            if (in_array('st', $locationDetails)) {
                $score[] = 'st';
            }

            $matchType = 0;
            $matchTypeName = '';

            /**
             * if big city with big city  $location2_data['bigCity']  matchType = 1
             */
            if ($location1_data['bigCity'] && $location2_data['bigCity']) {
                $matchType = 1 ;
                $matchTypeName = 'BigCityWithBigCity';
            }
            /**
             * if small city with big city matchType = 2
             */
            elseif (
                (!$location1_data['bigCity'] &&  $location2_data['bigCity']) ||
                ($location1_data['bigCity'] &&  !$location2_data['bigCity'])
            ) {
                $matchType = 2 ;
                $matchTypeName = 'SmallCityWithBigCity';
            }
            /**
             * if small city with small city matchType = 3
             */
            elseif (!$location1_data['bigCity'] && !$location2_data['bigCity']) {
                $matchType = 3;
                $matchTypeName = 'SmallCityWithSmallCity';
            }

            $data['locationDetails'] = [
                    'dist' => $locationDetails['dist'] ,
                    'matchScore' => $score,
                    'matchType' => $matchType,
                    'matchTypeName' => $matchTypeName,
                ];
        } else {
            $data['locationDetails'] = null;
        }
        return $data;
    }

    private function checkDistancLocations($person_id = null, $combination_id = null)
    {
        $this->log .= "[Location Analyzer] Begin Check Distance Match.......\n\n" ;

        $m2mi = 0.000621371192;

        $this->getLatLOngForMissingLocations();

        $distance_threshold = config('distance_match_threshold');

        $distance_threshold_big_city = config('distance_match_threshold_big_city');

        $distance_threshold_small_city = config('distance_match_threshold_small_city');

        if (!$distance_threshold) {
            $distance_threshold = 32187;
        }

        foreach ($this->personLocations as $personLocation) {
            foreach ($this->locations as $location) {

                $this->log .= "[Location Analyzer] Matching ( $personLocation - $location ) ... \n" ;

                $st1 = $this->fullLocationsDetails[$location]['state'];
                $st2 = $this->fullLocationsDetails[$personLocation]['state'];
                if (
                    $st1!= $st2 &&
                    in_array($st1, loadData('states_abv')) &&
                    in_array($st2, loadData('states_abv'))
                ) {
                    $this->log .= "[Location Analyzer] different state between ( $personLocation - $location ) ... \n" ;
                    continue;
                }

                if (isset($this->cached_locations[$personLocation][$location])) {
                    if ($this->cached_locations[$personLocation][$location]) {
                        $this->log .= "[Location Analyzer] Locations is Cached \n" ;
                        //continue;
                    }
                }

                $locationData = $this->fullLocationsDetails[$location];
                $personLocationData = $this->fullLocationsDetails[$personLocation];
                $data = [];

                $data = $this->getDistanceInfo($personLocation, $location);
                if (is_null($data)) {
                    break;
                }


                $states1FromGoogle = $data['states1FromGoogle'];
                $states2FromGoogle = $data['states2FromGoogle'];

                $minDist = $data['minDist'];
                $maxDist = $data['maxDist'];

                $sumDists = $data['sumDists'];
                $countDists = $data['countDists'];

                $this->log .= "[Location Analyzer] Matching ( $personLocation - $location )\n"
                           ."minDist = $minDist \n"
                           ."maxDist = $maxDist \n"
                           ."countDists = $countDists \n";


                if ($countDists) {

                    $avgDist = $sumDists / $countDists;

                    $data['avgDist'] = $avgDist;

                    // change distance_threshold for small city match
                    $distance_threshold = $distance_threshold_big_city;
                    if (
                        !$this->fullLocationsDetails[$personLocation]['bigCity'] &&
                        !$this->fullLocationsDetails[$location]['bigCity']
                    ) {
                        $distance_threshold = $distance_threshold_small_city;
                        $this->log .= "[Location Analyzer] ( $personLocation - $location ) small cities limit distance $distance_threshold \n";
                    }

                    if (!$distance_threshold) {
                        $distance_threshold = 32187;
                    }

                    if ($minDist <= $distance_threshold) {

                        if ($minDist <= 7500) {
                            $this->exct_locations[] = ['location1' => $personLocation , 'location2' => $location] ;
                            $this->setExectSmallLocations($personLocation,$location);

                            $data['locationDetails'][] = 'exct';
                            $data['locationDetails']['dist'] = $minDist*$m2mi;
                            $data['locationDetails'][] = 'st';


                            $this->locationsMatch[$personLocation][$location] = [
                                'locationDetails' => $data['locationDetails']
                            ] ;

                            $this->log .= "[Location Analyzer] Matching ( $personLocation - $location ) locationDetails = "
                                           .print_r($data['locationDetails'],true)." \n\n";

                        } else {
                            if (!$this->is_rejected($personLocation,$location)) {
                                if ($this->fullLocationsDetails[$location]['stateOnly']) {
                                    $this->MapLocationsResults($personLocation,$location,$minDist*$m2mi,'st');
                                } else {
                                    $data['locationDetails'][] = 'pct';
                                    $this->MapLocationsResults($personLocation,$location,$minDist*$m2mi,'pct');
                                }

                                $data['locationDetails']['dist'] = $minDist*$m2mi;
                                $data['locationDetails'][] = 'st';

                                $this->locationsMatch[$personLocation][$location] = [
                                    'locationDetails' => $data['locationDetails']
                                ];

                                $this->log .= "[Location Analyzer] Matching ( $personLocation - $location ) locationDetails = "
                                               .print_r($data['locationDetails'],true)." \n\n";
                            }
                        }
                    } else {
                        $googleStatesCount = 0;
                        $googleStatesMatch = 0;

                        foreach ($states1FromGoogle as $state1FromGoogle) {
                            foreach ($states2FromGoogle as $state2FromGoogle) {
                                $googleStatesCount++;
                                if ($state1FromGoogle==$state2FromGoogle) {
                                    $googleStatesMatch++;
                                }
                            }
                        }

                        if ($googleStatesCount) {
                            $googleStatesMatchPercent = 100 * $googleStatesMatch / $googleStatesCount;

                            if (
                                $googleStatesMatchPercent >= 80 &&
                                !$this->is_rejected($personLocation, $location)
                            ) {
                                $data['locationDetails']['dist'] = $minDist * $m2mi;
                                $data['locationDetails'][] = 'st';
                                $this->MapLocationsResults($personLocation, $location, $minDist * $m2mi, 'st');
                                $this->locationsStateMatch[$personLocation][$location] = [
                                    'locationDetails' => $data['locationDetails']
                                ];

                                $this->log .= "[Location Analyzer] Matching ( $personLocation - $location ) locationDetails = "
                                       .print_r($data['locationDetails'],true)." \n\n";
                            }
                        }
                    }
                }
                if (!empty($data)) {
                    $this->fullLocationsDetails[$personLocation][$location] = $data;
                    $this->setCache($personLocation,$location);
                }
                //check if exact return is_exct_terminate
                if ($this->is_exct_terminate) {
                    if(!empty($this->exct_locations))
                    {
                        $this->log .= "[Location Analyzer] Found exct Locations ( $personLocation - $location )....\n End Check Distance Match....... \n\n" ;
                        return;
                    }
                }
            }
        }
        $this->log .= "[Location Analyzer]  End Check Distance Match.......\n\n" ;

    }

    /**
     * [checkTextSimilartyLocations check there text similar person locations]
     * @return [void]
     */
    private function checkTextSimilartyLocations()
    {
        $fullLocationsDetails = $this->fullLocationsDetails;
        $this->log .= "[Location Analyzer] Begin Check Text Match.......\n\n" ;

        foreach ($this->personLocations as $personLocation) {
            foreach ($this->locations as $location) {
                $this->log .= "[Location Analyzer] Matching $personLocation with $location \n" ;
                $st1 = $this->fullLocationsDetails[$location]['state'];
                $st2 = $this->fullLocationsDetails[$personLocation]['state'];
                if (
                    $st1 != $st2 &&
                    in_array($st1, loadData('states_abv')) &&
                    in_array($st2, loadData('states_abv'))
                ) {
                    $this->log .= "[Location Analyzer] different state between ( $personLocation - $location ) ... \n" ;
                    continue;
                }

                $cach = $this->getCache($personLocation, $location);

                if ($cach) {
                    // if get exct from cach exit
                    if ($cach === 1) {
                        $this->log .= " End Check Text Match....... \n\n" ;
                        // return;
                    } elseif ($cach === 2) {
                        // continue;
                    }
                }


                $locationData = $this->fullLocationsDetails[$location] ;
                $personLocationData = $this->fullLocationsDetails[$personLocation];
                $data = [];

                // first check if location is not assuming match
                if (!$locationData['assumingMatch']) {
                    // second check if location have city and state
                    if (
                        !empty($locationData['state']) &&
                        !empty($locationData['city'])
                    ) {
                        $this->log .= "[Location Analyzer] Matching ( $personLocation - $location ) have city & state \n" ;

                        similar_text($locationData['state'], $personLocationData['state'], $state_percent);
                        similar_text($locationData['city'], $personLocationData['city'], $city_percent );
                        $percent = ($city_percent + $state_percent * 2) / 3;
                        $data['city_percent'] =  $city_percent ;
                        $data['state_percent'] =  $state_percent ;
                        $data['percent'] = $percent;

                        $this->log .= "[Location Analyzer] Matching ( $personLocation - $location ) percent =  $percent \n" ;

                        if ($percent >= 85) {
                            $data['locationDetails'] = [];
                            $data['locationDetails']['dist'] = 0;
                            $data['locationDetails'][] = 'st';

                            if ($percent >= 90) {
                                $this->exct_locations[] = ['location1' => $personLocation, 'location2' => $location] ;
                                $data['locationDetails'][] = 'exct';
                                $this->setExectSmallLocations($personLocation,$location);
                            } else {
                                $data['locationDetails'][] = 'pct';
                                $this->MapLocationsResults($personLocation,$location,0,'pct');
                            }

                            $this->locationsMatch[$personLocation][$location] = [
                                'percent' => $percent,
                                'locationDetails' => $data['locationDetails']
                            ];

                            $this->log .= "[Location Analyzer] Matching ( $personLocation - $location ) locationDetails = "
                                       .print_r($data['locationDetails'],true)." \n";

                        }

                        $this->log .="\n";
                    } elseif ($locationData['stateOnly'] === true) {
                        // if location have only state
                        $this->log .= "[Location Analyzer] Matching ( $personLocation - $location ) have state Only \n" ;

                        similar_text($locationData['state'], $personLocationData['state'] , $state_percent );
                        $percent = (80 + $state_percent * 2) / 3;
                        $data['city_percent'] = false ;
                        $data['state_percent'] = $state_percent;
                        $data['percent'] = $percent;

                        $this->log .= "[Location Analyzer] Matching ( $personLocation - $location ) percent =  $percent \n" ;

                        if ($percent >= 85) {
                            $data['locationDetails']['dist'] = 0;
                            $data['locationDetails'][] = 'st';
                            $this->MapLocationsResults($personLocation, $location,0, 'st');
                            $this->locationsStateMatch[$personLocation][$location] = [
                                'percent' => $percent,
                                'locationDetails' => $data['locationDetails']
                            ];

                            $this->log .= "[Location Analyzer] Matching ( $personLocation - $location ) locationDetails = "
                                       .print_r($data['locationDetails'],true)." \n";
                        }

                        $this->log .="\n";
                    } else {
                        // no city or state
                        $this->log .= "[Location Analyzer] Matching ( $personLocation - $location ) no city no state \n\n" ;
                    }

                    if (!empty($data)) {
                        // dump($location, $personLocation, $fullLocationsDetails, $data);
                        $fullLocationsDetails[$personLocation][$location] = $data;
                        // dump($location, $personLocation, $fullLocationsDetails, $data);
                    }
                }
                // end if(!$locationData['assumingMatch'])

                //check if exact return is_exct_terminate
                if ($this->is_exct_terminate) {
                    if (!empty($this->exct_locations)) {
                        $this->log .= "[Location Analyzer] Found exct Locations ( $personLocation - $location )....\n End Check Text Match....... \n\n" ;
                        // return;
                    }
                }
                $this->fullLocationsDetails = $fullLocationsDetails;
            }
        }
        $this->log .= "[Location Analyzer]  End Check Text Match.......\n\n" ;
    }

    /**
     * [setCache store best location in cach]
     * @param [type] $location1 [description]
     * @param [type] $location2 [description]
     */
    private function setCache($location1, $location2)
    {
        $cacheKey = json_encode(array("LocationAnalyzer", $location1, $location2));
        $data = json_encode($this->fullLocationsDetails[$location1]);
        \Cache::put($cacheKey, $data, 60 * 24);
    }

    /**
     * [getCache if return 1 mean exct if 2 maen pct or st if false  there is no cach]
     * @param  [type] $location1 [description]
     * @param  [type] $location2 [description]
     * @return [type]            [description]
     */
    private function getCache($location1, $location2)
    {
        return;
        $cacheKey = json_encode(array("LocationAnalyzer",$location1,$location2));
        $data = \Cache::get($cacheKey);
        if ($data)
        {
            $location = json_decode($data, true);
            $this->fullLocationsDetails[$location1] = $location;
            $this->cached_locations[$location1][$location2] = true;
            $loc_rejected = $this->fullLocationsDetails[$location1]['rejected'];
            $loc_rejectedwith = $this->fullLocationsDetails[$location1]['rejectedWith'];

            if ($loc_rejected && in_array($location2, $loc_rejectedwith)) {
                $this->log .=  "\n " . print_r($this->fullLocationsDetails[$location1],true);
                $this->is_rejected($location1,$location2);
            }
            $locationDetails = null;
            if (isset($location[$location2]['locationDetails'])) {
                $locationDetails = $location[$location2]['locationDetails'];
            }

            if (!is_null($locationDetails)) {
                if (in_array('exct', $locationDetails)) {
                    $this->log .= "[Location Analyzer] Found exct Locations in Cach ( $location1  - $location2 )....\n";
                    $this->log .= "[Location Analyzer] distance = " . $locationDetails['dist'] ."\n" ;

                    $this->exct_locations[] = ['location1' => $location1 , 'location2' => $location2] ;
                    $this->locationsMatch[$location1][$location2] = [
                        'dist' => $locationDetails['dist'] , 'locationDetails' => $locationDetails
                    ];
                    return 1 ;
                } elseif (in_array('pct', $locationDetails)) {
                    $this->log .= "[Location Analyzer] Found pct Locations in Cach ( $location1  - $location2 )....\n";
                    $this->log .= "[Location Analyzer] distance = " . $locationDetails['dist'] ."\n" ;

                    $this->MapLocationsResults($location1,$location2,$locationDetails['dist'],'pct');
                    $this->locationsMatch[$location1][$location2] = [
                        'dist' => $locationDetails['dist'],
                        'locationDetails' => $locationDetails
                    ];

                    return 2;
                } else {
                    if (in_array('st', $locationDetails)) {
                        $this->log .= "[Location Analyzer] Found st Locations in Cach ( $location1 - $location2 )....\n";
                        $this->log .= "[Location Analyzer] distance = " . $locationDetails['dist'] ."\n";
                        $this->MapLocationsResults($location1,$location2,$locationDetails['dist'],'st');
                        $this->locationsStateMatch[$location1][$location2] =
                                    ['dist' => $locationDetails['dist'] , 'locationDetails' => $locationDetails ] ;
                        return 2;
                    }
                }
            }
        }
        return false;
    }

    public function vdistance($lat1, $long1, $lat2, $long2, $earthRadius = 6371000)
    {
        $locationService = loadService("location");
        $firstlatLnginp['lat'] = $lat1;
        $firstlatLnginp['lng'] = $long1;
        $secondlatLnginp['lat'] = $lat2;
        $secondlatLnginp['lng'] = $long2;
        $distance = $locationService->calculateDistance($firstlatLnginp, $secondlatLnginp);
        return $distance["distance"];
    }
    /**
     * [loadAllLocations the new on load all the locations from the progress data mean all the locations found].
     * @param  [object] $person [the person object or typically an array contain index "id" the person id]
     * @return [array]         [all the locations found for that search]
     */
    public function loadAllLocations($person)
    {
        $locations = $this->report->loadAllLocations($person);
        return array_filter($locations);
        //searchApis::loadLocationsPerson($person);
        /*$locations = [];
        $added = $locations;
        // $sql = "SELECT * FROM `progress_data` WHERE `person_id` = {$person['id']} and type ='addresses'" ;
        //$addresses = Yii::app()->db->createCommand($sql)->queryAll();
        $criteria = new \Search\Helpers\Bridges\BridgeCriteria();
        $criteria->compare("person_id", $person['id']);
        $criteria->compare('type', "addresses");

        $prog_bridge = new \Search\Helpers\Bridges\DataPointBridge($person['id']);
        $addresses = $prog_bridge->getAll($criteria);
        foreach ($addresses as $addressArray) {
            if (!empty($addressArray['data'])) {
                $addressData = $addressArray['data'];
                if (!empty($addressData["locationName"])) {
                    if (!in_array($addressData['locationName'], $added)) {
                        $added[] = $addressData['locationName'];
                        $locations[] = $addressData;
                    }
                }
            }
        }*/
    }
}
