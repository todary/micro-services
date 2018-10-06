<?php
/**
 * Abstract Datapoint code
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

use App\DataTypes\DataType;
use App\Events\OnDatapointFoundEvent;
use App\Events\OnDatapointSaveEvent;
use App\Events\OnDatapointUpdateEvent;
use Illuminate\Support\Facades\Log;
use Skopenow\Datapoint\Services\ReportService;

/**
 * Abstract Datapoint class
 *
 * @category Micro_Services-phase_2
 * @package  Datapoint
 * @author   Mahmoud AboElnouR <aboelnoor@queentecksolutions.com>
 * @license  QueenTechSolutions, Inc.
 * @link     http://www.queentecksolutions.com
 **/

abstract class Datapoint
{
    protected $data;
    protected $report;
    protected $combinationId;
    protected $datasource;
    protected $extras;

    protected static $states_abv;

    public function __construct(\Iterator $data, Datasource $datasource)
    {
        $this->report = ReportService::getReport();

        $this->data = $data;
        $this->datasource = $datasource;
        $this->combinationId = config('state.combination_id');

        self::$states_abv = loadData('states_abv');
        $this->resultId = config('state.result_id');
    }

    public function add()
    {
        $allData = DataType::getMainValues($this->data);
        foreach ($this->data as $key => $input) {
            $currentData = $allData->getArrayCopy();
            unset($currentData[$key]);

            if (foundInArray($input->value, $currentData)) {
                continue;
            }

            $this->source = $input->source;
            foreach ($input->extras as $index => $extra) {
                $this->$index = $extra;
            }

            if (app()->environment(['production']) && isset($this->isQuable) && $this->isQuable == true) {
                event(new OnDatapointFoundEvent($this, $input));
            } else {
                $this->addEntry($input);
            }
        }
    }

    protected function addDataPoint(string $type, array $data, $input)
    {
        $data['is_verified'] = $this->is_verified ?? false;
        $data['flags'] = $this->flags ?? 0;
        $data['source'] = $input->source ?? null;
        $data['is_input'] = $this->is_input ?? 0;
        $result = $this->datasource->progressData($type, $data);
        if ($key = $result['key'] && $entityId = $result['entityId']) {
            $debugData = [
                'input' => $input,
                'datapointType' => $type,
                'data' => $data,
                'entityId' => $entityId,
            ];

            Log::debug('Datapoint to combination event', $debugData);
            if ($result['oldData']) {
                $oldData = app()->DynamoDB->marshaler->unmarshalItem($result['oldData']);
                Log::info('OnDatapointUpdateEvent dispatch');
                return event(new OnDatapointUpdateEvent($input, $type, $key, $entityId, $oldData));
            }
            Log::info('OnDatapointSaveEvent dispatch');
            return event(new OnDatapointSaveEvent($input, $type, $key, $entityId));
        }

        Log::info('Datapoint saved without event dispatching');
    }

    public function formatData(string $type, $data): string
    {
        $formatter = loadService('formatter');
        $formatted = $formatter->format(new \ArrayIterator([$type => [$data]]));
        return $formatted[$type][0]['formatted'];
    }

    protected function changeStartAndEndDateFormat(array $data): array
    {
        $date = ['startDate' => '', 'endDate' => ''];
        array_filter($data);

        return isset($data['startDate']) && isset($data['endDate']) ? $data : $date;
    }

    protected function isValidInputs(array $inputs, $validateAll = true): bool
    {
        $validation = loadService('validation');
        $validation->validate(new \ArrayIterator($inputs));
        foreach ($validation->getResults() as $results) {
            foreach ($results as $result) {
                if ($validateAll && !$result->offsetGet('isValid')) {
                    return false;
                }

                if (!$validateAll && $result->offsetGet('isValid')) {
                    return true;
                }
            }
        }

        return $validateAll;
    }

    abstract public function addEntry($input);
}
