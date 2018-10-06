<?php
/**
 * Abstract Datasource code
 *
 * PHP version 7.0
 *
 * @category Micro_Services-phase_2
 * @package  Datapoint
 * @author   Mahmoud AboElnouR <aboelnoor@queentecksolutions.com>
 * @license  QueenTechSolutions, Inc.
 * @link     http://www.queentecksolutions.com
 **/
namespace Skopenow\Datapoint\Classes;

use App\Libraries\BridgeCriteria;
use App\Libraries\DBCriteriaInterface as DBCriteria;
use App\Libraries\DataPointBridge;
use App\Libraries\ProgressBridge;
use App\Models\Entity;
use App\Models\EntityDataPoint;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Abstract Datasource class
 *
 * @category Micro_Services-phase_2
 * @package  Datapoint
 * @author   Mahmoud AboElnouR <aboelnoor@queentecksolutions.com>
 * @license  QueenTechSolutions, Inc.
 * @link     http://www.queentecksolutions.com
 **/
class Datasource
{
    const CACHE_TIME = 30;
    const TRAKING_CACHE_TIME = 1440;
    const PROFILE_DATA_TYPES = [
        'addresses' => 'fullAddress',
        'relatives' => 'name',
        'phones' => 'number',
        'emails' => 'emailAddress',
        'work_experiences' => 'company',
        'schools' => 'school',
        'websites' => 'url',
        'names' => 'name',
        'age' => 'age',
        'added_usernames' => 'username',
    ];
    /**
     * Class Constructor
     *
     * @param \Iterator $inputs Datapoint inputs
     *
     * @return void
     */
    public function __construct()
    {
        $this->reportId = config('state.report_id');
    }

    /**
     * Chack if input is valid
     *
     * @param mixed $inputs Input to validete Ex: username, age, .. etc.
     *
     * @return bool
     */
    private function getMainValue($value, $key): string
    {
        switch ($key) {
            case 'addresses':
                $mainValue = @$value['fullAddress'];
                break;
            case 'relatives':
                $mainValue = ucwords($value['name']);
                break;
            case 'work_experiences':
                $mainValue = @ucwords($value['position']) . ' - ' . ucwords($value['company']);
                break;
            case 'schools':
                $mainValue = @ucwords($value['school']);
                break;
            case 'names':
                $mainValue = @ucwords($value['name']);
                break;
            case 'websites':
                $mainValue = ucwords(@$value['url']);
                break;
            case 'nicknames':
                $mainValue = ucwords(@implode(', ', $value['names']));
                break;
            case 'usernames':
                $mainValue = @$value['username'];
                break;
            case 'added_usernames':
                $mainValue = @$value['username'];
                break;
            case 'emails':
                $mainValue = @$value['emailAddress'];
                break;
            case 'phones':
                $mainValue = @$value['number'];
                break;
            default:
                $mainValue = @$value[$key];
                break;
        }
        return $mainValue;
    }

    /**
     * prepare output status for Datapoint inputs
     *
     * @param mixed $input      input to be add to output
     * @param mixed $normalized normalized input if exists
     *
     * @return void
     */
    //$person, $key, $dbkey, $val, $combination = null, $rescanSetting = []
    public function progressData(string $key, array $val, array $rescanSetting = [])
    {
        // TODO:: to be changed
        $type = $key;
        $mainValue = $this->getMainValue($val, $key);

        Log::info('progress_data start');

        setArrayHash($val);
        $hash = $val['hash'];

        $assoc_profile = @$val['assoc_profile'] ?? empty($val['manual']) ? 'comb_base' : '';
        $val['assoc_profile'] = $assoc_profile;
        $key = @$val['key'];

        // Bridge
        $entity = $this->createEntity($key);
        $entityId = $entity->entity_id ?? null;

        $val['hash'] = $hash;
        $val['type'] = $type;
        $val['id'] = $entityId;

        if (!empty($val['res'])) {
            $val['res'] = (string) $val['res'];
        }
        $source = $val['source']??null;
        $sources = [];
        if ($source) {
            $sources[] = $source;
        }

        $data = array(
            'hash' => $hash,
            'id' => $entityId,
            'type' => $type,
            'key' => $key,
            'data_key' => $key,
            'assoc_profile+' => [$assoc_profile],
            'res+' => !empty($val['res']) ? [$val['res']] : array(),
            'main_value' => substr($mainValue, 0, 100),
            'data' => $val,
            'data_json' => json_encode($val),
            'data_all*' => [$val],
            'popularity' => !empty($val['popularity']) ? $val['popularity'] : "",
            'is_verified' => !empty($val['is_verified']) ? $val['is_verified'] : 0,
            'copied_from_rescan' => !empty($rescanSetting['updatesOnly']) ? 1 : 0, // 1->Copied, 0->New//
            'is_deleted' => 0,
        );

        if ($sources) {
            $data['sources+'] = $sources;
        }

        Log::info('DataPointBridge start');
        $dp_bridge = new DataPointBridge();
        $result = $dp_bridge->update($data, new BridgeCriteria);
        $oldData = $result['Attributes'] ?? null;

        $debugData = [
            'data' => $data,
            'dynamoReturn' => $result,
            'report' => $this->reportId,
            'key' => $key,
            'datapointId' => $entity,
        ];
        Log::debug('add datapoint Dynamo', $debugData);
        Log::info('DataPointBridge end');

        // $this->publishUpdates();
        return compact('key', 'oldData', 'entityId');
    }

