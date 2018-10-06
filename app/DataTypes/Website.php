<?php
namespace App\DataTypes;

// url: string

/**
 * Website DataType
 */
class Website extends DataType
{
    protected $type = 'websites';
    const RULES = [
        'url' => 'required|regex:/^([a-zA-Z0-9\-]+(\.[a-zA-Z0-9]{2,})+)$/',
    ];

    protected function normalizeInputs(array &$data)
    {
        parent::normalizeInputs($data);

        $data['url'] = str_replace(['http://', 'https://'], '', $data['url']);
        $this->value = $data['url'];
    }
}
