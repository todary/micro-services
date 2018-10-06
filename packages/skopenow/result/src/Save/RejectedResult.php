<?php

namespace Skopenow\Result\Save;

use App\Models\ResultData;

class RejectedResult extends PendingResultsAbstract
{
    public $reason;

    /**
     * [save description]
     *
     * @param ResultData $resultData [description]
     * @param int        $reason     [description]
     *
     * @return bool                  [description]
     */
    public function save(ResultData $resultData)
    {
        if (!$resultData->url) {
            return false;
        }
        
        $binReason = decbin($this->reason);

        try {
            $key = array(
                "report_id" => config("state.report_id"),
                "content"   =>  $resultData->url,
            );

            $key = app()->DynamoDB->marshaler->marshalItem($key);

            $clonedResult = clone $resultData;
            $clonedResult->setSearchList(null);
            $newResultData = array(
                ":resultData" => serialize($clonedResult),
                ":isRejected"=>1,
                ":ExpirationTime" => time()+60*60*24,
            );

            $updateExpression = "SET resultData = :resultData, isRejected = :isRejected, ExpirationTime = :ExpirationTime ";

            for ($i=0; $i<strlen($binReason); $i++) {
                $newResultData[":flag".$i] = $binReason[$i];
                $updateExpression .= ",flag".$i." = :flag".$i.", ";
            }

            $updateExpression = trim($updateExpression, ", ");

            $newResultData = app()->DynamoDB->marshaler->marshalItem($newResultData);
            $criteriaData = array(
                "TableName" => env("PENDING_RESULT_TABLE_NAME"),
                "Key" => $key,
                "UpdateExpression" => $updateExpression,
                "ExpressionAttributeValues" =>  $newResultData,
                "ReturnValues"  =>  "ALL_NEW"
            );

            $output = app()->DynamoDB->updateItem($criteriaData);
        } catch (Exception $ex) {
            throw $ex;
        }

        if ($output) {
            return true;
        }
        return false;
    }

    /**
     * [getRejectedResults description]
     *
     * @param int|null $reportId [description]
     * @param int|null $reason   [description]
     *
     * @return array             [array of Result data ]
     */
    public function getRejectedResults(int $reportId = null, int $reason = null)
    {
        if (!$reportId) {
            $reportId = config("state.report_id");
        }

        $expressionAttributeValues = array(":report_id" => $reportId);
        $filterExpression = "";

        if ($reason) {
            $binReason = decbin($reason);

            for ($i=0; $i<strlen($binReason); $i++) {
                if ($binReason[$i]) {
                    $expressionAttributeValues[":flag".$i] = $binReason[$i];
                    $filterExpression .= "flag".$i." = :flag".$i." and ";
                }
            }

            $filterExpression = trim($filterExpression, " and ");
        }
        
        $expressionAttributeValues = app()->DynamoDB->marshaler->marshalItem($expressionAttributeValues);

        $getCriteria =array(
            'TableName' => 'pending_result',
            'KeyConditionExpression' => 'report_id = :report_id',
            'ExpressionAttributeValues' => $expressionAttributeValues,
        );

        if ($reason) {
            $getCriteria['FilterExpression'] = $filterExpression;
        }

        $dbResult = app()->DynamoDB->queryAll($getCriteria);
        $relatives = $dbResult->get("Items");
        $relatives = app()->DynamoDB->marshaler->unmarshalItems($relatives);

        $rejectedresults = [];
        foreach ($relatives as $rejected) {
            if (isset($rejected["resultData"])) {
                $rejectedresults[] = unserialize($rejected["resultData"]);
            }
        }

        return $rejectedresults ;
    }

    /**
     * [deleteRejectedResults description]
     *
     * @param  int|null $reportId [description]
     *
     * @return bool             [description]
     */
    public function deleteRejectedResults(int $reportId = null)
    {
        if (!$reportId) {
            $reportId = config("state.report_id");
        }

        $results = $this->getRejectedResults($reportId);
        
        foreach ($results as $result) {
            $this->deleteResult($reportId, $result->url);
        }

        return true;
    }

    /**
     * [deleteResult description]
     *
     * @param int $reportId [description]
     * @param string $url   [description]
     *
     * @return bool         [description]
     */
    protected function deleteResult(int $reportId, string $url)
    {

        $key = array(
            "report_id" => $reportId,
            "content" => $url
        );

        $key = app()->DynamoDB->marshaler->marshalItem($key);
        
        $getCriteria =array(
            'TableName' => 'pending_result',
            "Key" => $key,
            
        );


        $dbResult = app()->DynamoDB->DeleteItem($getCriteria);

        return $dbResult;
    }

    public function get(array $criteria): \Iterator
    {
        
    }
}
