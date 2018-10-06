<?php

if (!function_exists('checkWebSiteCountry')) {
    function checkWebSiteCountry($url, $personCitis, $person, $combination)
    {
        $dominCountryMatch = true;
        foreach ($personCitis as $city) {
            $dominCountryMatch = matchDomainCountryWithUS($url, $city);
            if ($dominCountryMatch) {
                break;
            }
        }

        if ($dominCountryMatch === false) {
            $message = "[websites] domin or email : $url is international we removed it";
            // logging:: $person['id'], $message, $combination);
            return false;
        }
        return true;
    }
}

if (!function_exists('matchDomainCountryWithUS')) {
    function matchDomainCountryWithUS($domain, $location)
    {
        $isLocationInUS = isLocationInUS($location);

        preg_match('/\\.([^\\.]+)$/', $domain, $matches);
        if (empty($matches[1])) {
            return null;
        }

        $dominExt = strtoupper($matches[1]);
        return $isLocationInUS && !isset(loadData('countries_code')[$dominExt]) ?: false;
    }
}

if (!function_exists('isLocationInUS')) {
    function isLocationInUS($location)
    {
        $stateCode = strtoupper(getState($location));
        if (!$stateCode) {
            return null;
        }

        $states_abv = loadData('states_abv');
        return isset($states_abv[$stateCode]) || in_array(ucwords(strtolower($stateCode)), $states_abv) ?: false;
    }
}

if (!function_exists('isWord')) {
    function isWord($word)
    {
        $word = strtolower(trim($word));

        if (!$word) {
            return false;
        }

        if (strpos($word, '@') !== false) {
            $word = stristr($word, '@', true);
        }

        $dbOpj = Yii::app()->DynamoDB->query([
            'TableName' => 'words',
            'KeyConditionExpression' => 'word = :word',
            'ExpressionAttributeValues' => [
                ':word' => ['S' => $word],
            ],
        ]);

        if (is_object($dbOpj)) {
            $wordsFound = $dbOpj->get('Items');
            if (!empty($wordsFound)) {
                return true;
            }
        }

        return false;
    }
}

if (!function_exists('checkWebSiteOwnerInfo')) {
    function checkWebSiteOwnerInfo($domainName, $person, $combination, &$emails = [], &$phones = [])
    {
        $combination['id'] = $combination['id'] ?? 0;
        $info = \SearchApis::getWebSiteOwnerInfo($domainName, $person, $combination['id']);

        if (empty($info['status'])) {
            $message = "domain : $domainName is Available!";
            // logging:: SearchApis::logData($person['id'], $message, $combination);
            return ['name' => false, 'location' => false];
        }

        $checkPrivacy = function ($content) {
            return !stripos($content, 'privacy') === false && stripos($content, 'private') === false ?: false;
        };

        $is_privacy = array_map($checkPrivacy, $info['names']);

        // result from private domain will be rejected according to Eng. marc
        // see domain foddy.net in https://www.skopenow.com/report/r-aTnEe_pWmq8PH0NXNAUv7L32ilXt0BP7oOyPpdyzeiQ
        if (in_array(true, $is_privacy)) {
            $message = "domain : $domainName is privacy!";
            // logging:: SearchApis::logData($person['id'], $message, $combination);
            return ['name' => false, 'location' => false, 'found_location' => false, 'privacy' => true];
        }

        $isNameMatched = true;
        $isLocationMatched = true;
        $foundLocation = false;
        $locationDetails = ['locationDetails' => []];

        if (!empty($info['names'])) {
            $nameDetails = [];
            $isNameMatched = \SearchApis::runNameAnalyzer($person, $combination, $info['names'], $nameDetails);
        }

        if (!empty($info['locations']['city']) && !empty($info['locations']['state'])) {
            $websites_locations = [];

            for ($i = 0; $i < count($info['locations']['city']); $i++) {
                if (!empty($info['locations']['country'][$i])) {
                    $country = trim(strtolower($info['locations']['country'][$i]));
                    if ($country != 'us' && stripos($country, 'united states') === false) {
                        continue;
                    }
                }

                if (!empty($info['locations']['city'][$i]) && !empty($info['locations']['state'][$i])) {
                    $websites_locations[] = $info['locations']['city'][$i] . ', ' . $info['locations']['state'][$i];
                }
            }

            $websites_locations = array_unique($websites_locations);

            if (!empty($websites_locations)) {
                ## using location analyzer for location matching .

                $LocationAnalyzer = new Search\Helpers\LocationAnalyzer();
                $LocationAnalyzer->match_only_search_locations = true;
                $LocationAnalyzer($person, $websites_locations);
                if ($LocationAnalyzer->is_match()) {
                    $locationDetails = $LocationAnalyzer->getBestLocations();
                    $matchScore = @$locationDetails['locationDetails']['matchScore'];
                    if (!empty($matchScore) && (in_array('exct', $matchScore) || in_array('pct', $matchScore))) {
                        $isLocationMatched = true;
                    } else {
                        $isLocationMatched = false;
                    }
                } else {
                    $isLocationMatched = false;
                }
                ## End of location matching .
                // logging:: SearchApis::logData($person['id'], "domain : $domainName \n" .
                //  $LocationAnalyzer->getLog(), $combination);
            }
        }

        if ($isNameMatched && $isLocationMatched) {
            $emails = $info['emails'];
            $phones = $info['phones'];
        }

        $message = "domain : $domainName check status :- ";
        $message .= " location : $isLocationMatched name : $isNameMatched";
        // logging:: SearchApis::logData($person['id'], $message, $combination);

        return [
            'name' => $isNameMatched,
            'location' => $isLocationMatched,
            'found_location' => $foundLocation,
            'location_details' => $locationDetails['locationDetails']
        ];
    }
}

if (!function_exists('multiArrayKeyExists')) {
    function multiArrayKeyExists(array $keys, array $array, bool $findAll = true): bool
    {
        foreach ($keys as $key) {
            if ($findAll && !array_key_exists($key, $array)) {
                //return false if any key not found
                return false;
            }

            if (!$findAll && array_key_exists($key, $array)) {
                //return true if any key found
                return true;
            }
        }
        return $findAll; //if all keys required return true, false if not all
    }
}

if (!function_exists('checkCitySize')) {
    function checkCitySize($city)
    {
        $citySates = array_map('strtolower', (array) $city);

        $locationService = loadService('location');
        $cities = $locationService->findCities($citySates);

        $data = $cities[strtolower($city)];
        if ($data && isset($data['bigCity'])) {
            return $data['bigCity'];
        }
        return true;
    }
}

if (!function_exists('foundInArray')) {
    function foundInArray($needle, array $array)
    {
        $needle = is_array($needle) ? implode(',', $needle) : $needle;
        foreach ($array as $value) {
            $value = is_array($value) ? implode(',', $value) : $value;
            if (stripos($value, $needle) !== false) {
                return true;
            }
        }
        return false;
    }
}
