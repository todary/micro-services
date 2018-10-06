<?php
namespace App\Libraries;

use App\Models\Entity;
use App\Models\EntityDataPoint;
use App\Models\ProgressData;
use Aws\Result;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

/**
 *  class DataPoint Bridge
 *  @author Mostafa Ameen
 */
class DataPointBridge
{
    const CACHE_TIME = 60;
    const TYPES = [
        'added_usernames',
        'addresses',
        'age',
        'emails',
        'names',
        'nicknames',
        'phones',
        'relatives',
        'schools',
        'websites',
        'work_experiences',
    ];
    /**
     * [$person description]
     * @var null
     */
    protected $person_id = null;
    public static $hashKey = 'report_id';
    public static $sortKey = 'key';
    protected static $types_by_name = [];
    protected $is_old = false;

    /**
     * [__construct description]
     * @param [type] $person [description]
     */
    public function __construct()
    {
        // \Log::info('DataPointBridge: Init start');

        $this->person_id = config('state.report_id');

        if (empty(self::$types_by_name)) {
            self::$types_by_name = self::TYPES;
        }

        $this->is_old = Cache::remember('is_old', self::CACHE_TIME, function () {
            ProgressData::where('person_id', $this->person_id);
        });

        // \Log::info('DataPointBridge: Init end');
    }

    protected function prepareOptions(BridgeCriteria $criteria, $for = 'select', $index = '')
    {
        $hashKey = self::$hashKey;
        $sortKey = self::$sortKey;
        $keys = [$hashKey, $sortKey];

        $keyArray = [];
        $names = [];
        $keyConditions = [];

        $condition = $criteria->condition;
        $params = $criteria->params;

        $condition = str_replace('person_id', 'report_id', $condition);

        if ($condition && strpos($condition, '(') !== 0) {
            $condition = "($condition)";
        }

        $condition = preg_replace('#\\((\w)#', '(#\\1', $condition);
        $condition = preg_replace_callback('@\\((#\w+) like ([^\\)]+)\\)@i', function ($matches) use (&$params) {
            $val = $params[$matches[2]] ?? $matches[2];
            if (substr($val, -1) == '%') {
                $ret = "(begins_with($matches[1], $matches[2]))";
            } else {
                $ret = "(contains($matches[1], $matches[2]))";
            }

            if (isset($params[$matches[2]])) {
                $params[$matches[2]] = str_replace("%", "", $val);
            }

            return $ret;
        }, $condition);

        preg_match_all('@#(\w+)@', $condition, $matches);
        $names = [];
        if ($for == 'select') {
            $names = array_combine($matches[0], $matches[1]);
        }

        $conditionalRange = [];
        $multipleHash = [];

        foreach ($keys as $i => $key) {
            preg_match("#\\((\\#$key)([=><]+)(:ycp\d+)\\)#", $condition, $matches);
            //preg_match("#\\((\\#$key)([^\)]+)(:ycp\d+)\\)#", $condition, $matches);
            if (!empty($matches)) {
                $keyConditions[] = $matches[0];
                $currentCondition = str_replace($matches[1], $matches[3], $matches[0]);
                $condition = str_replace($matches[0], $currentCondition, $condition);
                $keyArray[ltrim($matches[1], '#')] = $criteria->params[$matches[3]];
            } elseif ($i == 0) {
                preg_match("#\\((\\#$key) (IN) (\(:ycp[^\)]+\\))\\)#", $condition, $matches);
                if ($matches) {
                    unset($names[$matches[1]]);
                    $vals = str_replace(array('(', ')', ', '), array('', '', ','), $matches[3]);
                    $vals = explode(',', $vals);
                    $vals = array_combine($vals, $vals);

                    $hashRange = array_intersect_key($params, $vals);
                    $multipleHash[$hashKey] = $hashRange;

                    $params = array_diff_key($params, $vals);

                    $condition = str_replace($matches[0], key($params) . '=' . key($params), $condition);
                } else {
                    $keyConditions[] = "#$hashKey=:$hashKey";
                    if ($for == 'select') {
                        $names["#$hashKey"] = $hashKey;
                        $params[":$hashKey"] = $this->person_id;
                    }
                    $keyArray["$hashKey"] = $this->person_id;
                }
            } elseif ($i == 1) {
                preg_match("#\\((\\#$key) (IN) (\(:ycp[^\)]+\\))\\)#", $condition, $matches);
                if ($matches) {
                    unset($names[$matches[1]]);
                    $vals = str_replace(array('(', ')', ', '), array('', '', ','), $matches[3]);
                    $vals = explode(',', $vals);
                    $vals = array_combine($vals, $vals);

                    $sortRange = array_intersect_key($params, $vals);
                    $conditionalRange[$sortKey] = $sortRange;

                    $params = array_diff_key($params, $vals);

                    $condition = str_replace($matches[0], key($params) . '=' . key($params), $condition);
                } elseif ($for != 'select') {
                    $keyConditions[] = "#$sortKey=:$sortKey";
                    $keyArray["$sortKey"] = '-';
                }
            }
        }

        $condition = preg_replace_callback('@(#\w+)\\[\\] in \\(([^\\)]+)\\)@i', function ($matches) {
            $vals = explode(", ", $matches[2]);
            $expressions = [];
            foreach ($vals as $val) {
                $expressions[] = "contains($matches[1],$val)";
            }
            $expression = implode(' OR ', $expressions);

            return $expression;
        }, $condition);
        //$condition = preg_replace(' not in ', ' NOT_CONTAINS ', $condition);

        $keyCondition = implode(' and ', $keyConditions);

        $params = app()->DynamoDB->marshaler->marshalItem($params);

        $options = [];
        $options['TableName'] = \Yii::app()->params['data_point_table_name'];
        $options['_ConditionalRange'] = $conditionalRange;
        $options['_MultipleHash'] = $multipleHash;

        if ($index) {
            $options['IndexName'] = $index;
        }

        if ($for == "select") {
            $options['KeyConditionExpression'] = $keyCondition; // a string representing a constraint on the attribute

            if ($condition) {
                $options['FilterExpression'] = $condition; // a string representing a constraint on the attribute
            }

            $options['ExpressionAttributeValues'] = $params;
        } else {
            $options['Key'] = app()->DynamoDB->marshaler->marshalItem($keyArray); // an array representing a constraint on the attribute
            //$options['ConditionExpression'] = $condition;
        }

        $options['ExpressionAttributeNames'] = $names;

        return $options;
    }

