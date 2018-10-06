<?php
/**
 * Created by PhpStorm.
 * User: todary
 * Date: 07/11/17
 * Time: 01:09 م
 */

namespace Skopenow\Api\Library\Auth;


use Skopenow\Api\Library\AuthUser\AuthUser;
use Skopenow\Api\Library\AuthUser\AuthUserInterface;
use Skopenow\Api\Library\Errors\ErrorsRequest;
use App\User;


/**
 * Class AuthMethod
 * @package Skopenow\Api\Library\Auth
 */
class KeyAuthMethod implements AuthInterface
{


    protected $data;
    protected $authUser;
    protected $errors = [];

    /**
     * AuthInterface constructor.
     * @param $data
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }


//    protected $error;


    public function authAPI(): bool
    {

        if (!isset($this->data['x-api-key'])) {
            $errors[] = ['required', 200, 'key', false];
        } else {
            $user = User::getUser($this->data['x-api-key']);
        }


        if (!$user) {
            $this->errors[] = ['error_auth', 120];
        } else if (isset($user->status) && $user->status == 0) {
            $this->errors[] = ['error_auth', 130];
        } else if (isset($user->status) && $user->status == -1) {
            $this->errors[] = ['error_auth', 131];

        } else if (isset($user->api_enabled) && !$user->api_enabled) {
            $this->errors[] = ['error_auth', 120];
        }

        if (!empty($this->errors)) {
            return false;
        }


        $this->authUser = new AuthUser($user);

        return true;
    }


    /**
     * @return AuthUserInterface
     */
    public function getAuthUser(): AuthUserInterface
    {
        return $this->authUser;
    }

    /**
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }


}