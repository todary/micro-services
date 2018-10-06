<?php
/**
 * Reports Manager
 *
 * PHP version 7.0
 *
 * @category Micro_Services-phase_1
 * @package  Reports Service
 * @author   Mahmoud Mgady <mahmoud.magdy@queentecksolutions.com>
 * @author   Mohammed Attya <mohammed.attya25@gmail.com>
 * @license  QueenTechSolutions, Inc.
 * @link     http://www.queentecksolutions.com
 **/
namespace Skopenow\Reports;

use Skopenow\Reports\Models\Report;
use App\Models\ResultData;
use Skopenow\Reports\Services\PeopleDataService;
use Skopenow\Reports\PeopleData\PeopleDataCriteriaBuilder;
use Skopenow\Reports\PeopleData\PeopleDataFilter;
use Skopenow\Reports\PeopleData\ReportData;
use Skopenow\Reports\Transformers\ReportTransformer;
use Skopenow\Reports\Transformers\ReportSuggestionTransformer;
use Skopenow\Reports\CombinationGenerators\CombinationsGeneratorsFactory;

/**
 *
 */
class ReportSuggester
{
    public function buildCriteria($report)
    {
        \Log::info('BRAIN: build peopleData Criteria');
        if (app()->environment(['production'])) {
            $criteria = (new PeopleDataCriteriaBuilder($report))->build();
            return $criteria;
        }
        // Default Case - search by all input data
        // for simplicity let's assume the user will input only one from each type
        $criteria = [];
        // for Local Testing add this key
        // 'sandbox' => true

        $level = ['apis' => ['tloxp']];

        // $level['sandbox'] = true;
        if (!app()->environment(['production'])) {
            $level['sandbox'] = true;
        }
        if (!empty($report->full_name)) {
            $level['name'] = $report->full_name[0];
        }

        if (!empty($report->city)) {
            $level['city'] = getCity($report->city[0]);
        }
        if (!empty($report->city)) {
            $level['state'] = getState($report->city[0]);
        }
        if (!empty($report->address)) {
            $level['address'] = $report->address[0];
        }
        if (!empty($report->phone)) {
            $level['phone'] = $report->phone[0];
        }
        if (!empty($report->email)) {
            $level['email'] = $report->email[0];
        }
        if (!empty($report->usernames)) {
            $level['username'] = $report->usernames[0];
        }
        if (!empty($report->age)) {
            $level['age'] = $report->age[0];
        }
        if (!empty($report->company)) {
            $level['company'] = $report->company[0];
        }
        if (!empty($report->school)) {
            $level['school'] = $report->school[0];
        }
        $criteria['trial1']['peopleData']['tlo'] = [$level];

        \Log::info('BRAIN: built peopleData Criteria');
        \Log::debug('BRAIN: PeopleData Criteria', $criteria);

        return $criteria;
    }

    public function getData(array $criteria)
    {
        \Log::info('BRAIN: before getting peopleData data');
        $service = new PeopleDataService;
        \Log::info('BRAIN: after getting peopleData data');
        return $service->search($criteria);
    }

    public function filterData(array $data, int $reportId) : array
    {
        $filter = new PeopleDataFilter($reportId);
        return $filter->filter($data);
    }

    public function takeDecision(array $data)
    {
        \Log::info('BRAIN: take decision');
        $count = count($data);
        $decision = ['decision' => '', 'data' => []];

        if ($count === 0) {
            $decision['decision'] = 'rejected';
            $decision['data'] = null;
        } elseif ($count === 1) {
            $decision['decision'] = 'accepted';
            $decision['data'] = $data;
        } elseif ($count > 1) {
            $decision['decision'] = 'suggestions';
            $decision['data'] = $data;
        }
        return $decision;
    }

    public function getSuggestions($reportId)
    {
        \Log::info('BRAIN: get suggestions');

        $report = Report::find($reportId);
        $criteria = $this->buildCriteria($report);
        $data = $this->getData($criteria);

        // dump($data);
        $filteredData = $this->filterData($data, $reportId);
        \Log::info('BRAIN: Transforming peopleData');
        \Log::debug('BRAIN: Transforming peopleData', $filteredData);
        $suggestions = (new ReportSuggestionTransformer())->transform($filteredData);
        $factory = new CombinationsGeneratorsFactory;
        $factory->make('peopleData', $suggestions)->generate($reportId);
        // // For Testing only
        if (!empty($suggestions) && !app()->environment(['production'])) {
            $suggestions[] = (new ReportTransformer())->transformToPeopleData($report);
        } elseif (empty($suggestions) && !empty($report->searched_names) && !empty($report->city)) {
            $suggestions[] = (new ReportTransformer())->transformToPeopleData($report);
        }
        return $suggestions;
    }

    public function acceptSuggestion($reportId, $data)
    {
        \Log::info('BRAIN: Accepting suggestions');
        config(['flags.initiating_report' => true]);
        \Log::debug('BRAIN: Accepting suggestions', $data??[]);

        // $reportsManager->updateReport($reportId, $choiceData);
        $data = json_decode(json_encode($data), true);
        $report = Report::find($reportId);
        $reverse = (empty($report->full_name) || empty($report->city));
        $personData = (new ReportTransformer())->transformToPeopleData($report);
        if (!empty($data)) {
            \Log::info('BRAIN: Merge Report Data with PeopleData');
            $personData = (new ReportData)->mergeReportPeopleData($personData, $data);
        }
        \Log::info('BRAIN: Create Datapoint With Report data and/or PeopleData');
        if ($reverse) {
            \Log::info('BRAIN: reverse -> update Report');
            $reportData = new ReportData;
            $reportData->updateReportData($reportId, $data);
        }
        \Log::info('BRAIN: Finish Accepting suggestions');
        return $personData;
    }
}
