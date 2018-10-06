<?php
namespace Skopenow\Reports\CombinationGenerators\Generators;

use Skopenow\Reports\Models\Report;
use App\DataTypes\DataType;

/**
*
*/
class UpdatedDatapointCombinationsGenerator extends AbstractCombinationsGenerator
{
    use DatapointCombinationsGeneratorTrait;

    protected $data;

    public function make()
    {
        $type = $this->data['type'];
        $oldData = $this->data['oldData'];
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
        $oldVerified = (bool) $oldData['is_verified'];
        $isPoepleData = $this->data['source'] == 'peopleData';

        // Username Combinations always run
        if ($type == 'added_usernames') {
            \Log::info('BRAIN: Creating Username combination from updated DataPoint');
            \Log::debug('BRAIN: Creating Username combination from updated DataPoint', $this->data);
            $this->makeUsernameCombinations($verified, $isPoepleData);
        }

        // if Data is not verified nor peopledata
        if (!$verified && !$oldVerified) {
            return;
        } elseif (!$verified) {
            return;
        }
        if ($verified && !$oldVerified) {
            // verified and old is not verified
        }

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
