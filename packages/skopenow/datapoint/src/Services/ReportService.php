<?php
namespace Skopenow\Datapoint\Services;

/**
 *
 */
class ReportService
{
    /**
     * summary
     */
    public static function getReport()
    {
        $reportService = loadService('reports');
        return $reportService->getReport();
    }
}
