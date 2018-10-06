<?php
namespace Skopenow\Matching\Check;

use Skopenow\Matching\Interfaces\CheckInterface;
use Skopenow\Matching\{Status, RunAnalyzer};


class SoundcloudCheck implements CheckInterface
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
        $combination = $this->combination;
        $person = $this->person;
        $status = [];
        $status['name'] = 1;
        $status['location'] = 1;
        $status['found_location'] = 0;
        $check = [];
        $runAnalyzer = new RunAnalyzer($this->report);
        $this->data['name']['status'] = true;
        $this->data['location']['status'] = true;

        // TODO
        // SearchApis::logData($person['id'], "Match soundcloud name with search name" .$searchedPerson['full_name'],$combination);
        //
        // $info = $this->entry->getProfileInfo($this->url, 'soundcloud');
        $info = $this->info;
        $sname = $info['name'];
        $realname = trim($sname);
        if ($realname != "") {
            $status['nameDetails'] = [];

            $status['name'] = $runAnalyzer->runNameAnalyzer(
                $person,
                $combination,
                $realname,
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

            if ($status['name']) {
                $check['name'] = "Matched";
            } else {
                // TODO
                // SearchApis::logData($person['id'], "Soundcloud profile name $realname is not matched . \n", $combination);
                $status['name']=0;
                $check['name']="Not Matched" ;
            }
        } else {
            $status['name'] = 0 ;
            $check['name'] = "Not Found";
            // TODO
            // SearchApis::logData($person['id'], "Name Not Found In Soundcloud Profile .",$combination);
        }
        $status['locationDetails'] = [];
        $status['location'] = $runAnalyzer->runLocationAnalyzer(
            $person,
            $combination,
            $info['location'],
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

        if ($status['location']) {
           $check['location']="Matched";
           $status["found_location"] = 1;
        } else {
            // when location is missing or not found in profile .
            $status["location"] = 0 ;
            $status["found_location"] = 0;
            $check["location"] = "Not Found";
            // TODO
            // SearchApis::logData($person['id'], "Location Not Found in Soundcloud Profile .", $combination);
        }

        // log the check data
        $checkArray = [];
        $checkArray['check'] = $check;
        // TODO
        // Yii::app()->reportLog->resultCheck($checkArray,$person,$combination);
        unset($checkArray);

        return $this->data;
    }
}
