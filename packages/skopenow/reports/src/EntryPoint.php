<?php
/**
 * Http Requests client code
 *
 * PHP version 7.0
 *
 * @category Micro_Services-phase_1
 * @package  Reports Service
 * @author   Mahmoud Mgady <mahmoud.magdy@queentecksolutions.com>
 * @license  QueenTechSolutions, Inc.
 * @link     http://www.queentecksolutions.com
 **/
namespace Skopenow\Reports;

use Skopenow\Reports\Models\Report;
use Skopenow\Reports\Services\ReverseService;
use Skopenow\Reports\Services\SettingsService;
use Skopenow\Reports\Services\PeopleDataService;
use Skopenow\Reports\Services\ResultsService;
use Skopenow\Reports\Services\DatapointService;
use Skopenow\Reports\Services\AuthorizationService;
use Skopenow\Reports\PeopleData\DatapointHandler;
use Skopenow\Reports\Transformers\ReportTransformer;
use Skopenow\Reports\Transformers\ReportSuggestionTransformer;

use Skopenow\Reports\SearchRequestProcessor;
use Skopenow\Reports\ReportsStore;
use Skopenow\Reports\SearchManager;
use Skopenow\Reports\ReportSuggester;
use App\Libraries\DBCriteria;
use App\Libraries\SearchAccount;

use Skopenow\Reports\CombinationGenerators\CombinationsGeneratorsFactory;

/**
 * Reports Service Entry Point
 *
 * @category Micro_Services-phase_1
 * @package  Reports Service
 * @author   Mahmoud Mgady <mahmoud.magdy@queentecksolutions.com>
 * @license  QueenTechSolutions, Inc.
 * @link     http://www.queentecksolutions.com
 **/
class EntryPoint
{
    protected $reportsManager;

    public function __construct()
    {
        // $validationService = loadService('validation');
        // $combinationsService = loadService('combinations');
        // $nameInfoService = loadService('nameInfo');
        // $reverseService = new ReverseService();
        // $settingsService = new SettingsService();
        // $resultsService = new ResultsService();
        // $peopleDataService = new PeopleDataService();


        $accountsService = new SearchAccount();
        $settingsService = new SettingsService();
        $authorizationService = new AuthorizationService();
        $combinationsGeneratorsFactory = new CombinationsGeneratorsFactory();


        $datapointService = new DatapointService();
        $this->reportsStore = new ReportsStore($datapointService);
        $this->searchManager = new SearchManager($accountsService, $settingsService, $authorizationService, $combinationsGeneratorsFactory, $this->reportsStore);
    }

    public function generateReport(array $postData, bool $isApi = false, array $apiOptions = [], string $defaultSearchOrigin = 'search')
    {
        $searchRequestProcessor = new SearchRequestProcessor($postData, loadService('nameInfo'));
        $isValid = $searchRequestProcessor->isValidRequest();


        if (!$isValid) {
            $errors = $searchRequestProcessor->getValidationErrors();
            $this->setErrors($errors);
            return false;
            // throw new \Exception("Error Processing Request", $errors);
        }

        $report = $searchRequestProcessor->getReport();
        $userId = config('state.user_id');
        $this->searchManager->prepareReport($report, $userId, $isApi, $apiOptions, $defaultSearchOrigin);
        $report->version = config('state.version');
        $report->save();

        return $report->id;
    }

    protected function setErrors($errors)
    {
        $this->errors = $errors;
    }

    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * TEMPORARY Parameter $temp
     * Pass type of data returned
     * multi
     * one
     * empty
     * @param  [type] $reportId [description]
     * @param  [type] $temp     [description]
     * @return [type]           [description]
     */
    public function getSuggestions($reportId)
    {
        config(['state.report_id' => $reportId]);
        $reportSuggester = new ReportSuggester();
        return $reportSuggester->getSuggestions($reportId);
    }

    /*public function storeSuggestions($suggestions)
    {
        return $reportsSuggester->storeSuggestions($suggestions);
    }*/

    public function acceptSuggestion($reportId, $data)
    {
        config(['state.report_id' => $reportId]);
        $suggestioner = new ReportSuggester;
        return $suggestioner->acceptSuggestion($reportId, $data);
    }

    public function updateApiOptions(int $reportId, array $api_options) : bool
    {
        $report = Report::find($reportId);
        if (empty($report)) {
            $this->setErrors('No Report associated with this id: ' . $reportId);
            return false;
        }
        try {
            $report->api_options = json_encode($api_options);
            $report->save();
            return true;
        } catch (\Exception $e) {
            $this->setErrors($e->getMessage());
            return false;
        }
    }

