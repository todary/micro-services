<?php
namespace App\DataTypes;

/*
company: string

title: string
start: date/year
end: date/year
*/

/**
 * Work DataType
 */
class Work extends DataType
{
    const RULES = [
        'company' => 'required_without:title|string',
        'title' => 'required_without:company|string',
        'start' => 'sometimes|int|between:1900,2050',
        'end' => 'sometimes|int|between:1900,2050',
    ];

    protected function normalizeInputs(array &$data)
    {
        parent::normalizeInputs($data);

        $this->value = $data['company'];

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
