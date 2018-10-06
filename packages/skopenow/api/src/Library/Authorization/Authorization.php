<?php
/**
 * Created by PhpStorm.
 * User: todary
 * Date: 14/11/17
 * Time: 05:42 م
 */

namespace Skopenow\Api\Library\Authorization;

use Skopenow\Api\Library\AuthUser\AuthUserInterface;
use Skopenow\Api\Library\Errors\ErrorsRequest;

class Authorization implements AuthorizationInterface
{
    protected $auth;
    protected $account;
    protected $errors;

    /**
     * Authorization constructor.
     * @param AuthUserInterface $authUser
     */
    public function __construct(AuthUserInterface $authUser)
    {
        $this->auth = $authUser;
        $this->account = \CAccount::of($authUser->getAuthId());
    }

    public function canSearch()
    {

        if (isset($this->account)) {
            $result = $this->account->canSearch();
        }
        if (!$result) {
            $this->errors[] = ['insufficient_credit', 600];
        }
        return $result;
    }

    public function checkLimit()
    {
        if ($this->auth->getPlan() &&
            $this->auth->countSearch() >= $this->auth->getPlan()['max_concurrent_searches'] &&
            $this->auth->getPlan()['max_concurrent_searches'] != 0) {
            ## Send Mail
            $emailParams = array(
                '{user_name}' => $this->auth->getUser()['name'],
                '{company}' => $this->auth->getUser()['company'],
                '{plan}' => $this->auth->getUser()['name'],
                '{rate_limit}' => $this->auth->getUser()['max_concurrent_searches'],
            );
            $this->errors[] = ['error', 429];
//            Yii::app()->EmailHelper->SendEmail("api_limit_rate_exceeded", $this->user['email'], $emailParams, $send = false);
            return false;
        }
        return true;
    }

    /**
     * @return mixed
     */
    public function getErrors()
    {
        return $this->errors;
    }


}