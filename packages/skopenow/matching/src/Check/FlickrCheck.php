<?php
namespace Skopenow\Matching\Check;

use Skopenow\Matching\{Status, RunAnalyzer};
use Skopenow\Matching\Interfaces\CheckInterface;
use Skopenow\Matching\Analyzer\{
    WorkAnalyzer,
    SchoolAnalyzer,
    LocationAnalyzer
};

class FlickrCheck implements CheckInterface
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
        $combination['urlProfile'] = $profile_url;
        $status = [];
        $check = [];
        $this->data['name']['status'] = true;
        $this->data['location']['status'] = true;
        $this->data['work']['status'] = true;
        $this->data['school']['status'] = true;

        if (
            isset($combination['htmlContent']) &&
            is_array($combination['htmlContent'])
        ) {
            $this->htmlContent = $combination['htmlContent'];
            unset($combination['htmlContent']);
        }

        $status["name"] = 1;
        $status["location"] = 1;
        $status["image"] = 0;
        $status["found_name"] = 0;
        $status["found_location"] = 0;

        $uname = $this->entry->getUsername($profile_url);
        $profile_url = explode("/", $profile_url);
        $profile_url = $profile_url[count($profile_url) - 1];
        $profile_url = "https://www.flickr.com/people/" . $profile_url;
        if (
            $uname &&
            isset($person['usernames']) &&
            stripos(",{$person['usernames']},", ",{$uname},") !== false
        ) {
            // TODO
            // SearchApis::logData($person['id'], "This profile $profile_url comes from a manual/reverse username search, passed.", $combination);

            $check['name'] = "manual/reverse username search (passed)";
            $check['location'] = "manual/reverse username search (passed)";
            $checkArray = [];
            $checkArray['result_url'] = $profile_url;
            $checkArray['check'] = $check;
            // TODO
            // Yii::app()->reportLog->resultCheck($checkArray, $person, $combination);
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
        // TODO
        // SearchApis::logData($person['id'], "Checking TW profile $profile_url", $combination);

        // $info = $this->entry->getProfileInfo($profile_url, 'flickr', $this->htmlContent);
        $info = $this->info;
        if (!$info) {
            $status['name'] = $status['location'] = 0;
            $status ["status"] = 404;
            return $this->data;
        }

        if ($info) {
            $runAnalyzer = new RunAnalyzer($this->report);
            // Check Name ..
            if ($info['name']) {
                $status["found_name"] = 1;
                $status ["profile_name"] = $info ['name'];
                $nameDetails = [];
                $isNameMatch = $runAnalyzer->runNameAnalyzer(
                    $person,
                    $combination,
                    [$info['name']],
                    $nameDetails
                );
                $status['nameDetails'] = $nameDetails;
                if ($isNameMatch) {
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
                }
            }
            $locations = [$info['location']];

            $LocationAnalyzer = new LocationAnalyzer;
            $LocationAnalyzer($person, $locations);
            if ($LocationAnalyzer->isMatch()) {
                $status ["location"] = 1;
                $locationDetails = $LocationAnalyzer->getBestLocations();
                $status['locationDetails'] = $locationDetails;
                if (!empty($locationDetails)) {
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
            // SearchApis::logData ($person['id'], $LocationAnalyzer->getLog() ,$combination);

            if (!empty($info['instagram'])) {
                $status["instagram"] = $info['instagram'];
            }

            if (!empty($info['image'])) {
                $status["image"] = $info['image'];
            }

            // Check company and school
            if (!empty($info['bio'])) {
                $workAnalyzer = new WorkAnalyzer;
                $schoolAnalyzer = new SchoolAnalyzer;
                $workDetails = [];
                $statusWork = $workAnalyzer->isWorkMatch(
                    $person,
                    $combination,
                    $info['bio'],
                    $workDetails
                );
                if (in_array('cm', $workDetails)) {
                    $this->data['work']['identities']['cm'] = true;
                }
                $this->data['work']['matchWith'] = $workDetails['matchWith']??'';
                $schoolDetails = [];

                $statusWork = $schoolAnalyzer->isSchoolMatch(
                    $person,
                    $combination,
                    $info['bio'],
                    $schoolDetails
                );
                if (in_array('sc', $schoolDetails)) {
                    $this->data['school']['identities']['sc'] = true;
                }
                $this->data['school']['matchWith'] = $schoolDetails['matchWith']??'';
            }
        } else {
            $status["name"] = 1;
            $status["location"] = 2;
            // when location is missing Or not found in Profile .
            $check['location'] = "Empty profile";
            $check['name'] = "Empty profile";
            // TODO
            // SearchApis::logData($person['id'],"Empty profilehtml for TW $profile_url", $combination);
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