    /**
     * @param  [array] $attributes
     * @return [bool]
     */
    public function insert(array $attributes)
    {
        if ($this->is_old) {
            return self::oldInsert($attributes);
        }

        $data_key = $attributes['key'] ?? $attributes['data_key'] ?? null;

        // Caution :: this function will not work probably without those lines
        /*if ($data_key) {
            $entityDataPoint = $this->createEntity($data_key);
            if ($entityDataPoint) {
                $attributes['entity_id'] = $entityDataPoint->entity_id;
            }
        }*/

        $attributes['report_id'] = $this->person_id;

        //$type = 0;
        //if (!empty(self::$types_by_name[$attributes['type']])) $type = self::$types_by_name[$attributes['type']];
        //$attributes['type'] = $type;

        $item = app()->DynamoDB->marshaler->marshalItem($attributes);

        $ret = app()->DynamoDB->putItem(array(
            'TableName' => \Yii::app()->params['data_point_table_name'],
            'Item' => $item,
        ));

        return $ret->get('statusCode') == 200;
    }

    /**
     * [get description]
     * @param  \BridgeCriteria $criteria [description]
     * @return [type]                    [description]
     */
    public function getAll(BridgeCriteria $criteria, $cache = 0)
    {
        if ($this->is_old) {
            return self::oldGetall($criteria, $cache);
        }

        $criteria = clone $criteria;

        if (get_class($this) == __CLASS__) {
            $criteria->addCondition('data_key <> :dash');
            $criteria->params[':dash'] = '-';
        }
        $options = self::prepareOptions($criteria);
        // dd($options);

        if (!empty($options['_MultipleHash'])) {
            $key = key($options['_MultipleHash']);
            $values = reset($options['_MultipleHash']);
            $firstVal = array_shift($values);

            $originalKeyExpression = $options['KeyConditionExpression'];
            $options['KeyConditionExpression'] = $originalKeyExpression ? "$key = :$key and $originalKeyExpression" : "$key = :$key";
            $options['ExpressionAttributeValues'][":$key"] = app()->DynamoDB->marshaler->marshalValue($firstVal);

            $dbResult = app()->DynamoDB->query($options);
            $resData = $dbResult->toArray();
            $items = &$resData['Items'];
            $count = &$resData['Count'];
            $scannedCount = &$resData['ScannedCount'];

            foreach ($values as $value) {
                $options['ExpressionAttributeValues'][":$key"] = app()->DynamoDB->marshaler->marshalValue($value);

                $res = app()->DynamoDB->query($options);
                $currentData = $res->toArray();

                $items = array_merge($items, $currentData['Items']);
                $count += $currentData['Count'];
                $scannedCount += $currentData['ScannedCount'];
            }

            $dbResult = new Result($resData);
        } elseif ($criteria->limit > 0) {
            //$options['Limit'] = $criteria->limit; // a string representing a constraint on the attribute
            $dbResult = app()->DynamoDB->query($options);
        } else {
            $dbResult = app()->DynamoDB->queryAll($options);
        }

        $data = $dbResult->get("Items");
        if (!$data) {
            return array();
        }

        $items = app()->DynamoDB->marshaler->unmarshalItems($data);
        if (!empty($options['_ConditionalRange'])) {
            foreach ($items as $k => $item) {
                foreach ($options['_ConditionalRange'] as $key => $values) {
                    if (!in_array($item[$key], $values)) {
                        unset($items[$k]);
                        continue 2;
                    }
                }
            }
        }
        return $items;
    }

