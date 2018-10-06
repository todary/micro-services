<?php
namespace Skopenow\Matching\Match;

use Skopenow\Matching\Interfaces\MatchInterface;
use Skopenow\Matching\Analyzer\LocationAnalyzer;

class LocationMatch implements MatchInterface
{
    private $city1;
    private $city2;
    private $state1;
    private $state2;
    private $locationDetails = [];
    private $preventAliasing = false;
    private $cityOrstate = false;
    private $onlyState = false;

    public function __construct($person, $combination)
    {
        $this->person = $person;
        $this->combination = $combination;
    }

    public function setCity1(string $city1)
    {
        $this->city1 = $city1;
    }

    public function setCity2(string $city2)
    {
        $this->city2 = $city2;
    }

    public function setState1(string $state1)
    {
        $this->state1 = $state1;
    }

    public function setState2(string $state2)
    {
        $this->state2 = $state2;
    }

    public function setLocationDetails(array &$locationDetails = [])
    {
        $this->locationDetails = $locationDetails;
    }

    public function preventAliasing(bool $preventAliasing)
    {
        $this->preventAliasing = $preventAliasing;
    }

    public function setCityOrState(bool $cityOrstate)
    {
        $this->cityOrstate = $cityOrstate;
    }

    public function setOnlyState(bool $onlyState)
    {
        $this->onlyState = $onlyState;
    }

