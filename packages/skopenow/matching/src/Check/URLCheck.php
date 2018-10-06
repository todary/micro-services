<?php
namespace Skopenow\Matching\Check;

use Skopenow\Matching\Interfaces\CheckInterface;
use Skopenow\Matching\RunAnalyzer;
use Skopenow\Matching\Status;
use Skopenow\Matching\Services\ReportService;

class URLCheck implements CheckInterface
{
    private $info;
    private $entry;
    private $data;
    private $url;
    private $person;
    private $combination;
    private $htmlContent = [];
    private $source;

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

    public function setSource()
    {
        $this->source = $source;
    }

    public function check()
    {
        //...Istantiate variables
        $this->data['name']['status'] = true;
        $this->data['location']['status'] = true;
        $url = $this->url;
        $personModel = $this->person;
        $combination = $this->combination;
        $runAnalyzer = new RunAnalyzer($this->report);
        $data = [
            'status' => true,
            'is_checked' => false,
            'checkStatus' => [
                'name' => true,
                'location' => true,
                'locationDetails' => [],
                'nameDetails' => [],
            ],
            'data' => []
        ];
        $locations = [];
        $matchName = true;
        $matchLocation = true;

        list($data['source'], $data['main_source']) = $this->entry->determineSource($url);

        if (!empty($data['main_source']) ) {
            $profileInfo = $this->info;
            $data['data'] = $profileInfo ? $profileInfo : [];
            $data['checkStatus']['name'] = $analyzer->runNameAnalyzer(
                $personModel,
                $comb,
                $profileInfo['name'],
                $data['checkStatus']['nameDetails']
            );
            $nameDetails = $data['checkStatus']['nameDetails'];
            $this->data['name']['matchWith'] = $nameDetails['matchWith']??'';
            $this->data['name']['identities']['input_name'] = $nameDetails['input_name']??false;

            if (in_array('fn', $nameDetails)) {
                $this->data['name']['identities']['fn'] = true;
            }

            if (in_array('mn', $nameDetails)) {
                $this->data['name']['identities']['mn'] = true;
            }
            if (in_array('IN', $nameDetails)) {
                $this->data['name']['identities']['input_name'] = true;
            }

            if (in_array('ln', $nameDetails)) {
                $this->data['name']['identities']['ln'] = true;
            }

            if (in_array('unq_name', $nameDetails)) {
                $this->data['name']['identities']['unq_name'] = true;
            }
            if (in_array('fzn', $nameDetails)) {
                $this->data['name']['identities']['fzn'] = true;
            }

            if (is_array($profileInfo['location'])) {
                $locations = $profileInfo['location'];
            } else {
                $locations[] = $profileInfo['location'];
            }

            $data['checkStatus']['location'] = $analyzer->runLocationAnalyzer(
                $personModel,
                $comb,
                $locations,
                $data['checkStatus']['locationDetails']
            );
            $locationDetails = $data['checkStatus']['locationDetails'];
            $input = $locationDetails['locations'][0]??'';
            $status['locationDetails'] = $locationDetails['locationDetails']??[];
            if (in_array('st', $locationDetails)) {
                $this->data['location']['identities']['st'] = true;
            }
            if (in_array('pct', $locationDetails)) {
                $this->data['location']['identities']['pct'] = true;
            }

            if (in_array('matchTypeName', $locationDetails)) {
                if ($locationDetails['matchTypeName'] == 'SmallCityWithSmallCity') {
                    $this->data['location']['identities']['exct-sm'] = true;
                } elseif ($locationDetails['matchTypeName'] == 'BigCityWithBigCity') {
                    $this->data['location']['identities']['exct-bg'] = true;
                }
            }
            $this->data['location']['matchWith'] = $input;
            return $this->data;
        }
    }
}
