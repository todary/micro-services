<?php
namespace App\DataTypes;

use Illuminate\Support\Carbon;

/**
 * Age DataType
 */
class Age extends DataType
{
    const RULES = [
        'age' => 'required_without:dob|integer',
        'dob' => 'required_without:age|string',
    ];

    protected function validate($inputs)
    {
        parent::validate($inputs);
        if ($inputs['age'] == '' || $inputs['age'] < 0) {
            throw new \Exception('Invalid Data: ' . print_r($inputs, true) . "\nReason: Age rules not matched");
        }
    }

    protected function normalizeInputs(array &$data)
    {
        parent::normalizeInputs($data);

        if (!empty($data['age'])) {
            $data['dob'] = (date('Y') - $data['age']) . '-01-01';
        } else {
            $data['age'] = Carbon::parse($data['dob'])->diffInYears(Carbon::now());
        }
        $this->value = $data['age'];
    }
}