    public function match()
    {
        // meter to mile
        $m2mi = 0.000621371192;

        $cacheKey = json_encode(["isLocationMatch", $this->city1, $this->state1, $this->city2, $this->state2]);
        $cacheTime = 60 * 60; //seconds

        /*if (\App::environment('local')) {
            // The environment is local
            $cacheTime = 60 * 60 * 24; // 1 day
        }*/

        $this->city2 = strip_tags($this->city2);
        $this->state2 = strip_tags($this->state2);

        // Alias for new york
        // Mr.Mark said that, when you find NYC or Manhattan consider them as Alias to New york, New york
        if (!$this->preventAliasing) {
            if (
                stripos($this->city2, 'nyc') !== false ||
                stripos($this->city2, 'manhattan') !== false ||
                stripos($this->city2, 'big apple')!== false
            ) {
                $this->city2 = 'new york, new york';
            }

            if (
                stripos($this->state2, 'nyc') !== false ||
                stripos($this->state2, 'manhattan') !== false ||
                 stripos($this->state2, 'big apple') !== false
            ) {
                $this->state2 = 'new york, new york';
            }

            if (
                stripos($this->city1, 'nyc') !== false ||
                stripos($this->city1, 'manhattan') !== false ||
                stripos($this->city1, 'big apple')!== false
            ) {
                $this->city1 = 'new york, new york';
            }

            if (
                stripos($this->state1, 'nyc') !== false ||
                stripos($this->state1, 'manhattan') !== false ||
                stripos($this->state1, 'big apple') !== false
            ) {
                $this->state1 = 'new york, new york';
            }

            if (strtolower($this->state2) == 'other') {
                $this->state2 = '';
            }

            if (strtolower($this->city2) == 'other') {
                $this->city2 = '';
            }
        }

        if (!($this->person)) {
            $this->person = [];
        }
        if (!isset($this->person['id'])) {
            $this->person['id'] = 0;
        }

        if (!($this->combination)) {
            $this->combination = [];
        }
        if (!isset($this->combination['id'])) {
            $this->combination['id'] = 0;
        }
        if (!isset($this->combination['urlProfile'])) {
            $this->combination['urlProfile'] = "";
        }

        $ex_syn = "Person: {$this->person['id']}, Comb: {$this->combination['id']}, Profile: {$this->combination['urlProfile']},\n";
        $ex_syn .= "  Request location match of [" . (strlen($this->city1) == 1?$this->state1:$this->city1) . "] and [".(strlen($this->city2) == 1 ? $this->state2 : $this->city2) . "] ";
        $ex_syn .= ($this->cityOrstate?'Check City and State Or Only State':null) . "\n";

        $locations1 = [];
        $locations2 = [];

        // case 3slea bet2ked an el state2 feha usa
        if (
            getState($this->city1) &&
            in_array(strtolower($this->state2), ["us", "usa", "united states"]) &&
            $this->city2 != '-'
        ) {
            $st_code = getState($this->city1);
            $ex_syn .= "    Check only big State [$this->city1]\n";
            $st = ucwords(strtolower($st_code));

            $st2 = ucwords(strtolower($this->state1));
            $ck = false;
            // begeb el state name bel el state code
            if (isset(loadData('states_abv')[strtoupper($st_code)])) {
                //echo "<br>test State <br> ". $st."<br>---<br>";
                $ck = true;
            } elseif (in_array($st, loadData('states_abv'))) {
                //echo "<br>2222 State <br> ". $st."<br>---<br>";
                $ck = true;
            }

            if ($ck) {
                // IF State is United State return 2
                $ex_syn .= "        [$this->state2] This State is Big State \n";
                // SearchApis::logData($this->person['id'],$ex_syn, $this->combination);
                return 2;
            } else {
                $ex_syn .= "        Not Match with [USA , US , United States] \n";
            }
        }

        $this->city1 = preg_replace('#,\s*(us|usa|united states)$#i', "", $this->city1);
        $this->city1 = trim(str_ireplace(array("city", "country", "state","greater","area"), "", $this->city1));
        $this->state1 = preg_replace('#,\s*(us|usa|united states)$#i', "", $this->state1);
        $this->state1 = trim(str_ireplace(array("city", "country", "state","greater","area"), "", $this->state1));
        $this->city2 = preg_replace('#,\s*(us|usa|united states)$#i', "", $this->city2);
        $this->city2 = trim(str_ireplace(array("city", "country", "state","greater","area"), "", $this->city2));
        $this->state2 = preg_replace('#,\s*(us|usa|united states)$#i', "", $this->state2);
        $this->state2 = trim(str_ireplace(array("city", "country", "state","greater","area"), "", $this->state2));


        $is_state_1 = count($this->isState($this->city1)) || count($this->isState($this->state1));
        $is_state_2 = count($this->isState($this->city2)) || count($this->isState($this->state2));


        $onlyCityMatch = null;
        if ($is_state_1 && $is_state_2) {
            $stateOnly = false;
            if (strpos($this->city1,',') === false) {
                $stateOnly = true;
            } else {
                // The code blow to check if the city match with state location which returned from profile, if match , will be match as city and state
                // Mr.Mark said to me to do that.
                // Related with task #10968 , profile twitter.com/KatrinaPierson
                if (strpos($this->city2,',') === false) {
                    $_cityTmp = $this->getCity($this->city1);
                    $onlyCityMatch = $this->isStateMatch($_cityTmp,$this->state2,$stateOnly);
                }
            }
            $stateMatch = $this->isStateMatch($this->state1,$this->state2,$stateOnly);
        } else {
            $stateMatch = null;
        }


        if ($stateMatch !== false) {

            // check Location by similator text..
            $_city1 = strtolower($this->getCity(trim($this->city1)));

            if($_city1 == "") $_city1 = $this->city1;
            if($_city1 == "-") $_city1 = '';
            $_state1 = strtolower($this->getStateName(trim($this->state1)));

            $_city2 = strtolower($this->getCity(trim($this->city2)));

            $_state2 = strtolower($this->getStateName(trim($this->state2)));

            $ex_syn .= "   Checking location match of $_city1,$_state1 and $_city2,$_state2\n";

            // lw m3rfsh egeb city name and state name
            if (($_city1 == "" && $_state1 == "") || ($_city2 == "" && $_state2 == "")) {
                $ex_syn .= "   Missing data!, assuming matched.\n";

                // TODO
                // SearchApis::logData($this->person['id'], $ex_syn, $this->combination);
                // Yii::app()->cache->set($cacheKey,true,300);
                return true;
            }


            $doesMatch = false;

            if ($_city2 && $_state2) {

                similar_text($_city1, $_city2, $percent1);
                similar_text($_state1, $_state2, $percent2);
                //$this->locationDetails[] = 'st';
            } else {
                similar_text($_state1, $_city2 . $_state2, $percent1);
                $percent2 = 80;
                if ($_city1 == '') {
                    $percent2 = null;
                }
                $matchedOnlyState = true;
            }

            $percent = ($percent1 + $percent2 * 2) / 3;
            if (is_null($percent2)) {
                $percent = $percent1;
            }
            $ex_syn .= "   Match percent: $percent%\n";

            if ($percent >= 85) {
                // Check state
                // first Case if state is alone on string ..
                if(!getState($this->city2)){
                    $st = ucwords(strtolower($this->city2));
                    $st2 = ucwords(strtolower($this->state2));
                    if (in_array($st, loadData('states_abv'))) {
                        $this->locationDetails[] = 'st';
                    } elseif(isset(loadData('states_abv')[strtoupper($st)])) {
                        $this->locationDetails[] = 'st';
                    } elseif(in_array($st2, loadData('states_abv'))) {
                        $this->locationDetails[] = 'st';
                    } elseif(isset(loadData('states_abv')[strtoupper($st2)])) {
                        $this->locationDetails[] = 'st';
                    }

                } elseif(!getState($this->state2)) {
                    $st = ucwords(strtolower($this->state2));
                    if (in_array($st, loadData('states_abv'))) {
                        $this->locationDetails[] = 'st';
                    } elseif(isset(loadData('states_abv')[strtoupper($st)])) {
                        $this->locationDetails[] = 'st';
                    }

                } else {

                    $st = ucwords(strtolower($this->getCity($this->state2)));
                    $checkDelemeter = strpos($this->state2, ',');
                    if (!$checkDelemeter&&in_array($st, loadData('states_abv'))) {
                        $this->locationDetails[] = 'st';
                    } elseif (!$checkDelemeter && isset(loadData('states_abv')[strtoupper($st)])){
                        $this->locationDetails[] = 'st';
                    } else {
                        if (!$this->onlyState) {
                            // calc near city and exact city
                            if ($percent >= 90) {
                                $this->locationDetails[] = 'exct';
                                $this->locationDetails['dist'] = 0;
                            } else {
                                $this->locationDetails[] = 'pct';
                                $this->locationDetails['dist'] = 0;
                            }
                        }
                        // second case, if city with state
                        $this->locationDetails[] = 'st';
                    }
                }
                if (isset($matchedOnlyState)) {
                    $key1 = array_search('exct', $this->locationDetails);
                    $key2 = array_search('pct', $this->locationDetails);
                    if ($key1 !== false || $key2 !== false ) {
                        if (is_int($key1)) {
                            unset($this->locationDetails[$key1]);
                        }
                        if (is_int($key2)) {
                            unset($this->locationDetails[$key2]);
                        }
                    }
                    if (!empty($onlyCityMatch)) {
                        $this->locationDetails[] = 'exct';
                        $this->locationDetails['dist'] = 0;
                    }
                }
                $doesMatch = true;
                if (!empty($matchedOnlyState)) {
                    $doesMatch = 2;
                }
            }

            $ex_syn .= ($doesMatch) ? "   Matched.\n" : "   Not matched.\n";

            if ($doesMatch) {
                // TODO
                // SearchApis::logData($this->person['id'], $ex_syn, $this->combination);
                // Yii::app()->cache->set($cacheKey,$doesMatch,300);

                return $doesMatch;
            }

            $ex_syn .= "\n  Match Location by Lat and Long:\n";

            $loadLocationsPerson = $this->person['city'] ? array_filter(explode(';', trim($this->person['city']))) : array("");
            $checkAdditinalLocation = function ($city1) use ($loadLocationsPerson) {
                $return = false;
                foreach ($loadLocationsPerson as $key => $_l) {
                    $_statePerson =  $this->getStateName($_l);
                    $_stateAdditinalProfile = $this->getStateName($this->city1);
                    if ($_statePerson == $_stateAdditinalProfile) {
                        $return = true;
                    }
                }
                return $return;
            };

            $distance_threshold = config('distance_match_threshold');
            if (!$distance_threshold) {
                $distance_threshold = 32187;
            }

            if ($this->city1 == '-') {
                $this->city1 = $this->state1;
            }
            if ($this->city2 == '-') {
                $this->city2 = $this->state2;
            }


            $locationService = loadService('location');
            $foundedAddresses = $locationService->findAddress([$this->city1]);
            // $locations1 = $foundedAddresses[$this->city1];
            $locations1 = $foundedAddresses;


            if (!empty($locations1)) {
                if (strtolower($this->city2) == strtolower("New York, New York")) {
                    $this->city2 = "New York, NY";
                }
                $foundedAddresses = $locationService->findAddress([$this->city2]);
                // $locations2 = $foundedAddresses[$this->city2];
                $locations2 = $foundedAddresses;
            }


            if (!empty($locations1) && !empty($locations2)) {
                $states1FromGoogle = [];
                $states2FromGoogle = [];

                $minDist = null;
                $maxDist = null;

                $sumDists = 0;
                $countDists = 0;
                $avgDist = null;
                $analyzer = new LocationAnalyzer;
                foreach ($locations1 as $loc1) {
                    foreach ($locations2 as $loc2) {
                        $dist = $analyzer->vdistance($loc1["latLng"]['lat'], $loc1["latLng"]['lng'], $loc2["latLng"]['lat'], $loc2["latLng"]['lng']);

                        $minDist = min($minDist, $dist);
                        if (is_null($minDist)) {
                            $minDist = $dist;
                        }

                        $maxDist = max($maxDist, $dist);
                        if (is_null($maxDist)) {
                            $maxDist = $dist;
                        }

                        $sumDists += $dist;
                        $countDists++;

                        $states1FromGoogle[] = strtolower($this->getStateName($loc1['title']));
                        $states2FromGoogle[] = strtolower($this->getStateName($loc2['title'])) ;
                    }
                }

                if ($countDists) {
                    $doesMatch = false;

                    $avgDist = $sumDists / $countDists;

                    $ex_syn .= "   minimum distance: $minDist m\n";
                    $ex_syn .= "   average distance: $avgDist m\n";
                    $ex_syn .= "   maximum distance: $maxDist m\n";


                    if ($minDist <= $distance_threshold) {
                        // Check state
                        // first Case if state is alone on string ..
                        if (!getState($this->city2)) {
                            $st = ucwords(strtolower($this->city2));
                            if (in_array($st, loadData('states_abv'))) {
                                $locationDetails[] = 'st';
                            } elseif(isset(loadData('states_abv')[strtoupper($st)])) {
                                $locationDetails[] = 'st';
                            }

                        } elseif(!getState($this->state2)) {
                            $st = ucwords(strtolower($this->state2));
                            if (in_array($st, loadData('states_abv'))) {
                                $locationDetails[] = 'st';
                            } elseif (isset(loadData('states_abv')[strtoupper($st)])) {
                                $locationDetails[] = 'st';
                            }

                        } elseif (!$this->getCity($this->state2)){
                            $st = ucwords(strtolower($this->state2));
                            if (in_array($st, loadData('states_abv'))) {
                                $locationDetails[] = 'st';
                            } elseif(isset(loadData('states_abv')[strtoupper($st)])) {
                                $locationDetails[] = 'st';
                            }
                        } else {
                            $st = ucwords(strtolower($this->getCity($this->state2)));
                            // added check Delemeter cause when facebook has location new york, new york was take (st) score and there a city with state
                            // related with task #10357 for this profile https://www.facebook.com/wishnie
                            $checkDelemeter = strpos($this->state2,',');
                            if (!$checkDelemeter && in_array($st, loadData('states_abv'))) {
                                $locationDetails[] = 'st';
                            } elseif (
                                !$checkDelemeter &&
                                isset(loadData('states_abv')[strtoupper($st)])
                            ) {
                                $locationDetails[] = 'st';
                            } else {
                                if (!$this->onlyState) {
                                    // calc near city and exact city
                                    if ($minDist <= 50) {
                                        $locationDetails[] = 'exct';
                                    } else {
                                        $locationDetails[] = 'pct';
                                    }
                                }
                                // second case, if city with state
                                $locationDetails[] = 'st';
                            }
                        }

                        if (
                            isset($this->combination['is_relative']) &&
                            $this->combination['is_relative'] == 1
                        ) {
                            if ($checkAdditinalLocation($this->city1)) {
                                $locationDetails['dist'] = $minDist * $m2mi;
                            }
                        } elseif (!$this->onlyState){
                            $locationDetails['dist'] = $minDist * $m2mi;
                        }
                        $doesMatch = true;
                    }

                    if (
                        !$doesMatch &&
                        (
                            !strpos($this->city2,',') &&
                            !strpos($this->state2,',') &&
                            !$this->cityOrstate
                        )
                    ){
                        $googleStatesCount = 0;
                        $googleStatesMatch = 0;
                        foreach ($states1FromGoogle as $state1FromGoogle) {
                            foreach ($states2FromGoogle as $this->state2FromGoogle) {
                                $ex_syn .= "  Google states matching [$state1FromGoogle] with [$this->state2FromGoogle]\n";
                                $googleStatesCount++;
                                if ($state1FromGoogle == $state2FromGoogle) {
                                    $googleStatesMatch++;
                                }
                            }
                        }

                        if ($googleStatesCount) {
                            $ex_syn .= "  Google states count = $googleStatesCount, matches = $googleStatesMatch\n";

                            $googleStatesMatchPercent = 100 * $googleStatesMatch / $googleStatesCount;
                            $ex_syn .= "  Google states match percent $googleStatesMatchPercent %\n";

                            if ($googleStatesMatchPercent >= 80) {
                                $doesMatch = true;
                                $locationDetails[] = 'st';
                            }
                        }
                    }

                    if (!$doesMatch) {
                        $ex_syn .= ($doesMatch) ? "   Matched.\n" : "   Not matched.\n";

                        if (
                            strpos($this->city1,',') &&
                            !strpos($this->city2,',') &&
                            !strpos($this->state2,',') &&
                            !$this->cityOrstate
                        ) {
                            $this->city1 = strtolower($this->getStateName(trim($this->city1)));
                            $this->state1 = strtolower($this->getStateName(trim($this->state1)));
                            // i added this parameter because was adding [exct > parametar score] and there just state
                            // related with googlePlus issue in task #10357
                            $this->onlyState = true;

                            ## Osama:: disable till ask Mr.mark about this case : related with task #9305 ..
                            /*if(SearchApis::getStateName(trim($this->city2))){
                                $this->city2 = strtolower(SearchApis::getStateName(trim($this->city2)));
                                $this->state2 = strtolower(SearchApis::getStateName(trim($this->state2)));
                            }*/
                            ## // ..

                            // SearchApis::logData($this->person['id'], $ex_syn, $this->combination);
                            return $this->isLocationMatch($this->person, $this->combination, $this->city1, $this->state1, $this->city2, $this->state2, $locationDetails, false, $this->onlyState);

                        } elseif (
                            !strpos($this->city1,',') &&
                            strpos($this->city2,',') &&
                            strpos($this->state2,',')
                        ) {
                            // for case if input location has state only
                            // related with problem location here #10400
                            // https://beta2383.skopenow.com/report/r-u9VPnIU4o0rvK9tPGBdyCVob8Krf0ihnkZIlDkehBNo#list
                            $this->city2 = strtolower($this->getStateName(trim($this->city2)));
                            $this->state2 = strtolower($this->getStateName(trim($this->state2)));
                            return $this->match();
                        }
                    }
                    $ex_syn .= ($doesMatch) ? "   Matched.\n" : "   Not matched.\n";
                    // TODO
                    // Yii::app()->cache->set($cacheKey,$doesMatch,300);
                }
            } else {
                $ex_syn .= "No data from lat long!";
            }
            // TODO
            // SearchApis::logData($this->person['id'], $ex_syn, $this->combination);
            return $doesMatch;

        } elseif ($stateMatch === false) {
            $ex_syn .= "state : $this->state1 Not matched state : $this->state2.\n";
            // TODO
            // SearchApis::logData($this->person['id'], $ex_syn, $this->combination);
            // Yii::app()->cache->set($cacheKey,false,300);
            return false;
        }
        // TODO
        // Yii::app()->cache->set($cacheKey,$doesMatch,300);
        return $doesMatch;
    }

