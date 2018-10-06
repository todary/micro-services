<?php

namespace Skopenow\Matching\Check;

use Skopenow\Matching\Interfaces\CheckInterface;
use Skopenow\Matching\Analyzer\{
    LocationAnalyzer,
    WorkAnalyzer,
    SchoolAnalyzer
};
use Skopenow\Matching\{Status, RunAnalyzer};
use Skopenow\Matching\Match\NameMatch;

class FacebookCheck implements CheckInterface
{
    private $info;
    private $entry;
    private $data;
    private $url;
    private $person;
    private $combination;
    private $htmlContent = [];
    private $isRelative = true;
    private $additionalLocation = '';
    private $disableLocationCheck = false;
    private $nameExaxt = false;
    private $disableMiddlenameCriteria = false;

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

    public function setHtmlContent(array $htmlContent)
    {
        $this->htmlContent = $htmlContent;
    }

    public function setIsRelative(bool $isRelative)
    {
        $this->isRelative = $isRelative;
    }

    public function setAdditionalLocation(string $location)
    {
        $this->additionalLocation = $location;
    }

    public function disableLocationCheck(bool $check)
    {
        $this->disableLocationCheck = $check;
    }

    public function setNameExact(bool $nameExact)
    {
        $this->nameExaxt = $nameExaxt;
    }

    public function disableMiddlenameCriteria(bool $disable)
    {
        $this->disableMiddlenameCriteria = $disable;
    }

