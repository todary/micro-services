<?php
namespace App\DataTypes;

use Illuminate\Support\Facades\Log;

/**
 * Address DataType
 */
class Address extends DataType
{
    protected $type = 'addresses';
    private $lattitude;
    private $longitude;
    const REFACTOR_KEYS = [
        'add' => 'address',
        'lat' => 'lattitude',
        'lon' => 'longitude',
        '_fullAdress' => 'fullAddress',
        'city' => 'city',
    ];

    const RULES = [
        'full_address' => 'required|string',
        'lattitude' => 'sometimes|double',
        'longitude' => 'sometimes|double',
        'street' => 'sometimes|string',
        'city' => 'sometimes|string',
        'state' => 'sometimes|string',
        'country' => 'sometimes|string',
        'zip' => 'sometimes|string',
    ];

    protected function normalizeInputs(array &$data)
    {
        /*
        333 E 49th St Apt 2r, New York, NY
        E 49th St Apt #2r, New York, NY, USA
        333 E 49th St Apt 2r, New York, NY 12345
        New York, NY
        New York, New York
        New York
        NY
        Cairo, Egypt
         */
        parent::normalizeInputs($data);

        if (!empty($data['full_address']) && empty($data['city']) && empty($data['state'])) {
            $data['city'] = getCity($data['full_address']);
            $data['state'] = getState($data['full_address']);
            if (empty($data['city']) && empty($data['state'])) {
                $addressParts = explode(",", $data['full_address']);
                $data['city'] = trim(end($addressParts));
            }
            if ($data['state'] == 'US') {
                $data['state'] = $data['city'];
                $data['city'] = '';
                $data['country'] = 'US';
            }
        }

        $data['full_address'] = $this->formatData($data);
        Log::debug("Add address data type: {$data['full_address']}\n");

        $this->value = $data['full_address'];
    }
}
