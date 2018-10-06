<?php
namespace Skopenow\Matching\Check;

use Skopenow\Matching\Interfaces\CheckInterface;
use Skopenow\Matching\Analyzer\{WorkAnalyzer, SchoolAnalyzer};
use Skopenow\Matching\{Status, RunAnalyzer};
use Skopenow\Matching\Match\LocationMatch;

class TwitterCheck implements CheckInterface
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

    public function setResultsCount($resultsCount)
    {
        $this->resultsCount = $resultsCount;
    }

    public function check()
    {
        $profile_url = $this->url;
        $person = $this->person;
        $comb = $this->combination;
        $resultsCount = $this->resultsCount;
        $this->data['name']['status'] = true;
        $this->data['location']['status'] = true;
        $this->data['work']['status'] = true;
        $this->data['school']['status'] = true;
        $this->data['username']['status'] = true;
        $comb['urlProfile'] = $profile_url;
        $status = [];
        $check = [];

        if (isset($comb['htmlContent']) && is_array($comb['htmlContent'])) {
            $this->htmlContent = $comb['htmlContent'];
            unset($comb['htmlContent']);
        }

        $status ["status"] = 0;
        $status["name"] = 1;
        $status["location"] = 1;
        $status["work"] = 0;
        $status["school"] = 0;
        $status["found_name"] = 0;
        $status["found_location"] = 0;
        $status['onlyOne'] = 0;

        $url = $profile_url;
        $uname = $this->entry->getUsername($url);

        // Check if name and location is empty,set log and return $status .
        if (empty($person['first_name']) && empty($person['city'])) {
            $check['name'] = "empty first_name && city";
            $check['location'] = "empty first_name && city";
            $checkArray = [];
            $checkArray['result_url'] = $profile_url;
            $checkArray['check'] = $check;
            // TODO
            // Yii::app()->reportLog->resultCheck($checkArray, $person, $comb);
            unset($checkArray);
            return $this->data;
        }
        // TODO
        // SearchApis::logData($person['id'], "Checking TW profile $profile_url", $comb);
        //
        // $info_twitter = $this->entry->getProfileInfo($profile_url, 'twitter', $this->htmlContent);
        //
        $info_twitter = $this->info;

        ## To fix problem get 404 page from twitter
        ## check twitter which came from linkedin profile form this search
        ## https://beta2384.skopenow.com/report/r-67MtksOY3u0JgL__GnHRRKkS92SVdHQWRufywhf02H4
        ## Added at 16/11/2017
        if (!$info_twitter) {
            $status['name'] = $status['location'] = 0;
            $status ["status"] = 404;
            return $this->data;
        }
        $runAnalyzer = new RunAnalyzer($this->report);

        if ($info_twitter) {

            // if found location
            if ($info_twitter['location'])
            {
                $status["found_location"] = 1;
            }

            // Check Name ..
            if ($info_twitter['name']) {
                $status["found_name"] = 1;
                $status ["profile_name"] = $info_twitter ['name'];
                $nameDetails = [];
                $status['nameDetails'] = [];
                $status['name'] = $runAnalyzer->runNameAnalyzer(
                    $person,
                    $comb,
                    [
                        $info_twitter['name'],
                        str_replace(['_', '.'], ' ', $uname)
                    ],
                    $status["nameDetails"]
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

            } else {
                $status["name"] = 1;
                $check['name'] = "Not Found";
            }
            $status['locationDetails'] = [];
            $locations = [$info_twitter['location']];
            $status['location'] = $runAnalyzer->runLocationAnalyzer(
                $person,
                $comb,
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


            // if the profile is onlyOne ,it  doesn't matter the location of the account
            if ($resultsCount == 1) {
                $status['location'] = 1;
                $status['onlyOne'] = 1;
            }


            if (!empty($info_twitter['link'])) {
                $status["link"] = $info_twitter['link'];
            }

            if ($info_twitter['image']) {
                $status["image"] = $info_twitter['image'];
            }

            // Check company and school
            if (!empty($info_twitter['bio'])) {
                $workDetails = [];
                $workAnalyzer = new WorkAnalyzer($person);
                $workAnalyzer->setWork($info_twitter['bio']);
                $workAnalyzer->setWorkDetails($workDetails);
                if ($workAnalyzer->isMatch()) {
                    $status["work"] = true;
                    $status["workDetails"] = $workDetails;
                    if (in_array('cm', $status['workDetails'])) {
                        $this->data['work']['identities']['cm'] = true;
                    }
                    $this->data['work']['matchWith'] = $status['workDetails']['matchWith']??'';
                }
                $schoolDetails = [];
                $schoolAnalyzer = new SchoolAnalyzer($person, $comb);
                $schoolAnalyzer->setSchool($info_twitter['bio']);
                $schoolAnalyzer->setSchooLDetails($schoolDetails);
                if ($schoolAnalyzer->isMatch()) {
                    $status["school"] = true;
                    $status["schoolDetails"] = $schoolDetails;
                    if (in_array('sc', $status['schoolDetails'])) {
                        $this->data['school']['identities']['sc'] = true;
                    }
                    $this->data['school']['matchWith'] = $status['schoolDetails']['matchWith']??'';
                }
            }
        }
        // Code couldn't get inside else
        /*else {
            $status["name"] = 1;
            $status["location"] = 2;

            ## when location is missing Or not found in Profile .
            $check['location']="Empty profile";
            $check['name']="Empty profile";
            SearchApis::logData($person['id'],"Empty profilehtml for TW $profile_url", $comb);
        }*/

        if (
            $uname &&
            isset($person['usernames']) &&
            stripos(",{$person['usernames']},", ",{$uname},") !== false
        ) {
            // TODO
            // SearchApis::logData($person['id'], "This profile $url comes from a manual/reverse username search, passed.", $comb);
            $status['name'] = 1;
            $status['location'] = 1;
            $this->data['username']['identities']['un'] = true;
            $this->data['username']['identities']['input_un'] = true;
            $this->data['username']['matchWith'] = $uname;
        }
        return $this->data;
    }
}
