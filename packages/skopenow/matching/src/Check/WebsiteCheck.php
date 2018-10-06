<?php
namespace Skopenow\Matching\Check;

use App\Models\BannedDomains;

class WebsiteCheck
{
    public function checkWebSiteOwnerInfoApi(
        $search_result,
        $person,
        $combination,
        &$emails = [],
        &$phones = []
    )
    {
        if (empty($search_result)) {
            return [];
        }

        $isNameMatched = false;
        $isLocationMatched = false;
        $foundLocation = false;
        $names = [];
        $locations = [];

        // names
        if (isset($search_result->registrant_contact->full_name)) {
            $names[] = $search_result->registrant_contact->full_name;
        }

        if (isset($search_result->administrative_contact->full_name)) {
            $names[] = $search_result->administrative_contact->full_name;
        }

        if (isset($search_result->technical_contact->full_name)) {
            $names[] = $search_result->technical_contact->full_name;
        }

        if (isset($search_result->billing_contact->full_name)) {
            $names[] = $search_result->billing_contact->full_name;
        }

        $checkPrivacy = function ($content) {
            if (
                stripos($content, "privacy") === false &&
                stripos($content, "private") === false
            ) {
                return false;
            }
            return true;
        };

        $check_emails = function($string) {
            $pattern = "/(who|domain|customer|service|whois|registry|proxy|private|godaddy|registrar|gandi|hostmaster|privacy)/i";
            if (preg_match($pattern, $string) == true) {
                return false;
            }
            return true;
        };

        $is_privacy = array_map($checkPrivacy, $names);

        // result from private domain will be rejected according to Eng. marc
        // see domain foddy.net in https://www.skopenow.com/report/r-aTnEe_pWmq8PH0NXNAUv7L32ilXt0BP7oOyPpdyzeiQ
        if (in_array(true, $is_privacy)) {
            $message = "Whoxy Api domain : " . $search_result->domain_name . " is privacy!";
            // TODO
            // SearchApis::logData($person['id'],$message,$combination);

            return ['name'=> false, 'location'=> false, 'found_location'=> false , 'privacy' => true];
        }

        // locations
        if (
            isset($search_result->registrant_contact->city_name) &&
            isset($search_result->registrant_contact->state_name)
        ) {
            $locations[] = $search_result->registrant_contact->city_name . ', ' . $search_result->registrant_contact->state_name;
        }

        if (
            isset($search_result->administrative_contact->city_name) &&
            isset($search_result->administrative_contact->state_name)
        ) {
            $locations[] = $search_result->administrative_contact->city_name . ', ' . $search_result->administrative_contact->state_name;
        }

        if (
            isset($search_result->technical_contact->city_name) &&
            isset($search_result->technical_contact->state_name)
        ) {
            $locations[] = $search_result->technical_contact->city_name . ', ' . $search_result->technical_contact->state_name;
        }

        if (
            isset($search_result->billing_contact->city_name) &&
            isset($search_result->billing_contact->state_name)
        ) {
            $locations[] = $search_result->billing_contact->city_name . ', ' . $search_result->billing_contact->state_name;
        }

        if (!empty($locations)) {
            $foundLocation = true;
        }
        $runAnalyzer = new RunAnalyzer($this->report);
        if ($names) {
            $nameDetails = [];
            $isNameMatched = $runAnalyzer->runNameAnalyzer($person, $combination, $names, $nameDetails);
        }

        $locationDetails = ['locationDetails' => []];
        if (!empty($locations)) {
            $locations = array_unique($locations);
            // using location analyzer for location matching .

            $LocationAnalyzer = new LocationAnalyzer;
            $LocationAnalyzer->match_only_search_locations = true;
            $LocationAnalyzer($person, $locations);
            $isLocationMatched = false;
            if ($LocationAnalyzer->is_match()) {
                $locationDetails = $LocationAnalyzer->getBestLocations();
                if (
                    !empty($locationDetails['locationDetails']['matchScore']) &&
                    (
                        in_array("exct", $locationDetails['locationDetails']['matchScore']) ||
                        in_array("pct",$locationDetails['locationDetails']['matchScore'])
                    )
                ) {
                    $isLocationMatched = true;
                }

            }
            // End of location matching .
            // SearchApis::logData ( $person ['id'], $LocationAnalyzer->getLog() ,$combination);
        }

        $emails_temp = [];
        if ($isNameMatched && $isLocationMatched) {
            if (
                isset($search_result->registrant_contact->email_address) &&
                $check_emails($search_result->registrant_contact->email_address)
            ) {
                $emails_temp['registrant_contact'] = $search_result->registrant_contact->email_address;
            }

            if (
                isset($search_result->administrative_contact->email_address) &&
                $check_emails($search_result->administrative_contact->email_address)
            ) {
                $emails_temp['administrative_contact'] = $search_result->administrative_contact->email_address;
            }

            if (
                isset($search_result->technical_contact->email_address) &&
                $check_emails($search_result->technical_contact->email_address)
            ) {
                $emails_temp['technical_contact'] = $search_result->technical_contact->email_address;
            }

            if (
                isset($search_result->billing_contact->email_address) &&
                $check_emails($search_result->billing_contact->email_address)
            ) {
                $emails_temp['billing_contact'] = $search_result->billing_contact->email_address;
            }

            $emails = array_unique(array_values($emails_temp));

            $emails_temp = array_keys($emails_temp);

            foreach ($emails_temp as  $value) {
                if (isset($search_result->{ $value }->phone_number))
                    $phones[] = $search_result->{ $value }->phone_number;
            }

            $phones = array_unique($phones);
        }

        $message = "Whoxy Api domain : " . $search_result->domain_name . " check status :- ";
        $message .= "Whoxy Api  location : " . $isLocationMatched . " name : " . $isNameMatched;
        // TODO
        // SearchApis::logData($person['id'],$message,$combination);

        return [
            'name' => $isNameMatched,
            'location' => $isLocationMatched,
            'found_location' => $foundLocation,
            'location_details' => $locationDetails
        ];
    }

