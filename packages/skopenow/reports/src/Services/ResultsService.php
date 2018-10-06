<?php
namespace Skopenow\Reports\Services;

/**
*
*/
class ResultsService
{
    public function store($result)
    {
        $person = Persons::model()->findByPk(config('report_id'));
        $searchApis = new \SearchApis($person);
        $searchApis->res_save($results);
    }

    public function getById($resultId)
    {
        $bridge_criteria = new \Search\Helpers\Bridges\BridgeCriteria();
        $bridge_criteria->compare('id', $res_id);
        $reportId = config('state.report_id');
        $result_bridge = new \Search\Helpers\Bridges\ResultsBridge($reportId);
        return $result_bridge->get($bridge_criteria);
    }
}
