<?php

use Skopenow\Reports\Services\ReverseService;
use Skopenow\Reports\Services\AccountsService;
use Skopenow\Reports\Services\SettingsService;
use Skopenow\Reports\Services\PeopleDataService;
use Skopenow\Reports\Services\ResultsService;
use Skopenow\Reports\Services\DatapointService;

use Skopenow\Reports\ReportsManager;
use Skopenow\Reports\Transformers\ReportTransformer;

/**
*
*/
class ReportsManagerTest extends TestCase
{
    public function setup()
    {
        $validationService = null;//loadService('validation');
        $combinationsService = null;//loadService('combinations');
        $nameInfoService = null;//loadService('nameInfo');
        $reverseService = null;//new ReverseService();
        $accountsService = null;//new AccountsService();
        $settingsService = null;//new SettingsService();
        $resultsService = null;//new ResultsService();
        $peopleDataService = null;//new PeopleDataService();
        $datapointService = new DatapointService();
        $this->reportsManager = new ReportsManager(
            $validationService,
            $reverseService,
            $settingsService,
            $accountsService,
            $combinationsService,
            $resultsService,
            $nameInfoService,
            $peopleDataService,
            $datapointService
        );
    }

    public function testGetReportNames()
    {
        $reportId = 60022;
        $names = $this->reportsManager->getReportNames($reportId);
        dd($names);
    }
}
