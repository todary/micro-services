<?php

use Illuminate\Support\Collection;

if (!function_exists('cutEmailFromTest')) {
    function cutEmailFromTest($text)
    {
        $emilEnd = stripos($text, ' ', stripos($text, '@')); //Get the poisition of the first space after email
        $email = trim(substr($text, 0, $emailEnd));

        $spacesCount = substr_count($email, ' ');
        if ($spacesCount) {
            $email = substr(strrchr($email, ' '), 1); //extract email
        }
        if (!!stripos($email, 'who')) {
            $email = preg_replace('/[^a-zA-Z@.0-9_]/', '', $email); //remove anything not an email charachter
            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                return $email;
            }
        }
        return false;
    }
}

if (!function_exists('getState')) {
    function getState($fullcode)
    {
        if (empty($fullcode)) {
            return '';
        }

        $locationService = loadService('location');
        $citySates = new \ArrayIterator([$fullcode]);
        $cities = $locationService->extractState($citySates);
        return $cities[$fullcode];
    }
}

if (!function_exists('getCity')) {
    function getCity($fullcode)
    {
        if (empty($fullcode)) {
            return $fullcode;
        }

        $locationService = loadService('location');
        $citySates = new \ArrayIterator([$fullcode]);
        $cities = $locationService->extractCity($citySates);
        return $cities[$fullcode];
    }
}

if (!function_exists('getStateName')) {
    function getStateName($fullcode)
    {
        $locationService = loadService('location');
        $code = new \ArrayIterator([$fullcode]);
        $output = is_numeric($fullcode) ? $locationService->getStateNameByAreaCode($code) :
        $locationService->getStateName($code);
        return $output[$fullcode];
    }
}

if (!function_exists('arrayExtract')) {
    /**
     * exclude value from an array using the key
     * @param mixed $key
     * @param array &$array
     * @return mixed
     */
    function arrayExtract($key, array &$array)
    {
        $value = $array[$key];
        unset($array[$key]);
        return $value;
    }
}

if (!function_exists('getLatLngOf')) {
    function getLatLngOf($address)
    {
        $output = [];

        $locationService = loadService('location');
        $findedAddress = $locationService->findAddress([$address]);
        $newAddress = $findedAddress[$address];

        if ($newAddress) {
            $output['lat'] = $newAddress['latLng']['lat'];
            $output['lng'] = $newAddress['latLng']['lng'];
            $output['formatted_address'] = $newAddress['title'];
        }

        return array($output);
    }
}

if (!function_exists('vdistance')) {
    function vdistance($lat1, $long1, $lat2, $long2)
    {
        $locationService = loadService('location');
        $firstlatLnginp['lat'] = $lat1;
        $firstlatLnginp['lng'] = $long1;
        $secondlatLnginp['lat'] = $lat2;
        $secondlatLnginp['lng'] = $long2;

        $distance = $locationService->calculateDistance($firstlatLnginp, $secondlatLnginp);

        return $distance['distance'];
    }
}

if (!function_exists('getPersonNickNames')) {
    function getPersonNickNames($perosnId)
    {
        $nickNames = array();
        $datapointService = loadService('dataPoint');
        $datapoint = $datapointService->create();

        $data = $datapoint->loadProgress($perosnId, true, 'nicknames');
        if ($data and count($data)) {
            $arrayOfData = json_decode($data['nicknames_data'], true);
            if (is_array($arrayOfData)) {
                foreach ($arrayOfData as $row) {
                    $nickNames = array_merge($nickNames, $row['names']);
                }
            }
        }
        return array_unique($nickNames);
    }
}

if (!function_exists('getDisplayName')) {
    function getDisplayName()
    {
        $reportService = loadService('reports');
        $report = $reportService->getReport();
        ## In case we searched with username and did not find any name (add the username instead) .
        ## Task #10970 .
        $displayNames = empty($report['names'])? $report['names'] : $report['usernames'];

        return isset($displayNames[0]) ? $displayNames[0] : '';
    }
}

// (not used) Delete this comment if you are going to use this function
if (!function_exists('extractBulkedCollections')) {
    function extractBulkedCollections($collection)
    {
        $newCollection = new Collection;
        foreach ($collection as $values) {
            foreach ($values as $value) {
                $newCollection->push($value);
            }
        }
        return $newCollection;
    }
}
