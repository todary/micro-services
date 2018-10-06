<?php
namespace Skopenow\Matching\Check;

use Skopenow\Matching\{Status, RunAnalyzer};
use Skopenow\Matching\Interfaces\CheckInterface;

class GoogleplusCheck implements CheckInterface
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
        $this->data['name']['status'] = true;
        $this->data['location']['status'] = true;
        $this->data['work']['status'] = true;
        $this->data['school']['status'] = true;
        $url = $this->url;
        $person = $this->person;
        $combination = $this->combination;
        $log = '';

        $status = [
            "name" => 0,
            "location" => 0,
            "additional" => "",
            "additionalProfiles" => [],
            "extra" => [],
            "dataPointMatch" => true
        ];
        $names = [];
        $locations = [];
        $schools = [];
        $work_Exps = [];
        $runAnalyzer = new RunAnalyzer($this->report);

        // TODO
        // $log = "Begin Googleplus check process for profile ({$url}) .\n";

        if (
            isset($combination['htmlContent']) &&
            is_array($combination['htmlContent'])
        ) {
            $this->htmlContent = $combination['htmlContent'];
            unset($combination['htmlContent']);
        }
        $username = $this->entry->getUsername($url);
        $personUsernames = [];
        if (!empty($person['usernames'])) {
            $personUsernames = explode(",", strtolower($person['usernames']));
        }

        //...Getting googleplus profile info
        $parsedUrl = parse_url($url, PHP_URL_PATH);
        $profileId = trim($parsedUrl, "/");
        if ($profileId) {
            // $googleplus_profileInfo = $this->entry->getProfileInfo($url, 'plus.google', $this->htmlContent);
            $googleplus_profileInfo = $this->info;
            if (!empty($googleplus_profileInfo['name'])) {
                $names[]=$googleplus_profileInfo['name'];
            }
            if (!empty($googleplus_profileInfo['location'])) {
                $locations = array_values($googleplus_profileInfo['location']);
            }
            if (!empty($googleplus_profileInfo['education'])) {
                $schools[] = $googleplus_profileInfo['education'];
                $status['extra']['education'] = $googleplus_profileInfo['education'];
            }
            if (!empty($googleplus_profileInfo['work'])) {
                $work_Exps = $googleplus_profileInfo['work'];
                $status['extra']['work'] = $googleplus_profileInfo['work'];
            }
            if (!empty($googleplus_profileInfo['emails'])) {
                $status['extra']['emails'] = $googleplus_profileInfo['emails'] ;
            }
            if (!empty($googleplus_profileInfo['address'])) {
                $status['extra']['address'] = $googleplus_profileInfo['address'] ;
            }
            if (!empty($googleplus_profileInfo['links'])) {
                if (!empty($googleplus_profileInfo['links']['username'])) {
                    unset($googleplus_profileInfo['links']['username']) ;
                }
                // TODO
                // $log .= "   Found links in googleplus .\n".print_r($googleplus_profileInfo['links'],true)."\n";

                $googleplus_profileInfo['links'][] = "https://picasaweb.google.com/" . $profileId;
                $links = array_flip($googleplus_profileInfo['links']);
                $insite_links_info = (new \Skopenow\Matching\Helpers)->extractLinksInfo($links, $person, $combination);
                if (!empty($insite_links_info['names'])) {
                    $names = array_merge($names, $insite_links_info['names']);
                }
                if (!empty($insite_links_info['locations'])) {
                    $locations = array_merge($locations, $insite_links_info['locations']);
                }
                if (!empty($insite_links_info['schools'])) {
                    $schools = array_merge($schools, $insite_links_info['schools']);
                }
                if (!empty($insite_links_info['work_Exps'])) {
                    $work_Exps = array_merge($work_Exps, $insite_links_info['work_Exps']);
                }
            }
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
            if (empty($status['locationDetails'])) {
                return $this->data;
            }
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

            $status['schoolDetails'] = [];
            $status['school_status'] = $runAnalyzer->runSchoolAnalyzer(
                $person,
                $combination,
                array_column($schools, "school"),
                $status['schoolDetails']
            );
            if (in_array('sc', $status['schoolDetails'])) {
                $this->data['school']['identities']['sc'] = true;
            }
            $this->data['school']['matchWith'] = $status['schoolDetails']['matchWith']??'';

            $status['workDetails'] = [];
            $status['work_status'] = $runAnalyzer->runWorkAnalyzer(
                $person,
                array_column($work_Exps,"company"),
                $status['workDetails']
            );
            if (in_array('cm', $status['workDetails'])) {
                $this->data['work']['identities']['cm'] = true;
            }
            $this->data['work']['matchWith'] = $status['workDetails']['matchWith']??'';

            $log .= "   names found ".print_r($names,true)."\n";
            $log .= "   locations found ".print_r($locations,true)."\n";
            $log .= "   companies found ".print_r(array_column($work_Exps,"company"),true)."\n";
            $log .= "   schools found ".print_r(array_column($schools, "school"),true)."\n";
            $log .= "   check status :  ".print_r($status,true)."\n";

        } else {
            $status['name'] = 1;
            $status['location'] = 1;
            $status['dataPointMatch'] = 1;
            $log .= "   Invalid API username . \n";
        }
        if (!$status['work_status'] || !$status['school_status']) {
            $log .= "   Data point match : false";
            $status['dataPointMatch'] = false;
        }

        if (
            $username &&
            isset($person['usernames']) &&
            in_array(strtolower($username), $personUsernames)
        ) {
            $log .= "   This profile comes from a manual/reverse username search, Passed. \n";
            $status['name'] = 1 ;
            $status['location'] = 1 ;
            $status['dataPointMatch'] = 1 ;
        }

        if (
            !empty($links) &&
            ($status['name'] &&
                $status['location'] &&
                $status['dataPointMatch'])
        ) {
            $createInsiteLinkeCombinations($person, $combination, $url, $links, $status, "googleplus_profiles_linked");
        }

        if (!empty($googleplus_profileInfo['image'])) {
            $status['image'] = $googleplus_profileInfo['image'];
        }
        // SearchApis::logData($person['id'],$log,$combination);
        return $this->data;
    }
}
