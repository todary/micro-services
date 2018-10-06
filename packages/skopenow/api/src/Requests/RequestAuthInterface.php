<?php
/**
 * Created by PhpStorm.
 * User: todary
 * Date: 13/11/17
 * Time: 11:36 ص
 */

namespace Skopenow\Api\Requests;
use Skopenow\Api\Library\Auth\AuthInterface;

interface RequestAuthInterface
{

    /**
     * @param AuthInterface $objectAuth
     * @return bool
     */
    public function requestAuth(AuthInterface $authObject): bool;

}