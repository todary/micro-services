<?php
namespace App\DataTypes;

// Save both

/* full_name: string
 */

/* array
first_name: string
middle_name: string
last_name: string
 */

/**
 * Name DataType
 */
class Relative extends DataType
{
    protected $type = 'relatives';
    const RULES = [
        'full_name' => 'required_without_all:first_name,middle_name,last_name|string',
        'first_name' => 'required_without:full_name|string',
        'middle_name' => 'sometimes|string',
        'last_name' => 'required_without:full_name|string',
        'city' => 'sometimes|string',
        'address' => 'sometimes|string',
        'zip' => 'sometimes|string',
    ];

    protected function normalizeInputs(array &$data)
    {
        parent::normalizeInputs($data);

        if (empty($data['first_name']) && !empty($data['full_name'])) {
            $data = name_parts($data['full_name']) + $data;
        } elseif (!empty($data['first_name']) && empty($data['full_name'])) {
            $data['full_name'] = trim($data['first_name'] . ' ' . $data['middle_name']) . ' ' . $data['last_name'];
        }
        $this->value = array_filter(['full_name' => $data['full_name'], 'address' => $data['address'] ?? null]);
    }
}
