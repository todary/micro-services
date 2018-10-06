<?php

namespace App\Libraries;

use Aws\DynamoDb\Marshaler;
use Aws\DynamoDb\DynamoDbClient;
use Aws\Result;

class DynamoDBContainer
{
    public $client = null;
    public $marshaler = null;

    public function __construct(DynamoDbClient $client, Marshaler $marshaler)
    {
        $this->client = $client;
        $this->marshaler = $marshaler;
    }
    
    public function __get($property)
    {
        return $this->client->$property;
    }
    
    public function __set($property, $value)
    {
        $this->client->$property = $value;
    }

    protected function getAll($function = "query", $options = [])
    {
        if (!$this->client) {
            return null;
        }
        $res = $this->client->$function($options);

        $LastEvaluatedKey = $res->get("LastEvaluatedKey");
        if (!$LastEvaluatedKey) {
            return $res;
        }

        $resData = $res->toArray();
        $resData['Pages'] = 1;
        $items = &$resData['Items'];
        $count = &$resData['Count'];
        $scannedCount = &$resData['ScannedCount'];
        
        unset($resData['LastEvaluatedKey']);

        while ($LastEvaluatedKey && $resData['Pages']<20) {
            $options['ExclusiveStartKey'] = $LastEvaluatedKey;

            $res = $this->client->$function($options);
            $currentData = $res->toArray();
            $resData['Pages']++;

            $items = array_merge($items, $currentData['Items']);
            $count += $currentData['Count'];
            $scannedCount += $currentData['ScannedCount'];
            $LastEvaluatedKey = $res->get("LastEvaluatedKeys");
        }

        $result = new Result($resData);

        return $result;
    }
    
    public function queryAll($options)
    {
        return $this->getAll("query", $options);
    }

    public function scanAll($options)
    {
        return $this->getAll("scan", $options);
    }

    public function __call($name, $parameters)
    {
        if (!$this->client) {
            return null;
        }
        
        try {
            return call_user_func_array(array($this->client, $name), $parameters);
        } catch (\Exception $ex) {
            notifyDev(print_r($parameters, true));
            throw $ex;
        }
    }
}
