<?php
/**
 * Created by PhpStorm.
 * User: todary
 * Date: 14/11/17
 * Time: 05:44 م
 */

namespace Skopenow\Api\Library\Authorization;


use Skopenow\Api\Library\AuthUser\AuthUserInterface;

interface AuthorizationInterface
{
    public function __construct(AuthUserInterface $authUser);

    public function canSearch();

    public function getErrors();
}