    public function startSearch($reportId)
    {
        config(['state.report_id' => $reportId]);
        $this->searchManager->startSearch($reportId);
    }

    public function onSearchComplete()
    {
        $reportId = config('state.report_id');
        $this->searchManager->onSearchComplete($reportId);
    }

    public function afterSearchComplete()
    {
        $reportId = config('state.report_id');
        $this->searchManager->afterSearchComplete($reportId);
    }

    public function deleteReport()
    {
        $reportId = config('state.report_id');
        return $reportsStore->deleteReport($reportId);
    }

    public function getReport()
    {
        $reportId = config('state.report_id');
        $report = $this->reportsStore->getReportById($reportId);
        $reportTransformer = new ReportTransformer();
        return $reportTransformer->transform($report);
    }

    public function getReportInputNames()
    {
        $reportId = config('state.report_id');
        $report = $this->reportsStore->getReportById($reportId);
        return $report->full_name;
    }

    public function getReportRelatives()
    {
        $reportId = config('state.report_id');
        return $this->reportsStore->getReportRelatives($reportId);
    }

    public function getReportNames()
    {
        $reportId = config('state.report_id');
        return $this->reportsStore->getReportNames($reportId);
    }

    public function getNickNames()
    {
        $reportId = config('state.report_id');
        return $this->reportsStore->getNickNames($reportId);
    }

    public function getReportOtherNames()
    {
        $reportId = config('state.report_id');
        return $this->reportsStore->getReportOtherNames($reportId);
    }

    public function getReportLocations()
    {
        $reportId = config('state.report_id');
        return $this->reportsStore->getReportLocations($reportId);
    }

    public function handleInsertedDatapointCombination(array $data)
    {
        $reportId = $data['state']['report_id'];
        $source = 'datapoint';
        \Log::info('BRAIN: Create DataPoint Combination');
        $this->searchManager->createSourceCombinations(
            $reportId,
            $source,
            $data
        );
    }

    public function handleUpdatedDatapointCombination(array $data)
    {
        $reportId = $data['state']['report_id'];
        $source = 'updatedDatapoint';
        \Log::info('BRAIN: Create updated DataPoint Combination');
        $this->searchManager->createSourceCombinations(
            $reportId,
            $source,
            $data
        );
    }

    public function handleMainResult($data)
    {
        $reportId = $data['state']['report_id'];
        $source = 'mainResult';
        \Log::info('BRAIN: Create Main result Combination');
        $this->searchManager->createSourceCombinations(
            $reportId,
            $source,
            $data
        );
        return;
    }

    public function getPeopleData($reportId)
    {
        config(['state.report_id' => $reportId]);
        $suggestioner = new ReportSuggester;
        $criteria = $suggestioner->buildCriteria($reportId, 'multi');
        $data = $suggestioner->getData($criteria);
        $filteredData = $suggestioner->filterData($data);
        return $suggestioner->takeDecision($filteredData);
    }

    public function isVerifiedDataPoints(int $reportId, array $types = []) : bool
    {
        $dataPointService = loadService('datapoint')->datasource();
        $DBCriteriaReport = new DBCriteria();
        $DBCriteriaReport->compare('report_id', $reportId);
        if (!empty($types)) {
            $DBCriteriaReport->addInCondition('type', $types);
        }
        $DBCriteria = new DBCriteria();
        $DBCriteria->compare('is_verified', 1);
        $DBCriteria->compare('is_input', 1, null, 'OR');
        $DBCriteriaReport->mergeWith($DBCriteria);
        $datapoints = $dataPointService->loadData($DBCriteriaReport);
        return count($datapoints)? true: false;
    }

    public function addDatapoint($data)
    {
        $handler = new DatapointHandler;
        $reportID = config('state.report_id');
        $handler->addDatapoint($data, $reportID);
    }

    public function addEmailUsernamesDatapoint(array $data, string $source)
    {
        $suggester = new DatapointHandler;
        $suggester->addEmailUsernamesDatapoint($data, $source);
    }

    public function generateResultsCombination(array $data, int $reportId)
    {
        $source = 'peopledata';
        \Log::info('BRAIN: Create Result Combination');
        $this->searchManager->createSourceCombinations(
            $reportId,
            $source,
            $data
        );
        return;
    }
}
