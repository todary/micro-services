<?php

namespace Skopenow\Reports\PeopleData;

use Skopenow\Reports\Models\Report;
use Skopenow\Reports\CombinationCreators\CombinationsMaker;

class PeopleDataFilter
{
    protected $report;

    public function __construct($reportId)
    {
        $this->report = Report::find($reportId);
    }

    public function filter(array $data) : array
    {
        \Log::info('BRAIN: before filtering PeopleData data');
        $data = $data['results'];
        $data = $this->filterByModified($data);
        $data = $this->filterByAge($data);
        \Log::info('BRAIN: after filtering PeopleData data');
        return $data;
    }

    public function filterByModified(array $data) : array
    {
        \Log::info('BRAIN: filtering PeopleData data by modified');
        $withoutModification = [];
        foreach ($data as $value) {
            if (!$value->modified) {
                $withoutModification[] = $value;
            }
        }
        if (count($withoutModification) > 0) {
            \Log::info('BRAIN: after filtering PeopleData data by modified');
            \Log::info('BRAIN: Filtering PeopleData returning not modified data');
            $data = $withoutModification;
        } else {
            \Log::info('BRAIN: Filtering PeopleData all data are modified ... returning all data');
        }
        return $data;
    }

    public function filterByAge(array $data) : array
    {
        \Log::info('BRAIN: filtering PeopleData data by age');
        $filteredByAge = [];
        foreach ($this->report->age as $age) {
            foreach ($data as $value) {
                if (!empty($value->age) && $value->age == $age) {
                    $filteredByAge[] = $value;
                }
            }
        }

        if (count($filteredByAge) > 0) {
            \Log::info('BRAIN: after filtering PeopleData data by age');
            $data = $filteredByAge;
        } else {
            \Log::info('BRAIN: Filtering PeopleData no matched age .. return all data');
        }
        return $data;
    }

    public function filterOtherNames(array $otherNames) : array
    {
        $otherNames = $this->filterOtherNamesByReport($otherNames);
        $filteredOtherNames = [];
        $withMNames = [];
        $withoutMNames = [];
        foreach ($otherNames as $otherName) {
            if (empty($otherName['middle_name'])) {
                $withoutMNames[] = $otherName;
                continue;
            }
            $withMNames[] = $otherName;
        }

        foreach ($withoutMNames as $withoutMName) {
            foreach ($withMNames as $withMName) {
                if ($withoutMName['first_name'] == $withMName['first_name'] &&
                    $withoutMName['last_name'] == $withMName['last_name']
                ) {
                    continue 2;
                }
            }
            $filteredOtherNames[] = $withoutMName;
        }

        foreach ($withMNames as $index => $withMName) {
            for ($i = $index + 1; $i < count($withMNames); $i++) {
                if (strpos($withMNames[$i]['middle_name'], $withMName['middle_name']) !== false &&
                    $withMNames[$i]['first_name'] == $withMName['first_name'] &&
                    $withMNames[$i]['last_name'] == $withMName['last_name']
                ) {
                    continue 2;
                }
            }
            $filteredOtherNames[] = $withMName;
        }
        return $filteredOtherNames;
    }

    protected function filterOtherNamesByReport($otherNames)
    {
        $filtered = [];
        foreach ($otherNames as $otherName) {
            foreach ($this->report->full_name as $full_name) {
                if (strtolower($full_name) == strtolower($otherName['full_name'])) {
                    continue 2;
                }
            }
            $filtered[] = $otherName;
        }
        return $filtered;
    }
}
