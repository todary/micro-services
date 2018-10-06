<?php
namespace App\DataTypes;

// nickname: string

/**
 * NickName DataType
 */
class NickName extends DataType
{
    protected $type = 'nicknames';
    const RULES = [
        'nickname' => 'required|string',
    ];

    protected function normalizeInputs(array &$data)
    {
        parent::normalizeInputs($data);
        $this->value = $data['nickname'];
    }
}
