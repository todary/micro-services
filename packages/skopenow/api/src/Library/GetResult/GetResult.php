<?php
/**
 * Created by PhpStorm.
 * User: todary
 * Date: 21/11/17
 * Time: 05:02 م
 */

namespace Skopenow\Api\Library\GetResult;

class GetResult implements GetResultInterface
{

    public function getResultsRequest($requestArray)
    {

        $result = \CSearch::getResults(\CJSON::encode($requestArray), true);
        return json_decode($result, true);
    }

    public function getSummary($id)
    {

        $getDataProgress = \SearchApis::load_progress($id);
        $summary = null;

        if ($getDataProgress)
            $summary = \CSearch::getSummary($getDataProgress['person_id'], $getDataProgress, true, 30, false, true);

        return $summary;

    }

}