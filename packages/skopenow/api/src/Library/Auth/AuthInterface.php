<?php
/**
 * Created by PhpStorm.
 * User: todary
 * Date: 08/11/17
 * Time: 11:49 ص
 */


namespace Skopenow\Api\Library\Auth;

use Skopenow\Api\Library\AuthUser\AuthUserInterface;


/**
 * Interface AuthInterface
 * @package Skopenow\Api\Library\Auth
 */
interface AuthInterface
{

    /**
     * AuthInterface constructor.
     * @param $data
     */
    public function __construct(array $data);

    /**
     * @return bool
     */
    public function authAPI(): bool;

    /**
     * @return AuthUserInterface
     */
    public function getAuthUser(): AuthUserInterface;

    /**
     * @return array
     */
    public function getErrors(): array;
}