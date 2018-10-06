<?php
namespace App\DataTypes;

// username: string

/**
 * Username DataType
 */
class Username extends DataType
{
    protected $type = 'usernames';
    const RULES = [
        'username' => 'required|string',
    ];

    protected function normalizeInputs(array &$data)
    {
        parent::normalizeInputs($data);

        if (!empty($data['username'])) {
            if (stripos($data['username'], "profile.php") !== false) {
                preg_match('#profile\.php\?id\=(\d+)$#i', $data['username'], $usn);
                if (isset($usn[1])) {
                    $data['username'] = $usn[1];
                } else {
                    $data['username'] = 0;
                }
            }
        }

        $this->value = $data['username'];
    }
}