    /**
     * [get one recored]
     * @param  string $value [description]
     * @return [type]        [description]
     */
    public function get(BridgeCriteria $criteria)
    {
        $criteria = clone $criteria;
        $criteria->limit = 1;
        $data = self::getAll($criteria);
        if (empty($data)) {
            return null;
        } else {
            return $data[0];
        }
    }

    public function exists(BridgeCriteria $criteria)
    {
        return !!self::get($criteria);
    }

    /**
     * [update description]
     * @param  [type]          $data [description]
     * @param  \BridgeCriteria $criteria        [description]
     * @return [type]                           [description]
     */
    public function updateAll(array $data, BridgeCriteria $criteria)
    {
        if ($this->is_old) {
            return self::oldUpdate($data, $criteria);
        }

        $affectedRows = 0;

        if (get_class(\Yii::app()) == "CConsoleApplication" and \Yii::app()->params['runCommandLog']) {
            echo "Start DataPoint getAll for updateAll...\n\n";
        }

        $criteria = clone $criteria;
        $results = $this->getAll($criteria);

        if (get_class(\Yii::app()) == "CConsoleApplication" and \Yii::app()->params['runCommandLog']) {
            echo "End DataPoint getAll for updateAll...\n\n";
        }

        foreach ($results as $result) {
            $rowCriteria = new BridgeCriteria();
            $rowCriteria->compare(self::$sortKey, $result[self::$sortKey]);

            if (get_class(\Yii::app()) == "CConsoleApplication" and \Yii::app()->params['runCommandLog']) {
                echo "start DataPoint updating " . $result[self::$sortKey] . "...\n\n";
            }
            $ret = $this->update($data, $rowCriteria);
            if (get_class(\Yii::app()) == "CConsoleApplication" and \Yii::app()->params['runCommandLog']) {
                echo "end DataPoint updating " . $result[self::$sortKey] . "...\n\n";
            }
            if ($ret) {
                $affectedRows++;
            }
        }

        if (get_class(\Yii::app()) == "CConsoleApplication" and \Yii::app()->params['runCommandLog']) {
            echo "End DataPoint updateAll...\n\n";
        }

        return $affectedRows;
    }