    public function checkWebSiteCountry($url, $personCitis, $person, $combination)
    {
        $dominCountryMatch = true;
        foreach ($personCitis as $city) {
            $dominCountryMatch = matchDomainCountryWithUS($url, $city);
            if ($dominCountryMatch === true) {
                break;
            }
        }

        if ($dominCountryMatch === false) {
            $message = "[websites] domin or email : " . $url . ' is international we removed it';
            // TODO
            // SearchApis::logData($person['id'],$message,$combination);
            return false;
        }
        return true;
    }

    public function checkbanned($url = "", $title = "", $descrip = "")
    {
        $return = ["status" => false];
        $bannedList = [
            "White Pages",
            "Whitepages",
            "phone",
            "lookup",
            "reverse",
            "email",
            "address",
            "directory",
            "background",
            "checks"
        ];
        // remove "cell"&"call" Task #11012 .
        if (
            !empty($url) &&
            stripos($url, "http://") === false &&
            stripos($url, "https://") === false
        ) {
            $url = "http://" . $url;
        }

        $parse_url = parse_url($url);
        $host = "";
        if (!empty($parse_url['host'])) {
            if (stripos($parse_url['host'], "www.") !== false) {
                $parse_url['host'] =str_ireplace("www.", "", $parse_url['host']);
            }
            $host= $parse_url['host'];
        }

        $title = trim($title);
        $ban_reason = "";
        $url_status = false;
        $values = implode("|", $bannedList);
        $url_array = array_push($bannedList, "profile");
        $values_profile = implode("|", $bannedList);

        $re = "/(" . $values_profile . ")/i";
        if (!empty($host) && preg_match($re, $host ,$match)) { //preg_match( $re, $host ,$match)
            if (!empty($match[1])) {
                $url_status = true;
                $ban_reason .= "Matching [{$match[1]}] with the url (" . $host . ") \n";
                $return = ["status" => true, "reason" => $ban_reason];
            }
        }
        $re = "/([\\/ \"'.,_-]|[^a-z0-9])(" . $values . ")([\\/ \"'.,_-]|[^a-z0-9]*)/i";
        if (!empty($title) && preg_match($re, $title, $match)) {
            if (!empty($match[2])) {
                $ban_reason .= "Matching [{$match[2]}] with the title (" . $title . ") \n";
                $return = ["status" => true, "reason" => $ban_reason];
            }
        }

        if (!empty($descrip) && preg_match($re, $descrip, $match)) {
            if (!empty($match[1])) {
                $ban_reason .= "Matching [{$match[2]}] with the descrip (" . $descrip . ") \n";
                $return = ["status" => true, "reason" => $ban_reason];
            }
        }

        if (!empty($host) && !empty($ban_reason) && $url_status) {
            try {
                $model = new BannedDomains;
                $model->domain = $host;
                $model->save();
            } catch(\Exception $e) {

            }
        }
        return $return;
    }
}
