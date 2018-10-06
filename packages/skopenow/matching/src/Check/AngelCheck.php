<?php
namespace Skopenow\Matching\Check;

use Skopenow\Matching\Interfaces\CheckInterface;
use Skopenow\Matching\{Status, RunAnalyzer};

class AngelCheck implements CheckInterface
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
        //...Istantiate variables
        $this->data['name']['status'] = true;
        $this->data['location']['status'] = true;
        $this->data['work']['status'] = true;
        $this->data['school']['status'] = true;
        $this->data['age']['status'] = true;
        $url = $this->url;
        $person = $this->person;
        $combination = $this->combination;
        $names = [];
        $locations = [];
        $schools = [];
        $work_Exps = [];
        $status = [];
        $log = "Begin Angel.co check process for profile ({$url}).\n";

        // $profileInfo = $this->entry->getProfileInfo($url, 'angel', $this->htmlContent);
        $profileInfo = $this->info;
        $pattern = '#angel\.co\/((\w|\d|[\.\-_])+)[^\/\&]*$#i';
        preg_match($pattern, $url, $match);
        $username = "";
        if (isset($match[1])) {
            $username = $match[1];
        }
        preg_match($pattern, $profileInfo['profile_url'], $match2);
        if (isset($match[1])) {
            $username_returned = $match2[1];
        }

        if (
            !empty($profileInfo['profile_url']) &&
            $username_returned != $username
        ) {
            $log .= "Reject profile because user returned {$username_returned} does not match {$username} \n";
            // TODO
            // SearchApis::logData($person['id'],$log,$combination);
            return $this->data;
        }
        if (!empty($profileInfo['name'])) {
            $names[] = $profileInfo['name'];
        }
        if (!empty($profileInfo['location'])) {
            $locations[] = $profileInfo['location'];
        }
        if (!empty($profileInfo['work'])) {
            $work_Exps = $profileInfo['work'];
        }
        if (!empty($profileInfo['school'])) {
            $schools = $profileInfo['school'];
        }
        if (!empty($profileInfo['links'])) {
            $log .= "Found links in Angel.co .\n".print_r($profileInfo["links"], true) ;
            $links = array_flip($profileInfo['links']);
            $insite_links_info = extractLinksInfo($links, $person, $combination);
            if (!empty($insite_links_info['names'])) {
                $names = array_merge($names,$insite_links_info['names']);
            }
            if (!empty($insite_links_info['locations'])) {
                $locations = array_merge($locations,$insite_links_info['locations']);
            }
            if (!empty($insite_links_info['work_Exps'])) {
                $work_Exps = array_merge($work_Exps,$insite_links_info['work_Exps']);
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
        $status['name'] = $runAnalyzer->runNameAnalyzer(
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

        $status['location'] = $runAnalyzer->runLocationAnalyzer(
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
        //....End Logging

        if (!empty($links)) {
            $status['links'] = $links;
        }
        if (!empty($profileInfo['image'])) {
            $status['image'] = $profileInfo['image'];
        }

        // SearchApis::logData($person['id'],$log,$combination);
        return $this->data;
    }
}
