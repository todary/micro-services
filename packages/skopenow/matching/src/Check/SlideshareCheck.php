<?php
namespace Skopenow\Matching\Check;

use Skopenow\Matching\Interfaces\CheckInterface;
use Skopenow\Matching\{Status, RunAnalyzer};
use Skopenow\Matching\Match\NameMatch;
use Skopenow\Matching\Analyzer\{NameAnalyzer, LocationAnalyzer, WorkAnalyzer, SchoolAnalyzer};

class SlideshareCheck implements CheckInterface
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
        $profile_url = $this->url;
        $person = $this->person;
        $combination = $this->combination;
        $this->data['name']['status'] = true;
        $this->data['location']['status'] = true;
        $this->data['work']['status'] = true;
        $this->data['school']['status'] = true;
        $nameAnalyzer = new NameAnalyzer;
        $analyzer = new RunAnalyzer($this->report);
        $combination['urlProfile'] = $profile_url;
        $status = [];
        $check = [];

        $status["name"] = 1;
        $status["location"] = 1;
        $status["work"] = 0;
        $status["school"] = 0;
        $status["nameDetails"] = [];
        $status["locationDetails"] = [];

        // check unique name combination ..
        $nameMatch = new NameMatch;
        $checkUniqueComb =  $nameMatch->checkUniqueNameComb($combination);

        if (
            isset($combination['htmlContent']) &&
            is_array($combination['htmlContent'])
        ) {
            $this->htmlContent = $combination['htmlContent'];
            unset($combination['htmlContent']);
        }

        $url = $profile_url;
        $comb = $combination;
        $uname = $this->entry->getUsername($url);
        if (
            $uname &&
            isset($person['usernames']) &&
            stripos(",{$person['usernames']},", ",{$uname},") !== false
        ) {
            // TODO
            // SearchApis::logData($person['id'], "This profile $url comes from a manual/reverse username search, passed.", $comb);

            $check['name'] = "manual/reverse username search (passed)";
            $check['location'] = "manual/reverse username search (passed)";
            $checkArray = [];
            $checkArray['result_url'] = $url;
            $checkArray['check'] = $check;
            // TODO
            // Yii::app()->reportLog->resultCheck($checkArray, $person, $comb);
            unset($checkArray);
            return $this->data;
        }

        // Check if name and location is empty,set log and return $status .
        if (empty($person['first_name']) && empty($person['city'])) {
            $check['name'] = "empty first_name && city";
            $check['location'] = "empty first_name && city";
            $checkArray = [];
            $checkArray['result_url'] = $profile_url;
            $checkArray['check'] = $check;
            // TODO
            // Yii::app()->reportLog->resultCheck($checkArray, $person, $combination);
            unset($checkArray);
            return $this->data;
        }

        $search_url = $url;
        $options = [
            'name'=> true,
            'location'=> true,
            'image'=> true,
            'links' => true,
        ];

        // $slideshare_infoProfile = $this->entry->getProfileInfo($search_url, 'slideshare', $this->htmlContent);
        $slideshare_infoProfile = $this->info;
        if ($slideshare_infoProfile === false) {
            // TODO
            // SearchApis::logData($person['id'],"Error finding curl profilehtml for $profile_url :{$ret['error']}", $combination);
            $status["name"] = 0;
            $status["location"] = 0;
            $check['location'] = "Curl Error";
            $check['name'] = "Curl Error";
            $checkArray = [];
            $checkArray['result_url'] = $url;
            $checkArray['check'] = $check;
            // TODO
            // Yii::app()->reportLog->resultCheck($checkArray, $person, $combination);
            unset($checkArray);
            return $this->data;
        }

        $status["image"] = '';
        if ($slideshare_infoProfile['image']) {
            $status["image"] = $slideshare_infoProfile['image'];
        }

        if (!empty($slideshare_infoProfile['links'])) {
            $links = $slideshare_infoProfile['links'];
            $links = array_flip($links);
            $insite_links_info = extractLinksInfo($links, $person, $combination);
        }

        if ($slideshare_infoProfile['name'] != null) {
            $status["profile_name"] = $slideshare_infoProfile['name'];
            $names = $slideshare_infoProfile['name'];
            $nameAnalyzer->stringToArray($names);
            if (
                isset($insite_links_info) &&
                !empty($insite_links_info['names'])
            ) {
                $names = array_merge($insite_links_info['names'], $names);
            }
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

            if ($status['name']) {
                $check['name'] = "Matched";
            } else {
                $status["name"] = 0;
                $check['name'] = "Not Matched";
            }
        }
        $locationAnalyzer = new LocationAnalyzer;
        $locations = $locationAnalyzer->loadAllLocations($person);

        if (count($locations) > 0 && !$checkUniqueComb) {
            $locations = $slideshare_infoProfile['location'];
            $nameAnalyzer->stringToArray($locations);
            if (isset($insite_links_info) && !empty($insite_links_info['locations'])) {
                $locations = array_merge($insite_links_info['locations'],$locations);
            }
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

            if ($status['location']) {
                $check['location'] = "Matched";
                $status["found_location"] = 1;
            } else {
                // when location is missing or not found in profile .
                $status["location"] = 1 ;
                $status["found_location"] = 0;
                $check["location"] = "Not Found";
                // TODO
                // SearchApis::logData($person['id'], "Location Not Found in Soundcloud Profile .", $combination);
            }
        } else {
            if ($checkUniqueComb) {
                $checkStatus['location']="Escape match location, cause this profile came from Uniqu Name.";
                // TODO
                // SearchApis::logData($person['id'], "Escape match location for this Profile ( $profile_url ) cause it came from Unique Name.\n", $combination);
            } else {
                $check['location'] = "Empty Person Location";
                // TODO
                // SearchApis::logData($person['id'], "Empty Person Location.", $combination);
            }
            $status["location"] = 1;
        }

        // Check company and school
        if (!empty($slideshare_infoProfile['work'])) {
            $workDetails = [];
            $work = $slideshare_infoProfile['work'];
            $nameAnalyzer->stringToArray($work);
            if (isset($insite_links_info) && !empty($insite_links_info['work_Exps'])) {
                $work = array_merge($insite_links_info['work_Exps'], $work);
            }
            $workAnalyzer = new WorkAnalyzer;
            $statusWork = $workAnalyzer->isWorkMatch($person, $combination, $work, $workDetails);
            if ($statusWork['status']) {
                $status["work"] = true;
                $status["workDetails"] = $workDetails;
            }
            if (in_array('cm', $status['workDetails'])) {
                $this->data['work']['identities']['cm'] = true;
            }
            $this->data['work']['matchWith'] = $status['workDetails']['matchWith']??'';

            $schoolDetails = [];
            $schools = $slideshare_infoProfile['work'];
            $nameAnalyzer->stringToArray($schools);
            if (isset($insite_links_info) &&!empty($insite_links_info['schools'])) {
                $schools = array_merge($insite_links_info['schools'], $schools);
            }
            $schoolAnalyzer = new SchoolAnalyzer;
            $statusWork = $schoolAnalyzer->isSchoolMatch($person,$combination,$schools,$schoolDetails);
            if (in_array('sc', $status['schoolDetails'])) {
                $this->data['school']['identities']['sc'] = true;
            }
            $this->data['school']['matchWith'] = $status['schoolDetails']['matchWith']??'';
            if ($statusWork['status']) {
                $status["school"] = true;
                $status["schoolDetails"] = $schoolDetails;
                if (in_array('sc', $status['schoolDetails'])) {
                    $this->data['school']['identities']['sc'] = true;
                }
                $this->data['school']['matchWith'] = $status['schoolDetails']['matchWith']??'';
            }

        }

        // Check if there another sources ..

        if (
            !empty($slideshare_infoProfile['links']) &&
            $status["name"] && $status['location']
        ) {
            $t = microtime(true);
            $unique_cont2 = $this->entry->prepareContent($profile_url, $combination['person_id'], $combination['id']);

            /*if (get_class(Yii::app())=="CConsoleApplication" and Yii::app()->params['runCommandLog'])
            {
                global $_start_time;
                echo number_format(microtime(true)-$_start_time,3) . " " . 'Class : '.__CLASS__.', Function : '.__METHOD__.', Mission : prepare_content'.' Time : '.number_format(microtime(true)-$t,3).', File : '.__FILE__.', Line : '.__LINE__."<br>\n";debug_print_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);echo "\n\n";
            };*/

            $combination_additionalProfiles = [];
            $combination_additionalProfiles['slideshare_url'] = $profile_url;
            $combination_additionalProfiles['combination_level'] = $combination['combination_level'];
            $combination_additionalProfiles['sources'] = [];
            $combination_additionalProfiles['bestNameDetails'] = $status['nameDetails'];
            $combination_additionalProfiles['bestLocationDetails'] = $status['locationDetails'];

            $t = microtime(true);

            if (!empty($links) && ($status['name'] && $status['location'])) {
                SearchApis::createInsiteLinkeCombinations($person,$combination,$url,$links,$status,"slideshare_profiles_linked");
            }
            // SearchApis::store_combination (null, null, "slideshare_profiles_linked", CJSON::encode($combination_additionalProfiles), null, null, $combination ['combs_fields'], $person );

            // if (get_class(Yii::app())=="CConsoleApplication" and Yii::app()->params['runCommandLog']){global $_start_time; echo number_format(microtime(true)-$_start_time,3) . " " . 'Class : '.__CLASS__.', Function : '.__METHOD__.', Mission : store_combination'.' Time : '.number_format(microtime(true)-$t,3).', File : '.__FILE__.', Line : '.__LINE__."<br>\n";debug_print_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);echo "\n\n";};
        }

        $checkArray = [];
        $checkArray['result_url'] = $profile_url;
        $checkArray['check'] = $check;
        // TODO
        // Yii::app()->reportLog->resultCheck($checkArray, $person, $combination);
        unset($checkArray);
        return $this->data;
    }
}
