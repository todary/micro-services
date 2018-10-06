<?php
namespace App\DataTypes;

// phone: string

/**
 * Phone DataType
 */
class Phone extends DataType
{
    protected $type = 'phones';
    const RULES = [
        'phone' => 'required|string',
    ];

    protected function normalizeInputs(array &$data)
    {
        parent::normalizeInputs($data);
        $this->value = $data['phone'];
    }
}