    public function publishUpdates()
    {
        if (!config('flags.initiating_report') && app()->Socket) {
            $progress = $this->loadProgress(null, false);
            $summary = getSummary($progress);
            // $progress = \CSearch::getSummary($this->reportId, $progress);
            // $progress = $this->currentProgress(null, false);

            Cache::delete(config('state.version')."search_progress_" . encryptid($this->reportId));

            app()->Socket->publishUpdates($this->reportId, $summary);
        }
    }
    public function currentProgress($type = null)
    {
        $prog_bridge = new ProgressBridge($this->reportId);
        $progress_data = $prog_bridge->get();

        if (empty($progress_data)) {
            $progress_data = array();
        }

        $progress_data += include __DIR__ . '/../../resources/progress_default_data.php';

        $params = [':id' => $this->reportId];

        if ($type) {
            $params[':type'] = str_replace('_data', '', $type);
            $data[str_replace('_data', '', $type) . '_data'] = array();
        } else {
            $data['assoc_profiles_data'] = array();
            $data['assoc_keys_data'] = array();
        }

        if (!empty(self::PROFILE_DATA_TYPES)) {
            $profile_data_types = array_keys(self::PROFILE_DATA_TYPES);
        }

        $criteria = new BridgeCriteria;
        $criteria->compare('is_deleted', 0);
        $criteria->compare('copied_from_rescan', 0);
        if ($type) {
            $criteria->compare('type', str_replace('_data', '', $type));
        }
        $dp_bridge = new DataPointBridge;
        $progress_rows = $dp_bridge->getAll($criteria);
        $progress = []; //print_r($progress_rows); if(!empty($progress_rows))die;
        foreach ($progress_rows as $progress_row) {
            $progress[] = array_intersect_key($progress_row, array_flip(['data']));
        }
        return $progress_data = array_merge($progress, $progress_data);
    }

    //$id, $use_cache = true, $type = null, $as_object = false, $rescanSetting = []
    public function loadProgress(string $type = null, bool $use_cache = true, bool $as_object = false)
    {
        // Log::info('loadProgress start');
        if ($use_cache) {
            $progress_data = Cache::get(config('state.version') . "progress_data_{$type}_{$this->reportId}", false);
            if ($progress_data && is_array($progress_data)) {
                if ($as_object) {
                    return empty($progress_data) ? false : (object) $progress_data;
                }

                return $progress_data;
            }
        }

        // Deperacate progress json data
        $prog_bridge = new ProgressBridge($this->reportId);
        $progress_data = $prog_bridge->get();

        if (empty($progress_data)) {
            $progress_data = array();
        }

        $progress_data += include __DIR__ . '/../../resources/progress_default_data.php';

        $transformedData = $this->transformProgressData($type);
        if (!empty($transformedData['assoc_profiles_data'])) {
            unset($transformedData['assoc_profiles_data']);
        }

        if (!empty($transformedData['assoc_keys_data'])) {
            unset($transformedData['assoc_keys_data']);
        }

        $progress_data = $transformedData + $progress_data;

        Cache::add(config('state.version') . "progress_data_{$type}_$this->reportId", $progress_data, self::CACHE_TIME);

        if ($as_object) {
            return empty($progress_data) ? false : (object) $progress_data;
        }

        // Log::info('loadProgress end');
        return $progress_data;
    }

