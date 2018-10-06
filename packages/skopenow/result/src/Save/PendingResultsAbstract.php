<?php

namespace Skopenow\Result\Save;

use App\Models\ResultData;

abstract class PendingResultsAbstract implements PendingResultsInterface
{

	abstract function save(ResultData $result);

	abstract public function get(array $criteria): \Iterator;

	public function getAll(int $report_id): array
	{

		if (!$report_id) {
			return [];
		}

		$filterExpression = 'report_id = :report_id';
		$expressionAttributeValues = array(":report_id" => config('state.report_id'));
        $expressionAttributeValues = app()->DynamoDB->marshaler->marshalItem($expressionAttributeValues);
        $dbResult = $this->queryAll($filterExpression, $expressionAttributeValues);
		$results = $dbResult->get("Items");
        $results = app()->DynamoDB->marshaler->unmarshalItems($results);

        return $results;
	}

	protected function queryAll(string $filterExpression, array $expressionAttributeValues)
	{
		$getCriteria =array(
            'TableName' => 'pending_result',
            'KeyConditionExpression' => $filterExpression, 
            'ExpressionAttributeValues' => $expressionAttributeValues,
        );

        $dbResult = app()->DynamoDB->queryAll($getCriteria);
        
        return $dbResult;
        
	}
}