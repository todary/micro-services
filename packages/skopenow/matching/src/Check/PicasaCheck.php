<?php
namespace Skopenow\Matching\Check;

use Skopenow\Matching\Interfaces\CheckInterface;
use Skopenow\Matching\Status;

class PicasaCheck implements CheckInterface
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
        $status = [];
        $url = $this->url;
        $person = $this->person;
        $combination = $this->combination;
        // $info = $this->entry->getProfileInfo($url, 'picasa');
        $info = $this->info;
        $profileHtml = $info['profile'];
        $pattern = "/AF_initDataCallback.*?data:function\\(\\){return([^]]+])/";

        $result = null;

        if (preg_match($pattern, $profileHtml, $match)) {
            $result = html_entity_decode(($match[1]));
        }
        if ($result) {
            return true;
        }
        return false;
    }
}
