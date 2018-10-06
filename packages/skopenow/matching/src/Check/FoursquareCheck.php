<?php
namespace Skopenow\Matching\Check;

use Skopenow\Matching\Interfaces\CheckInterface;
use Skopenow\Matching\{Status, RunAnalyzer};


class FoursquareCheck implements CheckInterface
{
    private $info;
    private $entry;
    private $data;
    private $url;
    private $person;
    private $combination;
    private $htmlContent = [];

    public function __construct(
        string $url,
        array $info,
        $combination,
        ReportService $report
    )
    {
        $this->entry = loadService('urlInfo');
        $status = new Status;
        $this->data = $status->matchingData;
        $this->combination = $combination;
        $this->person = $report->getReport();
        $this->url = $url;
        $this->info = $info;
        $this->report = $report;
    }

    public function check()
    {
        $url = $this->url;
        $person = $this->person;
        $combination = $this->combination;
        $this->data['name']['status'] = true;
        $this->data['location']['status'] = true;
        $this->data['work']['status'] = true;
        $this->data['school']['status'] = true;
        $log = "Begin Check foursquare profile {{$url}} \n";
        $status = [
            "name" => 0,
            "location" => 0,
            "nameDetails" => [],
            "locationDetails" => []
        ];
        // $profileInfo = $this->entry->getProfileInfo($url, 'foursquare', $this->htmlContent);
        $profileInfo = $this->info;
        $status['profile_url'] = $profileInfo['profile_url'];
        $status['is_profile'] = $this->entry->isProfile($url);
        $status['dataPointMatch'] = true;
        $foursquare_uniqueContent = $this->entry->prepareContent($status['profile_url']);
        $bestNameDetails = $bestLocationDetails = $names = $locations = $work_Exps = $schools = [];
        if (isset($profileInfo['name'])) {
            $names[] = $profileInfo['name'];
            if (stripos($status['profile_url'], "https://foursquare.com/") !== false ) {
                $username_url = str_replace("https://foursquare.com/", "", $status['profile_url']);
                $names[] = $username_url;
            }
        }
        if (isset($profileInfo['location'])) {
            $locations[]= $profileInfo['location'];
        }
        $links = [];
        if (!empty($profileInfo['facebook'])) {
            $facebook_url = "";
            if (stripos($profileInfo['facebook'], "facebook.com")) {
                $facebook_url = $profileInfo['facebook'];
            } else {
                $facebook_url = $this->getFacebookLinkByUsername($profileInfo['facebook']);
            }
            $links[] = $facebook_url;
        }
        if (!empty($profileInfo['twitter'])) {
            $twitter_url = "";
            $twitter_status = true;
            $twitter_url = "https://twitter.com/" . $profileInfo['twitter'] ;
            if (stripos($profileInfo['twitter'], "twitter.com")) {
                $twitter_url = $profileInfo['twitter'];
            }
            $links[] = $twitter_url;
        }
        if (!empty($links)) {
            $log .= " Found insite links " . print_r($links,true) . "\n" ;
            $links = array_flip($links);
            $insite_links_info = (new \Skopenow\Matching\Helpers)->extractLinksInfo($links, $person, $combination);
            if (!empty($insite_links_info['names'])) {
                $names = array_merge($names,$insite_links_info['names']);
            }
            if (!empty($insite_links_info['locations'])) {
                $locations = array_merge($locations, $insite_links_info['locations']);
            }
            if (!empty($insite_links_info['work_Exps'])) {
                $work_Exps = array_merge($work_Exps, $insite_links_info['work_Exps']);
            }
            if (!empty($insite_links_info['schools'])) {
                $schools = $insite_links_info['schools'];
            }
            if (!empty($insite_links_info['age'])) {
                $age = $insite_links_info['age'];
            }
        }
        $analyzer = new RunAnalyzer($this->report);
        $status['nameDetails'] = [];
        $status['name'] = $analyzer->runNameAnalyzer(
            $person,
            $combination,
            $names,
            $status['nameDetails']
        );

        $this->data['name']['matchWith'] = $status['nameDetails']['matchWith']??'';
        $this->data['name']['identities']['input_name'] = $status['nameDetails']['input_name']??false;

        if (in_array('fn', $status['nameDetails'])) {
            $this->data['name']['identities']['fn'] = true;
        }

        if (in_array('mn', $status['nameDetails'])) {
            $this->data['name']['identities']['mn'] = true;
        }
        if (in_array('IN', $status['nameDetails'])) {
            $this->data['name']['identities']['input_name'] = true;
        }

        if (in_array('ln', $status['nameDetails'])) {
            $this->data['name']['identities']['ln'] = true;
        }

        if (in_array('unq_name', $status['nameDetails'])) {
            $this->data['name']['identities']['unq_name'] = true;
        }
        if (in_array('fzn', $status['nameDetails'])) {
            $this->data['name']['identities']['fzn'] = true;
        }

        $status['locationDetails'] = [];
        $status['location'] = $analyzer->runLocationAnalyzer(
            $person,
            $combination,
            $locations,
            $status['locationDetails']
        );

        $input = $status['locationDetails']['locations'][0]??'';
        $status['locationDetails'] = $status['locationDetails']['locationDetails']??[];
        if (in_array('st', $status['locationDetails'])) {
            $this->data['location']['identities']['st'] = true;
        }
        if (in_array('pct', $status['locationDetails'])) {
            $this->data['location']['identities']['pct'] = true;
        }

        if (in_array('matchTypeName', $status['locationDetails'])) {
            if ($status['locationDetails']['matchTypeName'] == 'SmallCityWithSmallCity') {
                $this->data['location']['identities']['exct-sm'] = true;
            } elseif ($status['locationDetails']['matchTypeName'] == 'BigCityWithBigCity') {
                $this->data['location']['identities']['exct-bg'] = true;
            }
        }
        $this->data['location']['matchWith'] = $input;

        $status['workDetails'] = [];
        $status['work_status'] = $analyzer->runWorkAnalyzer(
            $person,
            array_column($work_Exps, "company"),
            $status['workDetails']
        );

        if (in_array('cm', $status['workDetails'])) {
            $this->data['work']['identities']['cm'] = true;
        }
        $this->data['work']['matchWith'] = $status['workDetails']['matchWith']??'';

        $status['schoolDetails'] = [];
        $status['school_status'] = $analyzer->runSchoolAnalyzer(
            $person,
            $combination,
            array_column($schools, "school"),
            $status['schoolDetails']
        );
        if (in_array('sc', $status['schoolDetails'])) {
            $this->data['school']['identities']['sc'] = true;
        }
        $this->data['school']['matchWith'] = $status['schoolDetails']['matchWith']??'';

        //...Logging
        $log .= "Names found " . print_r($names, true) . "\n";
        $log .= "Locations found " . print_r($locations, true) . "\n";
        $log .= "Schools found " . print_r(array_column($schools, "school"), true) . "\n";
        $log .= "Companies found " . print_r(array_column($work_Exps, "company"), true) . "\n";
        $log .= "Check Status " . print_r($status, true) . "\n";

        if (!$status['work_status'] || !$status['school_status']) {
            $log .= "   Data point match : false";
            $status['dataPointMatch'] = false;
        }

        if (
            !empty($links) &&
            (
                $status['name'] &&
                $status['location'] &&
                $status['dataPointMatch']
            )
        ) {
            createInsiteLinkeCombinations(
                $person,
                $combination,
                $foursquare_uniqueContent,
                $links,
                $status,
                "foursquare_profiles_linked"
            );
        }

        // TODO
        // SearchApis::logData($person['id'],$log,$combination);
        return $this->data ;
    }

    private function getFacebookLinkByUsername($username)
    {
        $facebook ='https://www.facebook.com/' . $username;
        if (is_numeric($username)) {
            $facebook ='https://www.facebook.com/profile.php?id=' . $username;
        }
        return $facebook;
    }
}
