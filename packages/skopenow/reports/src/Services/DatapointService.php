<?php
namespace Skopenow\Reports\Services;

// require_once(__DIR__.'/../../../../../../framework/yii.php');

/**
*
*/
use App\Libraries\DBCriteria;

class DatapointService
{
    private $datasource;

    public function __construct()
    {
        $service = loadService('datapoint');
        $this->datasource = $service->datasource();
    }

    public function getReportRelatives(int $reportId)
    {
        config(['state.report_id' => $reportId]);
        // $prog = $this->datasource->loadProgress('relatives', false);
        // dd($prog);
        // $relatives = json_decode($prog['relatives_data'], true);
        // $data = [];
        // if ($relatives) {
        //     foreach ($relatives as $relative) {
        //         $data[] = $relative['name'];
        //     }
        // }
        $dataPointService = loadService('datapoint')->datasource();
        $DBCriteria = new DBCriteria();
        $DBCriteria->compare('report_id' , config('state.report_id'));
        $DBCriteria->compare('type', 'relatives');
        $dataPoints = $dataPointService->loadData($DBCriteria);
        return $dataPoints;
    }

    public function getNickNames(int $reportId)
    {
        config(['state.report_id' => $reportId]);
        $dataPointService = loadService('datapoint')->datasource();
        $DBCriteria = new DBCriteria();
        $DBCriteria->compare('report_id' , config('state.report_id'));
        $DBCriteria->compare('type', 'nickname');
        $dataPoints = $dataPointService->loadData($DBCriteria);
        return $dataPoints;
    }

    public function getReportNames(int $reportId)
    {
        config(['state.report_id' => $reportId]);

        $DBCriteria = new DBCriteria();
        $DBCriteria->compare('report_id' , config('state.report_id'));
        $DBCriteria->compare('type', 'names');
        $names = $this->datasource->loadData($DBCriteria);
        // $prog = $this->datasource->loadProgress('names', false);
        // $names = json_decode($prog['names_data'], true);
        $data = [];
        if ($names) {
            foreach ($names as $name) {
                $data[] = $name;
            }
        }
        return $data;
    }

    public function getReportOtherNames(int $reportId)
    {
        config(['state.report_id' => $reportId]);

        $DBCriteria = new DBCriteria();
        $DBCriteria->compare('report_id' , config('state.report_id'));
        $DBCriteria->compare('type', 'names');
        $names = $this->datasource->loadData($DBCriteria);
        // $prog = $this->datasource->loadProgress('names', false);
        // $names = json_decode($prog['names_data'], true);
        $data = [];
        if ($names) {
            foreach ($names as $name) {
                if (isset($name['data']['input_name']) && !$name['data']['input_name']) {
                    $data[] = $name;
                }
            }
        }
        return $data;
    }

    public function getReportLocations(int $reportId)
    {
        config(['state.report_id' => $reportId]);

        $DBCriteria = new DBCriteria();
        $DBCriteria->compare('report_id' , config('state.report_id'));
        $DBCriteria->compare('type', 'addresses');
        $addresses = $this->datasource->loadData($DBCriteria);

        // $prog = $this->datasource->loadProgress('addresses', false);
        // $addresses = json_decode($prog['addresses_data'], true);
        $locations = [];
        if ($addresses) {
            foreach ($addresses as $address) {
                // $locations[] = $address['locationName'];
                if (!empty($address['data']['locationName'])) {
                    $locations[] = $address['data']['locationName'];
                } elseif (!empty($address['data']['locationName'])) {
                    $locations[] = $address['data']['fullAddress'];
                }
            }
        }
        return $locations;
    }


    public function getReportPhones(int $reportId)
    {
        config(['state.report_id' => $reportId]);

        $prog = $this->datasource->loadProgress('phones', false);
        $phones = json_decode($prog['phones_data'], true);
        $data = [];
        if ($phones) {
            foreach ($phones as $phone) {
                $data[] = $phone['number'];
            }
        }
        return $data;
    }


    public function getReportEmails(int $reportId)
    {
        config(['state.report_id' => $reportId]);

        $prog = $this->datasource->loadProgress('emails', false);
        $emails = json_decode($prog['emails_data'], true);
        $data = [];
        if ($emails) {
            foreach ($emails as $email) {
                $data[] = $email['emailAddress'];
            }
        }
        return $data;
    }
}
