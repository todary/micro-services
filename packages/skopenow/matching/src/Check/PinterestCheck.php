<?php
namespace Skopenow\Matching\Check;

use Skopenow\Matching\Interfaces\CheckInterface;
use Skopenow\Matching\{Status, RunAnalyzer};

class PinterestCheck implements CheckInterface
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
        $status = [
            "name" => 1,
            "found_name" => 0,
            "location" => 1,
            "found_location" => 0,
            "additional" => "",
            "matchWith" => "",
            "nameDetails" => [],
            "locationDetails" => [],
            "image" => ""
        ];
        $url = $this->entry->normalizeURL($url);
        // if (get_class(Yii::app())=="CConsoleApplication" and Yii::app()->params['runCommandLog']){echo "Begin pinterest check ({$url}).";}
        $log = "Begin pinterest check ({$url}).";
        $combination["urlProfile"] = $url;
        if (isset($combination['htmlContent']) && is_array($combination['htmlContent'])) {
            $this->htmlContent = $combination['htmlContent'];
            unset($combination['htmlContent']);
        }

        //... Istansiate variables .
        $names = [];
        $locations = [];
        $schools = [];
        $work_Exps = [];

        //... Get pinterest porfile Info

        // $pinterest_infoProfile = $this->entry->getProfileInfo($url, 'pinterest', $this->htmlContent);
        $pinterest_infoProfile = $this->info;
        $log .= "Pinterest profile data returned " . print_r($pinterest_infoProfile,true) . "\n";
        if (!empty($pinterest_infoProfile['name'])) {
            $names[] = $pinterest_infoProfile['name'];
        }
        if (!empty($pinterest_infoProfile['location'])) {
            $locations[] = $pinterest_infoProfile['location'];
        }

        if (!empty($pinterest_infoProfile['bio'])) {
            $schools[] = $pinterest_infoProfile['bio'];
            $work_Exps[] = $pinterest_infoProfile['bio'];
        }

        if (!empty($pinterest_infoProfile['facebook'])) {
            $username = $pinterest_infoProfile['facebook'];
            if (is_numeric($username)) {
                $facebook_url ='https://www.facebook.com/profile.php?id=' . $username;
            } else {
                $facebook_url ='https://www.facebook.com/' . $username;
            }

            $facebook_infoProfile = $this->entry->getProfileInfo($facebook_url);
            if (!empty($facebook_infoProfile['profileUrl'])) {
                $facebook_url = $facebook_infoProfile['profileUrl'];
            }
            // $facebook_status = $this->check_facebook($facebook_url,$person,$combination);
            if (!empty($facebook_infoProfile['name'])) {
                $names[] = $facebook_infoProfile['name'];
            }
            if (!empty($facebook_infoProfile['location'])) {
                $locations = array_merge($locations, array_values($facebook_infoProfile['location']));
            }
            if (!empty($facebook_infoProfile['education'])) {
                $schools = array_column($facebook_infoProfile['education'], "school");
            }
            if (!empty($facebook_infoProfile['experience'])) {
                $work_Exps = array_column($facebook_infoProfile['experience'], "company");
            }
        }
        if (!empty($pinterest_infoProfile['twitter'])) {
            $twitter_url = $pinterest_infoProfile['twitter'];
            $twitter_infoProfile = $this->entry->getProfileInfo($twitter_url);
            if (!empty($twitter_infoProfile['name'])) {
                $names[] = $twitter_infoProfile['name'];
            }
            if (!empty($twitter_infoProfile['location'])) {
                $locations[] = $twitter_infoProfile['location'];
            }
            if (!empty($twitter_infoProfile['bio'])) {
                $schools[] = $twitter_infoProfile['bio'];
                $work_Exps[] = $twitter_infoProfile['bio'];
            }
        }

        $uname = $this->entry->getUsername($url, $person, false, 0, $combination);
        $names[] = $uname;
        $analyzer = new RunAnalyzer($this->report);
        $status['nameDetails'] = [];

        $status['name'] = $analyzer->runNameAnalyzer($person, $combination, $names, $status['nameDetails']);
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

        $status['location'] = $analyzer->runLocationAnalyzer(
            $person,
            $combination,
            $locations,
            $status['locationDetails']
        );
        if (!empty($status['locationDetails'])) {
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
        }

        $status['school_status'] = $analyzer->runSchoolAnalyzer($person, $combination, $schools, $status['schoolDetails']);
        $status['work_status'] = $analyzer->runWorkAnalyzer($person, $work_Exps, $status['workDetails']);
        $status['dataPointMatch'] = true;
        if (!$status['school_status'] && !$status['work_status']) {
            $status['dataPointMatch'] = false;
        }
        $status['insite_url'] = $url;
        $status['insite_source'] = "pinterest";
        $log .= "All names ".print_r($names,true) . "\n";
        $log .= "All locations ".print_r($locations,true) . "\n";
        $log .= "All schools ".print_r($schools,true) . "\n";
        $log .= "All workExps ".print_r($work_Exps,true) . "\n";
        $log .= "check status : ".print_r($status,true) . "\n";
        if (!empty($pinterest_infoProfile['facebook'])) {
            $status['facebook_url'] = $facebook_url;

            // TODO
            // self::createFacebookInsiteCombination($person,$combination,$status,$facebook_infoProfile);
        }
        if (!empty($pinterest_infoProfile['twitter'])) {
            $status['twitter_url'] = $twitter_url;
            // TODO
            // self::saveTwitterInsiteResult($person,$combination,$status,$twitter_infoProfile);
        }
        if (!empty($pinterest_infoProfile['image'])) {
            $status['image'] = $pinterest_infoProfile['image'];
        }

        // SearchApis::logData($person['id'],$log,$combination);
        return $this->data;
    }
}