    public function check()
    {
        $this->data['name']['status'] = true;
        $this->data['location']['status'] = true;
        $this->data['work']['status'] = true;
        $this->data['school']['status'] = true;
        $this->data['username']['status'] = true;
        $this->data['email']['status'] = true;
        $url = $this->url;
        $person = $this->person;
        $combination = $this->combination;
        $analyzer = new RunAnalyzer($this->report);
        $t = microtime(true);
        $url = $this->entry->normalizeURL($url);
        $cacheKey = json_encode([
            "check_facebook",
            $url,
            $person['id'],
            $this->combination['id'],
            $this->isRelative,
            $this->additionalLocation,
            $this->disableLocationCheck
        ], true);
        $cacheTime = 60 * 60; // seconds

        if (isset($this->combination['htmlContent']) && is_array($this->combination['htmlContent'])) {
            $this->htmlContent = $this->combination['htmlContent'];
            unset($this->combination['htmlContent']);
        }

        // if (\App::environment('local')) {
        //     $cacheTime = 60 * 60 * 24; // 1 day
        // }

        $_url = $url;

        $is_profile = $this->entry->isProfile($_url, $this->htmlContent);
        $ret = null;
        $t = microtime(true);
        // TODO
        // $ret = Yii::app()->cache->get($cacheKey, null);
        $nameMatch = new NameMatch;
        $checkUniqueComb = $nameMatch->checkUniqueNameComb($this->combination);

        $this->combination['urlProfile'] = $url;

        $t = microtime(true);
        // TODO
        // SearchApis::logData($person['id'], "Checking FB profile $url \n", $comb);
        $firstcheck = false;
        $secondcheck = false;
        $status = [];
        $check = [];
        $status["instagram"] = [];
        $status["location"] = 1;
        $status["name"] = 1;
        $status["familyCheck"] = 1;

        if ($is_profile) {
            $status['has_profile_data'] = true;
        }

        $status["found_location"] = 0;
        $status["found_name"] = 0;
        $status["found_image"] = 0;
        $status["found_friends_list"] = 0;
        $status["page_type"] = [];
        $status['matchWith'] = "";
        $t = microtime(true);
        $uname = $this->entry->getUsername($url);

        $reverseUsername = $uname && isset($person['usernames']) && stripos(",{$person['usernames']},", ",{$uname}," ) !== false;

        $combination = @unserialize($this->combination['combination']);
        $reverseEmail = !empty($combination['email']) && !empty($person['email']) && stripos(",{$person['email']},", ",{$combination['email']},") !== false;
        $fromTloEmail = !empty($combination['email']) && !$reverseEmail && !$reverseUsername;


        $t = microtime(true);
        // # Get info form facebook profile .

        // $infoProfile = $this->entry->getProfileInfo($url, 'facebook', $this->htmlContent);
        $infoProfile = $this->info;

        if (isset($infoProfile['profileUrl'])) {
            $url = $infoProfile['profileUrl'];
            $this->combination['urlProfile'] = $url;
        }

        if ($reverseUsername || $reverseEmail || $fromTloEmail) {

            if ($reverseEmail) {
                $_type = 'Email';
                $status['em'] = true;
                $this->data['email']['identities']['em'] = true;
                $this->data['email']['matchWith'] = $combination['email'];
                $this->data['email']['identities']['input_em'] = true;
            } elseif($fromTloEmail) {
                $_type = "Tloxp Email ";
                $status['em'] = true;
                $this->data['email']['identities']['em'] = true;
            } else {
                $status['un'] = true;
                $_type = 'Username';
            }
            $status["found_location"] = 1;
            $status["found_name"] = 1;
            $status["reverseMatch"] = 1;
            $status["found_friends_list"] = 1;
            $status["page_type"] = [
                'type' => 'profile',
                'results_page_type_id' => 1
            ];
            $status['nameDetails'] = [];
            $name_status = $analyzer->runNameAnalyzer(
                $person,
                $this->combination,
                [$infoProfile['name']],
                $status['nameDetails']
            );
            if (!$name_status) {
                $relative_name_status = $analyzer->runNameAnalyzer($person, $this->combination, [$infoProfile['name']], $relativeNameDetailes);
                if ($relative_name_status && !empty($relativeNameDetailes)) {
                    $name_status = $relative_name_status;
                    $status['nameDetails'] = $relativeNameDetailes;
                    $status['mark_as_relative'] = true;
                }
            }
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

            if (!$name_status && $fromTloEmail) {
                $first_names = $this->getPersonFirstNames($person, true);

                $splittedNameIterator = loadService('nameInfo')->nameSplit(new \ArrayIterator([$infoProfile['name']]));
                $splittedNameArray = iterator_to_array($splittedNameIterator);
                $search_first_name = $splittedNameArray[0]["splitted"][0]??[];

                if (in_array($search_first_name, $first_names, true)) {
                    $name_status = true;
                    $status['nameDetails'] = ["fn"];
                    $this->data['name']['identities']['fn'] = true;
                } else {
                    $status['name'] = 0;
                }
            }

            if (!$fromTloEmail) {
                $status['input_matched'] = true;
                $this->data['name']['identities']['input_name'] = true;
            }

            $status['locationDetails'] = [];
            $locations = [] ;
            if (isset($infoProfile ['location'] ['livesin'])) {
                $locations[] = $infoProfile['location']['livesin'];
            }
            if (isset($infoProfile ['location'] ['hometown'])) {
                $locations[] = $infoProfile['location']['hometown'];
            }
            if (!empty($infoProfile['location']['otherLocations'])) {
                $locations = array_merge($locations,$infoProfile['location']['otherLocations']);
            }
            $location_status = $analyzer->runLocationAnalyzer(
                $person,
                $this->combination,
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


            $t = microtime(true);
            // TODO
            // SearchApis::logData($person['id'], "This profile $url comes from a manual/reverse {$_type} search, passed. \n", $comb);
            $t = microtime(true);
            // TODO
            // Yii::app()->cache->set($cacheKey, $status, 300);
            $check['name'] = "manual/reverse {$_type} search (passed)";
            $check['location'] = "manual/reverse {$_type} search (passed)";
            $checkArray = [];
            $checkArray['result_url'] = $url;
            $checkArray['check'] = $check;
            $t = microtime(true);
            unset($checkArray);
            return $this->data;
        }

        $status ["page_type"] = $infoProfile ["page_type"];
        if ($infoProfile['status'] == false) {
            // TODO
            // SearchApis::logData($person['id'], "Can Not open this profile: {$search_url}\n", $comb);
            // Yii::app()->cache->set($cacheKey, $status, 300);
            $status ["page_type"] = $infoProfile['page_type'];

            unset($checkArray);
            if (
                (isset($infoProfile['name']) && empty($infoProfile['name']))
                || !isset($infoProfile['name'])
            ) {
                if ($is_profile) {
                    $status['name'] = 0;
                }
            }
            return $this->data;
        }

        if ($infoProfile ['name']) {
            $status ["profile_name"] = $infoProfile ['name'];
            $status ["found_name"] = true;
        }

        if (count($infoProfile['instagram']) && $infoProfile['instagram']['link']) {
            $status ["instagram"] = $infoProfile ['instagram'];
        }

        if ($infoProfile ['image']) {
            $status ["image"] = $infoProfile ['image'];
            $status ["found_image"] = true;
        }

        if (
            isset($infoProfile['location']['livesin']) &&
            $infoProfile['location']['livesin']
        ) {
            $status["found_location"] = true;
        }
        if (
            isset($infoProfile['location']['hometown']) &&
            $infoProfile['location']['hometown']
        ) {
            $status["found_location"] = true;
        }

        // Check if name and location is empty,set log and return $status .
        if (empty($person['first_name']) && empty($person['city'])) {
            $ex_syn = "Empty name and Location. \n";
            $t = microtime(true);
            // TODO
            // SearchApis::logData($person['id'], $ex_syn, $comb);

            /*
            if (get_class(Yii::app())=="CConsoleApplication" and Yii::app()->params['runCommandLog']){global $_start_time; echo number_format(microtime(true)-$_start_time,3) . " " . 'Class : '.__CLASS__.', Function : '.__METHOD__.', Mission : logData'.' Time : '.number_format(microtime(true)-$t,3).', File : '.__FILE__.', Line : '.__LINE__."<br>\n";debug_print_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);echo "\n\n";};
            $t=microtime(true);
            Yii::app ()->cache->set ( $cacheKey, $status,300 );
            if (get_class(Yii::app())=="CConsoleApplication" and Yii::app()->params['runCommandLog']){global $_start_time; echo number_format(microtime(true)-$_start_time,3) . " " . 'Class : '.__CLASS__.', Function : '.__METHOD__.', Mission : cache->set'.' Time : '.number_format(microtime(true)-$t,3).', File : '.__FILE__.', Line : '.__LINE__."<br>\n";debug_print_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);echo "\n\n";};

            $t=microtime(true);
            if (get_class(Yii::app())=="CConsoleApplication" and Yii::app()->params['runCommandLog']){global $_start_time; echo number_format(microtime(true)-$_start_time,3) . " " . 'Class : '.__CLASS__.', Function : '.__METHOD__.', Mission : reportLog->resultCheck'.' Time : '.number_format(microtime(true)-$t,3).', File : '.__FILE__.', Line : '.__LINE__."<br>\n";debug_print_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);echo "\n\n";};
            */
            unset($checkArray);

            return $this->data;
        }

        // $SearchApis = new SearchApis($person);
        $status["url"] = $url;


        // check if not facebook profile ..
        if (
            !$infoProfile['profile'] &&
            stripos($infoProfile['profile']['body'], 'fb-timeline-cover-name' ) === false
        ) {
            $status["location"] = 1;
            $status["name"] = 1;
            $status["familyCheck"] = 1;

            $t = microtime(true);
            // TODO
            // if (get_class(Yii::app())=="CConsoleApplication" and Yii::app()->params['runCommandLog']){global $_start_time; echo number_format(microtime(true)-$_start_time,3) . " " . 'Class : '.__CLASS__.', Function : '.__METHOD__.', Mission : reportLog->resultCheck'.' Time : '.number_format(microtime(true)-$t,3).', File : '.__FILE__.', Line : '.__LINE__."<br>\n";debug_print_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);echo "\n\n";};
            unset($checkArray);

            $ex_syn = 'This is not facebook profile ' . $url."\n";
            $t = microtime(true);
            // TODO
            // SearchApis::logData($person['id'], $ex_syn, $comb);
            // if (get_class(Yii::app())=="CConsoleApplication" and Yii::app()->params['runCommandLog']){global $_start_time; echo number_format(microtime(true)-$_start_time,3) . " " . 'Class : '.__CLASS__.', Function : '.__METHOD__.', Mission : logData'.' Time : '.number_format(microtime(true)-$t,3).', File : '.__FILE__.', Line : '.__LINE__."<br>\n";debug_print_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);echo "\n\n";};
            $t = microtime(true);
            // Yii::app ()->cache->set ( $cacheKey, $status ,300);
            // if (get_class(Yii::app())=="CConsoleApplication" and Yii::app()->params['runCommandLog']){global $_start_time; echo number_format(microtime(true)-$_start_time,3) . " " . 'Class : '.__CLASS__.', Function : '.__METHOD__.', Mission : cache->set'.' Time : '.number_format(microtime(true)-$t,3).', File : '.__FILE__.', Line : '.__LINE__."<br>\n";debug_print_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);echo "\n\n";};
            return $this->data;
        }

        if ($infoProfile['name']) {
            $status ["name"] = 0;
            $comb_isRelative = $this->combination['relative'];
            if (isset($comb_isRelative) && $comb_isRelative) {
                $this->isRelative = true;
            } else {
                $this->isRelative = false;
            }
            $params = array("disableMiddlenameCriteria" => $this->disableMiddlenameCriteria, "is_relative" => $this->isRelative);
            $status['nameDetails'] = [];
            $status['name'] = $analyzer->runNameAnalyzer($person, $this->combination, [$infoProfile['name']], $status['nameDetails'], $params);

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
        } else {
            // Task #: 9787 comment number:4
            if ($is_profile) {
                $status['name'] = 0;
            }
        }

        if ($this->disableLocationCheck) {
            // TODO
            // SearchApis::logData ( $person ['id'], "Disable location check for this profile: {$search_url}\n", $comb );
            $t = microtime(true);
            // Yii::app ()->cache->set ( $cacheKey, $status , 300);
            // if (get_class(Yii::app())=="CConsoleApplication" and Yii::app()->params['runCommandLog']){global $_start_time; echo number_format(microtime(true)-$_start_time,3) . " " . 'Class : '.__CLASS__.', Function : '.__METHOD__.', Mission : cache->set'.' Time : '.number_format(microtime(true)-$t,3).', File : '.__FILE__.', Line : '.__LINE__."<br>\n";debug_print_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);echo "\n\n";};
            return $this->data;
        }

        $locations = [];
        if (isset($infoProfile['location']['livesin'])) {
            $locations[] = $infoProfile['location']['livesin'];
        }
        if (isset($infoProfile['location']['hometown'])) {
            $locations[] = $infoProfile['location']['hometown'];
        }
        if (!empty($infoProfile['location']['otherLocations'])) {
            $locations = array_merge($locations,$infoProfile['location']['otherLocations']);
        }

        // using location analyzer for location matching.
        $LocationAnalyzer = new LocationAnalyzer;
        $LocationAnalyzer($person, $locations);
        $status['location'] = 0;
        if ($LocationAnalyzer->isMatch()) {
            $status ["location"] = 1;
            $locationDetails = $LocationAnalyzer->getBestLocations();
            if (!empty($locationDetails['locationDetails'])) {
                $status["locationDetails"] = $locationDetails['locationDetails'];
                $status['locationDetails'] = array_merge($status['locationDetails'], $status['locationDetails']['matchScore']);
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
        }
        // End of location matching .
        // TODO
        // SearchApis::logData ( $person ['id'], $LocationAnalyzer->getLog() ,$comb);

        $workAnalyzer = new WorkAnalyzer($person);
        $companies = $workAnalyzer->extractPersonCompanies($person, $emailsCompanies);

        if (!empty($companies) && !empty($infoProfile['experience'])) {
            $status['work'] = 0;
            $status['work_status'] = 0;
            $status['work_matched'] = 1;
            $workDetails = [];
            foreach ($infoProfile['experience'] as $experience) {
                $work_check = $workAnalyzer->isWorkMatch($person, $this->combination, $experience['company'], $workDetails);
                if ($work_check['status']) {
                    $status['work'] = $experience['company'];
                    $status['work_status'] = 1;
                    $status['workDetails'] = $workDetails;
                    if (in_array('cm', $status['workDetails'])) {
                        $this->data['work']['identities']['cm'] = true;
                    }
                    $this->data['work']['matchWith'] = $experience['company'];
                    break;
                }
            }
        }

        $schoolAnalyzer = new SchoolAnalyzer($person, $this->combination);
        $schools = $schoolAnalyzer->extractPersonSchools($person);

        if (!empty($schools) && !empty($infoProfile['education'])) {
            $status['school'] = 0;
            $status['school_status'] = 0;
            $status['school_matched'] = 1;
            $schoolDetails=[];
            foreach ($infoProfile['education'] as $school) {
                $school_check = $schoolAnalyzer->isSchoolMatch($person, $this->combination, $school['school'], $schoolDetails);
                if ($school_check['status']) {
                    $status['school'] = $school['school'];
                    $status['school_status'] = 1;
                    $status['schoolDetails'] = $schoolDetails;
                    if (in_array('sc', $status['schoolDetails'])) {
                        $this->data['school']['identities']['sc'] = true;
                    }
                    $this->data['school']['matchWith'] = $school['school'];
                    break;
                }
            }
        }

        $t = microtime(true);
        // TODO
        // Yii::app ()->cache->set ( $cacheKey, $status, 300 );
        // if (get_class(Yii::app())=="CConsoleApplication" and Yii::app()->params['runCommandLog']){global $_start_time; echo number_format(microtime(true)-$_start_time,3) . " " . 'Class : '.__CLASS__.', Function : '.__METHOD__.', Mission : cache->set'.' Time : '.number_format(microtime(true)-$t,3).', File : '.__FILE__.', Line : '.__LINE__."<br>\n";debug_print_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);echo "\n\n";};

        return $this->data;
    }

    private function getPersonFirstNames($person , $as_array = false)
    {
        $first_names = "";
        if ($as_array) {
            $first_names = [];
        }
        if (!empty($person['searched_names'])) {
            $searched_names = explode(",", $person['searched_names']);
            foreach ($searched_names as $fullName) {
                $splittedNameIterator = loadService('nameInfo')->nameSplit(new \ArrayIterator([$fullname]));
                $splittedNameArray = iterator_to_array($splittedNameIterator);
                $fullNameArr =  $splittedNameArray[0]["splitted"][0]??[];
                if (!empty($fullNameArr['firstName'])) {
                    $first_name = $fullNameArr['firstName'];
                    if ($as_array) {
                        $first_names[] = strtolower($first_name);
                    } else {
                        $first_names .= '"'.$first_name.'" ';
                    }
                }
            }
        }
        return $first_names;
    }
}
