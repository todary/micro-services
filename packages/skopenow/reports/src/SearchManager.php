<?php
namespace Skopenow\Reports;

use App\Models\Result;
use Skopenow\Reports\Models\Report;
use Skopenow\Reports\Models\ReportInfo;
use App\Models\Report as ReportModel;

/**
*
*/
class SearchManager
{
    public function __construct(
        $accountsService,
        $settingsService,
        $authorizationService,
        $combinationsGeneratorsFactory,
        $reportsStore
    ) {
        $this->reportsStore = $reportsStore;
        $this->accountsService = $accountsService;
        $this->settingsService = $settingsService;
        $this->authorizationService = $authorizationService;
        $this->combinationsGeneratorsFactory = $combinationsGeneratorsFactory;
    }

    /**
     * Prepare report before search
     * @param  Report  $report              [description]
     * @param  [type]  $userId              [description]
     * @param  boolean $isApi               [description]
     * @param  [type]  $apiOptions          [description]
     * @param  string  $defaultSearchOrigin [description]
     * @return [type]                       [description]
     */
    public function prepareReport(Report $report, $userId, $isApi = false, $apiOptions = null, $defaultSearchOrigin = 'search')
    {
        $report->profiles_in_results = 1;
        $report->search_origin = $defaultSearchOrigin;
        $report->search_type = "full";

        if ($isApi) {
            $report->is_api = 1;

            if ($apiOptions) {
                $report->api_options = json_encode($apiOptions);
            }
        }

        $account = $this->accountsService->getUserAccount($userId);
        $user = $account->user;
        if ($corporate = $this->accountsService->getUserCorporate($userId)) {
            $report->corporate_id = $corporate->id;
            $report->service_id = $corporate->service_id;
        } else {
            $report->service_id = 2;
        }

        $report->is_premium_search = $this->accountsService->isPremiumSearchEnabled($userId)?1:0;

        $isCorporate = $corporate ? true : false;
        $report->search_credit_count = $this->settingsService->getSearchCreditCount($report->service_id, $report->is_premium_search, $isCorporate);

        if ($user->corporate_department_id) {
            $report->department_id = $user->corporate_department_id;
        }

        $report->user_ip = request_ip();
        $report->user_agent = request_user_agent();

        $report->user_id = $userId;
        $report->insert_date = time();

        return $report;
    }

    public function startSearch($reportId)
    {
        \Log::info('REPORT: start search');

        $report = Report::find($reportId);
        if (! $report->is_paid) {
            $this->accountsService->payForSearch($report->user_id, $reportId);
        }

        $report->is_hidden = '0';
        $report->save();

        $env = env('APP_ENV');

        if (1 || $env == 'local') {
            \Log::info('REPORT: local -> storeInitialCombinations');
            $this->storeInitialCombinations($reportId);
        } else {
            \Log::info('REPORT: online -> startLambda');
            $this->startLambda($report->id, $report->user_id);
        }
    }

    protected function startLambda($reportId, $userId, $isBackground = true)
    {
        $trials = 3;
        $start_time = time();

        $credentials = new \Aws\Credentials\Credentials(env('AWS_KEY'), env('AWS_SECRET'));

        $lambda = \Aws\Lambda\LambdaClient::factory(array(
                'credentials' => $credentials,
                'region' => env('AWS_REGION'),
                'version' => '2015-03-31',
                'retries' => 3,
        ));

        $message = json_encode(array("type"=>"save_combs", "id"=>$reportId, "userID"=>$userId,"server"=>json_encode($_SERVER)));

        $event = array(
            'Records' => array(
                array('Sns'=>array('Message'=>$message))
            ),
        );

        $result = $lambda->invoke([
            'FunctionName' => env('lambda_function_ARN'),
            'InvocationType' => ($isBackground)?'Event':'RequestResponse',
            'LogType' => 'Tail',
            'Payload' => json_encode($event),
        ]);

        if ($result && $result->get('StatusCode')==200) {
            $response = $result->get('Payload')->getContents();
            $return = json_decode($response);
        } else {
            $return = "";
        }
    }

    public function onSearchComplete($reportId)
    {
        $results = Result::where('report_id', $reportId)->get();
        $report = ReportModel::find($reportId);
        $relationships = $report->relationships;
        $pendingResults = loadService('result')->getAllFromPending($reportId)??[];
        $cleanupService = loadService('cleanup', [$results??[], $relationships??[], $pendingResults]);
        $cleanupService->process();

        if (!$report->is_void
            && !$report->is_premium_search
            && $report->score < $this->settingsService->getChargeScore()
        ) {
            $this->accountsService->refundSearch($reportId);
        }

        $report->completed = 1;
        $report->save();
    }

    public function afterSearchComplete(int $reportId)
    {
        $report = Report::find($reportId);
        $this->fillReportInfo($reportId);
        // $this->generateEvents();
        if ($report->is_rescan) {
            $this->sendRescanCompletedEmail($reportId);
        }
        // $this->generatePdf($reportId);
        // $this->getDataFromApi($reportId);
        // $this->generateEvents();
    }

