<?php

namespace Skopenow\Result;

use Cache;
use App\Models\BannedDomains;
use App\Models\Result;

class DataSourceBridge implements DataBaseSourceInterface
{
    /**
     * [$report description]
     * 
     * @var array
     */
    protected $report;

    /**
     * [$reportId description]
     * 
     * @var integer
     */
    protected $reportId;

    /**
     * [__construct description]
     */
    public function __construct()
    {
        $this->reportId = config('state.report_id');
        $this->report["id"] = $this->reportId;
    }

    /**
     * [saveResult description]
     * 
     * @param array $data [description]
     * 
     * @return integer       [description]
     */
    public function saveResult(array $data) :int
    {
        $results_object = new \Search\Helpers\Bridges\ResultsBridge(
            $this->report
        );

        $resId = $results_object->store($data);
        return $resId;
    }

    /**
     * [UpdateResult description]
     * 
     * @param array   $data            [description]
     * @param integer $id            [description]
     * @param integer $combinationId [description]
     * 
     * @return bool       [description]
     */
    public function updateResult($data, $id = null, $url = null)
    {
        $bridgeCriteria = new \Search\Helpers\Bridges\BridgeCriteria();
        if (!is_null($id)) {
            $bridgeCriteria->compare('id', $id);    
        }

        if (!is_null($url)) {
            $urlInfo = loadService('urlInfo');
            $uniqeContent = $urlInfo->prepareContent($url);
            $bridgeCriteria->compare('unique_content', $uniqeContent);  
        }
        
        $bridgeCriteria->compare('person_id', $this->reportId);

        $resultBridge = new \Search\Helpers\Bridges\ResultsBridge($this->report);

        // $data['other_data'] = new \CDbExpression("concat(ifnull(other_data,''),',','" . config("state.combination_id") . "')");
        return $resultBridge->update($data,$bridgeCriteria);
    }

    /**
     * [getResult description]
     *
     * @param int $resultId [description]
     * @param string $url      [description]
     *
     * @return array           [description]
     */
    public function getResult($resultId = null, $url = null)
    {
        $bridgeCriteria = new \Search\Helpers\Bridges\BridgeCriteria();
        if (!is_null($resultId)) {
            $bridgeCriteria->compare('id', $resultId);  
        }

        if (!is_null($url)) {
            $urlInfo = loadService('urlInfo');
            $uniqeContent = $urlInfo->prepareContent($url);
            $bridgeCriteria->compare('unique_content', $uniqeContent);  
        }
        $bridgeCriteria->compare('person_id', $this->reportId);
        $resultBridge = new \Search\Helpers\Bridges\ResultsBridge($this->report);
        $result = $resultBridge->get($bridgeCriteria);

        return $result;
    }

    public function updateResults($data, $ids)
    {
        if (!$ids) {
            return false;
        }

        if (is_string($ids)) {
            $ids = explode(',', $ids);
        }
        $bridgeCriteria = new \Search\Helpers\Bridges\BridgeCriteria();
        $bridgeCriteria->compare('person_id', $this->reportId);
        $bridgeCriteria->addInCondition("id", $ids);

        $resultBridge = new \Search\Helpers\Bridges\ResultsBridge($this->report);

        return $resultBridge->update($data, $bridgeCriteria);
    }

    public function updateByCriteria($data, $criteria)
    {
        $resultBridge = new \Search\Helpers\Bridges\ResultsBridge($this->report);
        $bridgeCriteria = new \Search\Helpers\Bridges\BridgeCriteria();
        $bridgeCriteria->mergeWith($criteria);
        return $resultBridge->update($data, $bridgeCriteria);
    }

    public function getResults($criteria)
    {
        if(!$criteria["order"]){
            $criteria["order"] = "id asc ";
        }
        
        $results = Result::select($criteria["select"])
            ->where(function($q) use($criteria){
                if(isset($criteria["raw"])){
                    $q->whereRaw($criteria["raw"]);
                }
            })          
            ->orderByRaw($criteria["order"]);
        if (!empty($criteria['group'])) {
            $results = $results->groupBy($criteria["group"]);
        }
            
        $results = $results->skip($criteria["offset"])->take($criteria["limit"])
            ->get();

        return $results;
    }

    






}