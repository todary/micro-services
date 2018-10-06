<?php
namespace App\DataTypes;

/**
 * Email DataType
 */
class Email extends DataType
{
    protected $type = 'emails';

    const RULES = [
        'email' => 'required|email',
    ];

    protected function normalizeInputs(array &$data)
    {
        parent::normalizeInputs($data);
        $data['email'] = trim($data['email']);
        $email = $this->formatData($data['email']);
        $this->value = $email;
    }
}
