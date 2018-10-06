<?php
namespace Skopenow\Matching\Services;

require_once(__DIR__.'/../../../../../../framework/yii.php');

/**
*
*/
class ReportService
{
    private $report;

    public function __construct()
    {
        $this->report = loadservice('reports');
    }

    public function getAllPersonNames($id)
    {
        // return \SearchApis::load_progress($id, false);
        // return ["names_data" => "", 'Rob Bertholf', 'mark', 'john', 'philip'];
        config(['state.report_id' => $id]);
        $names = $this->report->getReportNames();
        return $names;
    }

    public function getOtherNames($id)
    {
        // $criteria = new \Search\Helpers\Bridges\BridgeCriteria();
        // $criteria->compare("is_deleted", 0);
        // $criteria->compare("type", 'names');
        // $dp_bridge = new \Search\Helpers\Bridges\DataPointBridge($this->person['id']);
        // $allNames = $dp_bridge->getAll($criteria);
        // $names = \DB::table('persons')->find($id);
        // if (is_null($names)) {
        //     return [];
        // }
        $names = [];
        config(['state.report_id' => $id]);
        $names =  $this->report->getReportOtherNames();
        return json_decode(json_encode($names), True);
    }

    public function getAllPersonLocations($person)
    {
        // $addresses = \searchApis::loadAllLocations($person);
        // foreach ($addresses as $address) {
        //     $locationName = \searchApis::getLocationNameFromAddressArray($address);
        //     $locationName = ucwords(strtolower(trim($locationName))) ;
        //     $this->addressesMap[$locationName] = $address ;
        //     $location[] = $locationName;
        // }
        config(['state.report_id' => $person['id']]);
        $locations =  $this->report->getReportLocations();
        return $locations;
        // return ['cairo', 'NY', 'LA', 'Washington'];
    }

    public function loadAllLocations($person)
    {
        /*//searchApis::loadLocationsPerson($person);
        $locations = [];
        $added = $locations;
        // $sql = "SELECT * FROM `progress_data` WHERE `person_id` = {$person['id']} and type ='addresses'" ;
        //$addresses = Yii::app()->db->createCommand($sql)->queryAll();
        $criteria = new \Search\Helpers\Bridges\BridgeCriteria();
        $criteria->compare("person_id", $person['id']);
        $criteria->compare('type', "addresses");

        $prog_bridge = new \Search\Helpers\Bridges\DataPointBridge($person['id']);
        $addresses = $prog_bridge->getAll($criteria);
        foreach ($addresses as $addressArray) {
            if (!empty($addressArray['data'])) {
                $addressData = $addressArray['data'];
                if (!empty($addressData["locationName"])) {
                    if (!in_array($addressData['locationName'], $added)) {
                        $added[] = $addressData['locationName'];
                        $locations[] = $addressData;
                    }
                }
            }
        }*/
        // return ['Cairo', 'Egypt', 'Benha'];

        config(['state.report_id' => $person['id']]);
        return $this->report->getReportLocations();
    }

    public function getReport()
    {
        return $this->report->getReport();
    }
}
