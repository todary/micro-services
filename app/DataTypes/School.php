<?php
namespace App\DataTypes;

/*
name: string

start: date/year
end: date/year
 */

/**
 * School DataType
 */
class School extends DataType
{
    const RULES = [
        'name' => 'required|string',
        'start' => 'sometimes|int|between:1900,2050',
        'end' => 'sometimes|int|between:1900,2050',
    ];

    protected function normalizeInputs(array &$data)
    {
        parent::normalizeInputs($data);

        $this->value = $data['name'];

        if (!empty($data['start'])) {
            $dateParts = explode("-", $data['start']);
            if (count($dateParts)<2) {
                $data["start"] = preg_replace('#(.*\D)?(\d+)$#', '\\2', $data["start"]);
            } else {
                $data['start'] = $dateParts[0];
            }

            if (is_numeric($data['start'])) {
                $data['start'] = (int)$data['start'];
            }
        }

        if (!empty($data['end'])) {
            $dateParts = explode("-", $data['end']);
            if (count($dateParts)<2) {
                $data["end"] = preg_replace('#(.*\D)?(\d+)$#', '\\2', $data["end"]);
            } else {
                $data['end'] = $dateParts[0];
            }

            if (is_numeric($data['end'])) {
                $data['end'] = (int)$data['end'];
            }
        }
    }
}
