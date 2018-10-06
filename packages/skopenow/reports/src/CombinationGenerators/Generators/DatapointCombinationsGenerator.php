<?php
namespace Skopenow\Reports\CombinationGenerators\Generators;

use Skopenow\Reports\Models\Report;
use App\DataTypes\DataType;

/**
*
*/
class DatapointCombinationsGenerator extends AbstractCombinationsGenerator
{
    use DatapointCombinationsGeneratorTrait;

    protected $data;

    public function make()
    {
        $type = $this->data['type'];
        \Log::debug("BRAIN: Creating {$type} combination from DataPoint .. entrance", $this->data);
        $report = Report::find($this->data['state']['report_id']);
        $names = [];
        if ($type != 'emails' && !empty($report->full_name[0])) {
            $unique = isUniqueFullName([$report->full_name[0]]);
            $name_status = $unique[0] ? 'unique' : 'common';
            $names[] = array_merge(name_parts($report->full_name[0]), ['name_status' => $name_status]);
        }
        $this->name = $names;
        $this->data['source'] = $this->data['input']->source;
        $this->data['values'] = [DataType::getMainValue($this->data['input'])];
        if (empty($this->data['values'])) {
            return;
        }

        $verified = $this->data['input']->extras['is_verified']??false;
        $isPoepleData = $this->data['source'] == 'peopleData';
        $is_input = $this->data['input']->extras['is_input']??false;
        // Username Combinations always run
        if ($type == 'added_usernames') {
            \Log::info('BRAIN: Creating Username combination from DataPoint');
            \Log::debug('BRAIN: Creating Username combination from DataPoint', $this->data);
            $this->makeUsernameCombinations($verified, $isPoepleData);
        }

        // if Data is not verified nor peopledata
        if (!$verified && $this->data['source'] != 'input' && !$is_input && !$isPoepleData) {
            return;
        }
        \Log::info('BRAIN: Creating Combinations from Datapoint with type ' . $type . ' ,source ' . $this->data['source']);
        switch ($type) {
            case 'emails':
                $this->makeEmailCombinations();
                break;

            case 'phones':
                $this->makePhoneCombinations();
                break;

            case 'work_experiences':
                $this->makeWorkCombinations();
                break;

            case 'schools':
                $this->makeSchoolCombinations();
                break;

            case 'addresses':
                $this->makeAddressCombinations();
                break;

            case 'websites':
                $this->makeWebsiteCombinations();
                break;

            case 'relatives':
                $this->makeRelativesCombinations();
                break;
        }
    }
}
