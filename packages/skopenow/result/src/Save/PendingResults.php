<?php

namespace Skopenow\Result\Save;

use App\Models\ResultData;

class PendingResults extends PendingResultsAbstract
{

	public function save(ResultData $result)
	{
		try {
			$key = array(
                "report_id" => config("state.report_id"),
                "content"   =>  $result->url,
            );

            $key = app()->DynamoDB->marshaler->marshalItem($key);

            $newResultData = array(
                ":resultData"=>serialize($result), 
                ":waiting_for_purify"=>1,
                ":combination_level_id" => config('state.combination_level_id'),
                ":ExpirationTime"   =>  time()+60*60*24, 
            );

            $updateExpression = "SET resultData = :resultData, waiting_for_purify = :waiting_for_purify, combination_level_id = :combination_level_id, ExpirationTime = :ExpirationTime ";

            $newResultData = app()->DynamoDB->marshaler->marshalItem($newResultData);
            $criteriaData = array(
                "TableName" => env("PENDING_RESULT_TABLE_NAME"),
                "Key" => $key,
                "UpdateExpression" => $updateExpression,
                "ExpressionAttributeValues" =>  $newResultData,
                "ReturnValues"  =>  "ALL_NEW"
            );

            $output = app()->DynamoDB->updateItem($criteriaData);
            if ($output) {
	            return true;
	        }
	        return false;
		} catch (\Exception $e) {
			throw $e;
		}
	}

	public function get(array $criteria): \Iterator
	{
		$expressionAttributeValues = array(":report_id" => config('state.report_id'));

		$filterExpression = "";
		if (!empty($criteria['combination_level_id'])) {
			$filterExpression = " combination_level_id = :combination_level_id ";
			$expressionAttributeValues[":combination_level_id"] = $criteria['combination_level_id'];
		}
        $expressionAttributeValues = app()->DynamoDB->marshaler->marshalItem($expressionAttributeValues);

		$getCriteria =array(
            'TableName' => 'pending_result',
            'KeyConditionExpression' => 'report_id = :report_id', 
            'ExpressionAttributeValues' => $expressionAttributeValues,
        );

		$filterExpression = ltrim($filterExpression,"and");
        if ($filterExpression) {
            $getCriteria['FilterExpression'] = $filterExpression;
        }


        $dbResult = app()->DynamoDB->queryAll($getCriteria);
        $results = $dbResult->get("Items");
        $results = app()->DynamoDB->marshaler->unmarshalItems($results);
        
        $results = new \ArrayIterator($results);

        return $results;
	}
}