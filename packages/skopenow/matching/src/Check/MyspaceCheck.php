<?php
namespace Skopenow\Matching\Check;

use Skopenow\Matching\Interfaces\CheckInterface;
use Skopenow\Matching\{Status, RunAnalyzer};
use Skopenow\Matching\Match\{NameMatch, LocationMatch};
use Skopenow\Matching\Analyzer\{NameAnalyzer, LocationAnalyzer};

class MyspaceCheck implements CheckInterface
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
        $combination['urlProfile'] = $profile_url;
        $status = [];
        $check = [];
        $nameAnalyzer = new NameAnalyzer;
        $locationMatch = new LocationMatch($this->person, $this->combination);
        $locationAnalyzer = new LocationAnalyzer;

        $status["name"] = 0;
        $status["location"] = 0;

        if (isset($combination['htmlContent']) && is_array($combination['htmlContent'])) {
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
        if (
            empty($person['first_name']) &&
            empty($person['city'])
        ) {
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

        $options = [];
        $options["headers"] = [
            "Connection: close",
            "X-Requested-With: XMLHttpRequest",
            "X-Push-State-Request: true"
        ];
        // ,"timeout"=>100

        // TODO
        // SearchApis::logData($person['id'], "Checking myspace profile $profile_url", $combination);
        // $info = $this->entry->getProfileInfo($url, 'myspace', $this->htmlContent);
        $info = $this->info;

        $getImage = $info['image'];
        if ($getImage) {
            $status['image'] = $getImage;
        }

        if (count($info)) {
            // TODO
            // SearchApis::logData($person['id'], "Found search name to check myspace profile $profile_url", $combination);
            $realname = $info['name'];
            $nameDetails = [];
            $status['nameDetails'] = [];

            if ($realname != "") {
                $status["profile_name"] = $realname;
                $arrayOfName = explode(' ', $realname);
                if (count($arrayOfName) == 2) {
                    //$checkFirstNameNode = SearchApis::isNameMatchNode($person['id'],$person['first_name'],$arrayOfName[0]);
                    //$checkLastNameNode = SearchApis::isNameMatchNode($person['id'],$person['last_name'],$arrayOfName[1]);
                    $checkFirstNameNode = null;
                    $checkLastNameNode = (strtolower(trim($person['last_name'])) == strtolower(trim($arrayOfName[1])) ? true : false);
                    $analyzer = new RunAnalyzer($this->report);

                    if ($checkFirstNameNode == true && $checkLastNameNode == true) {
                        $status["name"] = 1;
                        $status["nameDetails"] = array('fn', 'ln');
                        $check['name'] = "Matched";
                        // TODO
                        // SearchApis::logData($person['id'], "Myspace profile name $profile_url is matched.", $combination);
                    } elseif (
                        $checkFirstNameNode == null &&
                        $checkLastNameNode == true &&
                        $analyzer->runNameAnalyzer($person, $combination, $realname, $nameDetails)
                    ) {
                        $status["name"] = 1;
                        $status["nameDetails"] = $nameDetails;
                        $check['name'] = "Matched";
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
                        // TODO
                        // SearchApis::logData($person['id'], "Myspace profile name $profile_url is Not matched.", $combination);
                    }
                }
            }
            if (array_key_exists('name', $check) == false || $check['name'] != "Matched") {
                $status["name"] = 0;
                $check['name']="Not Matched";
            }
        } else {
            $status["name"] = 1;
            $check['name'] = "Not Found";
            // TODO
            // SearchApis::logData($person['id'],"Not found search names for myspace profile $profile_url", $combination);
        }
        // $this->loadlocations($person);
        // $locations = $this->city;

        $locations = $locationAnalyzer->loadAllLocations($person);

        if (count($info['location'])) {
            // TODO
            // SearchApis::logData($person['id'], "Found search location to check myspace profile $profile_url", $combination);
            $city2 = (trim($info['location']));
            $state2 = (trim($info['location']));
            if (count($locations) > 0) {
                foreach ($locations as $locationdata) {
                    if (is_array($locationdata) && !empty($locationdata['locationName'])){
                        $locationName = "";
                        if (!empty($locationdata['locationName'])) {
                            $locationName = $address['locationName'];
                        }
                        if (isset($locationdata['bigCity'])) {
                            $status['bigCity'] = $locationdata['bigCity'];
                        }
                    } else {
                        $locationName = $locationdata;
                    }

                    if (!$locationName) {
                        continue;
                    }

                    $city1 = $locationName;
                    $state1 = $locationName;

                    $locationDetails = [];
                    $locationMatch->setCity1($city1);
                    $locationMatch->setCity2($city2);
                    $locationMatch->setState1($state1);
                    $locationMatch->setState2($state2);
                    $locationMatch->setLocationDetails($locationDetails);
                    if ($locationMatch->match()) {
                        $status["location"] = 1;
                        $status["locationDetails"] = $locationDetails;
                        if (empty($locationDetails)) {
                            continue;
                        }
                        $check['location'] = "Matched";
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

                        break;
                    } else {
                        $check['location'] = "Not Matched";
                        $status["location"] = 0;
                    }
                }
            } else {
                $status["location"] = 1;
                $check['location'] = "Empty Person Location";
                // TODO
                // SearchApis::logData($person['id'], "Empty Person Location.", $combination);
            }
        } else {
            $status["location"] = 1;
            $check['location'] = "Not Found";
            // TODO
            // SearchApis::logData($person['id'],"Not found search location for myspace profile $profile_url", $combination);
        }


        $profileLinks = $info['links']??[];
        $status['links'] = [];
        foreach ($profileLinks as $link) {
            list($source, $main_source) = $this->entry->determineSource($link);

            $status['links'][] = $link;
        }

        if ($status['links']) {
            $status['links'] = array_unique($status['links']);
        }
        /*  SearchApis::logData($person['id'],"Error finding curl profilehtml for $profile_url :{$this->htmlContent['error']}", $combination);
        $status["name"] = 0;
        $status["location"] = 0;
        $check['location']="Curl Error";
        $check['name']="Curl Error";*/

        $checkArray = [];
        $checkArray['result_url'] = $profile_url;
        $checkArray['check'] = $check;
        // TODO
        // Yii::app()->reportLog->resultCheck($checkArray, $person, $combination);
        unset($checkArray);
        return $this->data;
    }
}
