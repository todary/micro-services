<?php
namespace Skopenow\Matching\Check;

use Skopenow\Matching\Interfaces\CheckInterface;
use Skopenow\Matching\{Status, RunAnalyzer};

class YoutubeCheck implements CheckInterface
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
        $result = $this->result;
        $status = [];
        $check = [];
        $this->data['name']['status'] = true;


        // TODO
        // searchApis::logData($person['id'],"Match Youtube name with search name ".$result['name'],$combination);

        $sname = $result['name'];
        $realname = trim($sname);
        if (!empty($realname)) {
            $namesOnly = true;
            if (isset($combination['unique_name']) && $combination['unique_name']) {
                $namesOnly = false;
            }
            $params = [
                'person' => $person,
                'combination' => $combination,
                'found_name' => $realname,
                'NameExact' => true,
                'namesOnly' =>$namesOnly,
            ];
            $runAnalyzer = new RunAnalyzer($this->report);
            $status['nameDetails'] = [];

            $status['name'] = $runAnalyzer->runNameAnalyzer(
                $person,
                $combination,
                [$realname],
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

        } else {
            $status['name'] = 0;
            $check['name'] = "Not Found";
            // TODO
            // searchApis::logData($person['id'],"Youtube profile name Not Found \n");
        }

        if ($result['image']) {
            $status['image'] = $result['image'];
        }
        $checkArray = [];
        $checkArray['check'] = $check;

        // TODO
        // Yii::app()->reportLog->resultCheck($checkArray,$person,$combination);

        return $this->data;
    }
}
