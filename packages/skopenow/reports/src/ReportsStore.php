<?php
namespace Skopenow\Reports;

use Skopenow\Reports\Models\Report;

/**
*
*/
class ReportsStore
{
    public function __construct($datapointService)
    {
        $this->datapointService = $datapointService;
    }

    public function update($reportId, $reportData)
    {
        return Report::find($reportId)->update($reportData);
    }

    public function getReportRelatives(int $reportId)
    {
        $relatives = $this->datapointService->getReportRelatives($reportId);
        return $relatives;
    }

    public function getReportNames(int $reportId)
    {
        $names = $this->datapointService->getReportNames($reportId);
        return $names;
    }

    public function getReportOtherNames(int $reportId)
    {
        $names = $this->datapointService->getReportOtherNames($reportId);
        return $names;
    }


    public function getReportPhones(int $reportId)
    {
        $phones = $this->datapointService->getReportPhones($reportId);
        return $phones;
    }


    public function getReportEmails(int $reportId)
    {
        $emails = $this->datapointService->getReportEmails($reportId);
        return $emails;
    }

    public function getReportLocations(int $reportId)
    {
        $report = Report::find($reportId);
        $locations = $this->datapointService->getReportLocations($reportId);
        $locations = array_unique(array_merge($locations, $report->city));
        return $locations;
    }

    public function getReportById(int $id)
    {
        $report = Report::find($id);
        return $report;
    }

    public function getNickNames(int $reportId)
    {
        $reportId = config('state.report_id');
        return $this->datapointService->getNickNames($reportId);
    }
}