    protected function fillReportInfo(int $reportId)
    {
        $reportsStore = $this->reportsStore;
        $report = Report::find($reportId);
        $reportInfo = ReportInfo::where('report_id', $reportId)->first();
        if (!$reportInfo) {
            $reportInfo = new ReportInfo();
        }

        $reportInfo->report_id = $report->id;
        $reportInfo->subject_name = $report->searched_names;
        $reportInfo->school = $report->school;
        $reportInfo->occupation = $report->company;
        $reportInfo->email = $report->email;
        $reportInfo->usernames = $report->usernames;
        $reportInfo->phone = $report->phone;
        $reportInfo->search_date = time();


        $mainSources = \DB::table('main_source')->pluck('id');

        $sourceIds = \DB::table('result')->select('source_id')
                         ->where('report_id', $reportId)
                         ->where('is_deleted', 0)
                         ->where('invisible', 0)
                         ->whereIn('raw_type', ['result', 'list'])
                         ->whereIn('source_id', $mainSources)
                         ->groupBy('source_id')
                         ->pluck('source_id');
        $mainSources = \DB::table('main_source')->whereIn('id', $sourceIds)->pluck('name');

        $options = [];
        foreach ($mainSources as $mainSource) {
            $options[$mainSource] = 1;
        }
        // $options = array_combine(array_keys($trackSources),array_fill(0, count($trackSources), 0));
        // $sourcesNames = array_keys($trackSources);
        // if ($sourcesNames) {
        //     $bridge_criteria = new Search\Helpers\Bridges\BridgeCriteria();
        //     $bridge_criteria->select = 'main_source, count(id) as related_to';
        //     $bridge_criteria->compare('person_id',$id);
        //     $bridge_criteria->compare('is_deleted',0);
        //     $bridge_criteria->compare('invisible',0);
        //     $bridge_criteria->addInCondition('main_source',$sourcesNames);
        //     $bridge_criteria->addInCondition('raw_type',['result','list']);
        //     $bridge_criteria->group = 'main_source';
        //     $result_bridge = new Search\Helpers\Bridges\ResultsBridge($id);
        //     $vc = $result_bridge->getAll($bridge_criteria);

        //     foreach ($vc as $sourceData) {
        //         if (!$sourceData['related_to']) continue;
        //         $options[$sourceData['main_source']] = 1;
        //     }
        // }

        // $sources = $resultsService->getVisibleResultsSources();
        // $mainSources = $this->sourcesService->getMainSources($sources);
        $reportInfo->options = $options;

        //progress data
        $reportInfo->addresses_data = $reportsStore->getReportLocations($reportId);
        $reportInfo->phones_data = $reportsStore->getReportPhones($reportId);
        $reportInfo->emails_data = $reportsStore->getReportEmails($reportId);
        $reportInfo->relatives_data = $reportsStore->getReportRelatives($reportId);

        $account = $this->accountsService->getUserAccount($report->user_id);
        if ($account->user) {
            $reportInfo->report_by = $account->user->name;
        }

        if ($account->corporate) {
            $reportInfo->company_name = $account->corporate->name;
        }

        $reportInfo->current_address = $reportInfo->addresses_data[0]??'';
        $reportInfo->previous_locations = array_slice($reportInfo->addresses_data, 1);

        // $reportInfo->additional_notes = SearchApis::formatVideosToAddInAdditionalNotes($id);
        $reportInfo->social_footprint = 1;

        if (!empty($reportInfo->addresses_data)) {
            $reportInfo->social_footprint = 0;
        }

          $reportInfo->save();
    }

    public function storeInitialCombinations(int $reportId)
    {
        \Log::info('REPORT: start initials combinations');
        $enabledSources = $this->settingsService->getEnabledSources();
        $enabledSources[] = 'commonCombinations';
        $maxAllowedCombinations = $this->settingsService->getMaxCominationsCount();
        $report = Report::find($reportId);
        $reverseSources = $report->reverse_source??[];
        $combinationsCount = 0;
        \Log::info('REPORT: before generating combinations');
        foreach ($enabledSources as $source) {
            if (!in_array($source, $reverseSources) && $this->authorizationService->canUseSource($source)) {
                $combinationsCount += $this->createSourceCombinations($reportId, $source);
                // if ($combinationsCount > $maxAllowedCombinations) {
                //     break;
                // }
            }
        }
        \Log::debug('REPORT: combinations count', [$combinationsCount]);
    }

    public function createSourceCombinations(int $reportId, string $sourceKey, $data = null)
    {
        \Log::info('REPORT: generating combinations of ' . $sourceKey);
        $sourceCombinationsGenerator = $this->combinationsGeneratorsFactory->make($sourceKey, $data);
        $sourceCombinationsGenerator->generate($reportId);
    }
}