    //$person_id, $type = null, $rescanSetting = []
    public function transformProgressData($type = null)
    {
        if ($type) {
            $data[str_replace('_data', '', $type) . '_data'] = array();
        } else {
            $data['assoc_profiles_data'] = array();
            $data['assoc_keys_data'] = array();
        }

        if (!empty(self::PROFILE_DATA_TYPES)) {
            $profile_data_types = array_keys(self::PROFILE_DATA_TYPES);
        }

        $criteria = new BridgeCriteria();
        $criteria->compare('is_deleted', 0);
        $criteria->compare('copied_from_rescan', 0);
        if ($type) {
            $criteria->compare('type', str_replace('_data', '', $type));
        }
        $dp_bridge = new DataPointBridge();
        $progress_rows = $dp_bridge->getAll($criteria);

        $defaultData = [
            'addresses' => [
                'locationLat' => null,
                'locationLng' => null,
            ],
        ];

        foreach ($progress_rows as $progress_row) {
            if (!$progress_row['data_json']) {
                $progress_row['data_json'] = '{}';
            }

            if (!isset($progress_row['assoc_profile'])) {
                $progress_row['assoc_profile'] = array();
            }

            if (!isset($progress_row['res'])) {
                $progress_row['res'] = array();
            }

            if (!isset($progress_row['combinations_ids'])) {
                $progress_row['combinations_ids'] = array();
            }

            if (is_string($progress_row['combinations_ids'])) {
                $progress_row['combinations_ids'] = explode(',', $progress_row['combinations_ids']);
            }

            if (empty($progress_row['data_key']) && !empty($progress_row['key'])) {
                $progress_row['data_key'] = $progress_row['key'];
            }

            $data_all = $progress_row['data_all'] ?? json_decode($progress_row['data_json'], true);

            foreach ($data_all as $dataJSON) {
                @$dataJSON['index'] = $progress_row['ui_index'];

                if ($progress_row['type'] == 'work_experiences' || $progress_row['type'] == 'school') {
                    if (!empty($dataJSON['start']) && empty($dataJSON['end'])) {
                        $dataJSON['end'] = "Present";
                    }
                }

                if (empty($dataJSON['id'])) {
                    $dataJSON['id'] = $progress_row['id'];
                }

                if ($progress_row['hash']) {
                    $data["{$progress_row['type']}_data"][$progress_row['hash']] = $dataJSON;
                } else {
                    $data["{$progress_row['type']}_data"][] = $dataJSON;
                }

                if (!$type) {
                    foreach ((array) $progress_row['assoc_profile'] as $assoc_profile) {
                        $profile = array_search($assoc_profile, $data['assoc_profiles_data'], true);
                        if ($profile === false) {
                            $profile_index = count($data['assoc_profiles_data']);
                        } else {
                            $profile_index = $profile['index'];
                        }

                        if (!isset($data['assoc_profiles_data'][$assoc_profile])) {
                            $data['assoc_profiles_data'][$assoc_profile] = array('index' => $profile_index);
                        }

                        foreach ($profile_data_types as $profile_data_type) {
                            if (!isset($data['assoc_profiles_data'][$assoc_profile][$profile_data_type])) {
                                $data['assoc_profiles_data'][$assoc_profile][$profile_data_type] = [];
                            }
                            if (!isset($data['assoc_keys_data'][$progress_row['data_key']])) {
                                $data['assoc_keys_data'][$progress_row['data_key']] = ['hashes' => [], 'combs' => []];
                            }
                        }

                        $data['assoc_profiles_data'][$assoc_profile][$progress_row['type']][] = $progress_row['hash'];
                    }
                    $data['assoc_keys_data'][$progress_row['data_key']]['hashes'][] = $progress_row['hash'];

                    if (!empty($progress_row['combinations_ids'])) {
                        if (empty($data['assoc_keys_data'][$progress_row['data_key']]['combs'])) {
                            $data['assoc_keys_data'][$progress_row['data_key']]['combs'] = array();
                        }

                        $data['assoc_keys_data'][$progress_row['data_key']]['combs'] = array_merge(
                            $data['assoc_keys_data'][$progress_row['data_key']]['combs'],
                            $progress_row['combinations_ids']
                        );

                        $data['assoc_keys_data'][$progress_row['data_key']]['combs'] = array_unique(
                            $data['assoc_keys_data'][$progress_row['data_key']]['combs']
                        );
                    }
                }
            }
        }

        foreach ($data as &$v) {
            $v = json_encode($v);
        }
        return $data;
    }

    public function loadData(DBCriteria $criteria)
    {
        $data = new DataPointBridge();
        return $data->getAll($criteria);
    }

    //new argument : type
    public function updateProgress($key, $val, bool $flag = false)
    {
        if ($key == 'total_combinations' || $key == 'completed_combinations' || $key == 'usernames') {
            // $this->publishUpdates($this->reportId);

            $data = [
                "$key+" => $val,
            ];
            $prog_bridge = new ProgressBridge($this->reportId);
            $prog_bridge->update($data);

            return;
        }

        $flag = false; // Disable total calculation
        $key = str_replace('_total', '', $key);

        $source_key = array_search($key, array_keys(trackSources()), true);
        if (!$source_key) {
            return;
        }

        $data = [
            $source_key . '_total+' => $val,
        ];
        $prog_bridge = ProgressBridge($this->reportId);
        $prog_bridge->update($data);
    }

    public function createEntity($data_key)
    {
        if (!$data_key || $data_key == '-') {
            return null;
        }

        // $transaction = \Yii::app()->db->beginTransaction();

        try {
            $entityDataPoint = DB::transaction(function () use ($data_key) {
                $entity = Entity::create([
                    'report_id' => config('state.report_id'),
                    'type' => 'datapoint',
                ]);

                return $entity->datapoint()->create([
                    'report_id' => config('state.report_id'),
                    'data_point_key' => $data_key,
                ]);
            }, 2);
        } catch (\Exception $e) {
            $entityDataPoint = EntityDataPoint::where([
                ['report_id', config('state.report_id')],
                ['data_point_key', $data_key],
            ])->first();
        };

        return $entityDataPoint;
    }
}
