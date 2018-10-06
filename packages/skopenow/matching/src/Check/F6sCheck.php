<?php
namespace Skopenow\Matching\Check;

use Skopenow\Matching\Interfaces\CheckInterface;
use Skopenow\Matching\{Status, RunAnalyzer};
use Skopenow\Matching\Services\ReportService;

class F6sCheck implements CheckInterface
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
        $url = $this->url;
        $person = $this->person;
        $combination = $this->combination;
        $names = [];
        $locations = [];
        $schools = [];
        $work_Exps = [];
        $runAnalyzer = new RunAnalyzer($this->report);
        $log = "Begin f6s.com check process for profile ({$url}).\n";

        // $profileInfo = $this->entry->getProfileInfo($url, 'f6s', $this->htmlContent);
        $profileInfo = $this->info;
        if (!empty($profileInfo['name'])) {
            $names[] = $profileInfo['name'];
        }
        if (!empty($profileInfo['location'])) {
            $locations[] = $profileInfo['location'];
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

        //...Logging
        $log .= "Names found ".print_r($names,true)."\n";
        $log .= "Locations found ".print_r($locations,true)."\n";
        $log .= "Check Status ".print_r($status,true)."\n";
        //....End Logging

        // TODO
        // SearchApis::logData($person['id'],$log,$combination);
        return $this->data;
    }
}