    /**
     * [update description]
     * @param  [type]          $data [description]
     * @param  \BridgeCriteria $criteria        [description]
     * @return [type]                           [description]
     */
    public function update(array $data, BridgeCriteria $criteria)
    {
        if ($this->is_old) {
            \Log::info('DataPointBridge: Old update');
            return self::oldUpdate($data, $criteria);
        }

        \Log::info('DataPointBridge: New update');

        $criteria = clone $criteria;

        $names = [];
        $values = [];
        $updateExpressionSet = [];
        $updateExpressionAdd = [];
        $updateExpressionRemove = [];
        $updateExpressionDelete = [];

        $data_key = null;
        $entityData = [];
        if (isset($data['is_deleted'])) {
            $entityData['deleted'] = $data['is_deleted'] == 1;
            $entityData['hidden'] = $data['is_deleted'] == 2;
        }

        foreach ($data as $key => $value) {
            if ($key == self::$sortKey) {
                $data_key = $value;
            }

            if ($key == self::$hashKey || $key == self::$sortKey) {
                $criteria->compare($key, $value);
                continue;
            }
            $field = trim($key, '+-!*');

            $specialCommand = substr($key, -1);

            if ($specialCommand != '!' && $value !== 0 && $value !== "0" && (!$value || $value === array(""))) {
                continue;
            }

            switch ($specialCommand) {
                // Special commands
                case '+': // add
                    $updateExpressionAdd[] = "#$field :$field";
                    if (is_array($value)) {
                        $value = new \ArrayIterator($value);
                    }

                    $values[":$field"] = $value;
                    break;
                case '-': // del
                    $updateExpressionDelete[] = "#$field :$field";
                    if (is_array($value)) {
                        $value = new \ArrayIterator($value);
                    }

                    $values[":$field"] = $value;
                    break;
                case '!': // remove
                    $updateExpressionRemove[] = "#$field";
                    break;
                case '*': // append
                    $type = 'L';
                    if ($value instanceof \Traversable || $value instanceof \stdClass) {
                        $type = 'M';
                    } elseif (is_array($value)) {
                        $expectedIndex = -1;
                        $dataSkipped = false;

                        foreach ($value as $k => $v) {
                            if (!empty($v)) {
                                ++$expectedIndex;
                                $dataSkipped = true;
                                continue;
                            }

                            if ($type === 'L' && (!is_int($k) || $k != ++$expectedIndex)) {
                                $type = 'M';
                            }
                        }
                    }

                    $values[":$field"] = $value;

                    if ($type == 'M') {
                        $updateExpressionSet[] = "#$field=list_append(if_not_exists(#$field, :empty_map), :$field)";
                        $values[":empty_map"] = new \stdClass();
                    } else {
                        $updateExpressionSet[] = "#$field=list_append(if_not_exists(#$field, :empty_list), :$field)";
                        $values[":empty_list"] = array();
                    }
                    break;
                default: // set
                    $updateExpressionSet[] = "#$field=:$field";
                    $values[":$field"] = $value;
                    break;
            }

            $names["#$field"] = "$field";
        }
        $updateExpressions = [];

        if ($updateExpressionSet) {
            $updateExpressions[] = "SET " . implode(", ", $updateExpressionSet);
        }

        if ($updateExpressionAdd) {
            $updateExpressions[] = "ADD " . implode(", ", $updateExpressionAdd);
        }

        if ($updateExpressionRemove) {
            $updateExpressions[] = "REMOVE " . implode(", ", $updateExpressionRemove);
        }

        if ($updateExpressionDelete) {
            $updateExpressions[] = "DELETE " . implode(", ", $updateExpressionDelete);
        }

        $updateExpression = implode('	 ', $updateExpressions);

        \Log::info('DataPointBridge: Preparing options');

        $options = self::prepareOptions($criteria, "update");

        \Log::info('DataPointBridge: Done preparing options');

        $options['UpdateExpression'] = $updateExpression;
        $options['ExpressionAttributeNames'] += $names;
        $options['ReturnValues'] = 'UPDATED_OLD';
        if ($values) {
            $options['ExpressionAttributeValues'] = app()->DynamoDB->marshaler->marshalItem($values);
        }

        if (!empty($options['_ConditionalRange'])) {
            \Log::info('DataPointBridge: Multiple update detected');
            foreach ($options['_ConditionalRange'] as $key => $values) {
                foreach ($values as $value) {
                    $currentOptions = $options;
                    $currentOptions['Key'][$key] = app()->DynamoDB->marshaler->marshalValue($value);
                    \Log::info('DataPointBridge: updating', $currentOptions);
                    $dbResult = app()->DynamoDB->updateItem($currentOptions);
                }
            }
        } else {
            try {
                \Log::info('DataPointBridge: updating', $options);
                $dbResult = app()->DynamoDB->updateItem($options);
            } catch (\Exception $ex) {
                dump($options);
                throw $ex;
            }
        }

        if (!$data_key && !empty($criteria->attributes['key'])) {
            $keys = is_array($criteria->attributes['key']) ? $criteria->attributes['key'] : [$criteria->attributes['key']];
            foreach ($keys as $key) {
                if ($key == "-") {
                    continue;
                }

                $data_key = $key;
            }
        }

        if ($data_key) {
            \Log::info('DataPointBridge: Creating entity');
            // $entityDataPoint = $this->createEntity($data_key);

            // if ($entityData && $entityDataPoint) {
                // \Entity::model()->updateByPK($entityDataPoint->entity_id, $entityData);
            // }
        }

        \Log::info('DataPointBridge: Finished update');
        return $dbResult;
    }