    private function isState($state)
    {
        $stateData = [];
        $state = strtolower(getState($state, true));
        $replace = array('greater', 'area', 'city', 'state');
        $stateName = strtolower(getStateName(trim(str_replace($replace, '', $state)), true));
        $states = array_map('strtolower', loadData('states_abv'));

        if (in_array($stateName, $states)) {
            $stateData['code'] = strtolower(array_search($stateName, $states));
            $stateData['name'] = $stateName;
        } elseif (array_key_exists($state, $states)) {
            $stateData['code'] = $state;
            $stateData['name'] = $states[$state];
        }
        return $stateData;
    }

    private function isStateMatch($state1, $state2, $stateOnly = false)
    {
        $state1 = strtolower($state1);
        $state2 = strtolower($state2);
        $replace = ['greater', 'area', 'city', 'state'];
        $stateName1 = strtolower($this->getStateName(trim(str_replace($replace, '', $state1)),true));
        $stateName2 = strtolower($this->getStateName(trim(str_replace($replace, '', $state2))));
        $stateData = $this->isState($state2);

        if ($state1 == $state2) {
            return true;
        } elseif (
            ($stateName1 && $stateName2) &&
            ($stateName1 == $stateName2)
        ) {
            return true;
        } elseif (
            ($stateName1 && !$stateName2) &&
            ($stateName1 == $state2)
        ) {
            return true;
        } elseif (count($stateData)) {
            if (getState($state1) == $stateData['code']) {
                return true;
            } elseif ($stateName1 == $stateData['name']) {
                return true;
            }
            return false;
        } elseif ($stateOnly) {
            return false;
        }
        return null;
    }

    private function getCity($address)
    {
        if (empty($address)) {
            return $address;
        }

        $locationService = loadService("location");
        $citySates = new \ArrayIterator([$address]);
        $cities = $locationService->extractCity($citySates);
        return $cities[$address];
    }

    private function getStateName($address)
    {
        $locationService = loadService("location");
        $code = new \ArrayIterator([$address]);
        $output = $locationService->getStateNameByAreaCode($code);
        return $output[$address];
    }

    public function checkAddressExactMatch($address, $title, $descrip)
    {
        $status = false ;
        if (!empty($address)) {
            $address = preg_quote($address, "/");
            $re = "/\\b(" . $address . ")\\b/i";
            // check exact match in title and description .
            if (
                preg_match($re, $title, $match) ||
                preg_match($re, $descrip, $match)
            ) {
                $status = true ;
            }
        }
        return $status;
    }
}