    public function delete(BridgeCriteria $criteria)
    {
        $data = array('is_deleted' => 1);
        return self::update($data, $criteria);
    }

    /**
     * @param  [array] $attributes
     * @return [bool]
     */
    public function oldInsert(array $attributes)
    {
        $attributes['person_id'] = $this->person_id;
        unset($attributes['report_id']);
        return \Yii::app()->db->createCommand()->insert('progress_data', $attributes);
    }

    /**
     * [get description]
     * @param  \BridgeCriteria $criteria [description]
     * @return [type]                    [description]
     */
    public function oldGetall(BridgeCriteria $criteria, $cache = 0)
    {
        $criteria = clone $criteria;

        $criteria->condition = str_ireplace('[] IN', ' IN', $criteria->condition);
        $criteria->condition = str_ireplace('report_id', 'person_id', $criteria->condition);
        if (stripos($criteria->condition, 'person_id') === false) {
            $criteria->compare('person_id', $this->person_id);
        }
        return \ProgressData::model()->cache($cache)->findAll($criteria);
    }

    /**
     * [update description]
     * @param  [type]          $data [description]
     * @param  \BridgeCriteria $criteria        [description]
     * @return [type]                           [description]
     */
    public function oldUpdate(array $data, BridgeCriteria $criteria)
    {
        $criteria = clone $criteria;
        $criteria->condition = str_ireplace('(key', '(data_key', $criteria->condition);
        $criteria->condition = str_ireplace('[] IN', ' IN', $criteria->condition);
        $criteria->condition = str_ireplace('report_id', 'person_id', $criteria->condition);

        if (stripos($criteria->condition, 'person_id') === false) {
            $criteria->compare('person_id', $this->person_id);
        }

        return \ProgressData::model()->updateAll($data, $criteria);
    }

    public function undoDeletedItems(\ProgressDelete $deletedProcess)
    {
        $criteria = new \Search\Helpers\Bridges\BridgeCriteria();
        if ($this->is_old) {
            $criteria->compare('delete_id', $deletedProcess->id);
        } else {
            $deletedData = \CJSON::decode($deletedProcess->deleted_data);
            $keys = [];
            foreach ($deletedData as $item) {
                foreach ($item as $subItems) {
                    if (isset($subItems['key'])) {
                        $keys[] = $subItems['key'];
                    }
                }
            }

            $criteria->addInCondition('key', $keys);
        }

        return $this->update(array('is_deleted' => 0), $criteria);
    }
}
