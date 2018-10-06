<?php

/**
 * Created by PhpStorm.
 * User: todary
 * Date: 15/11/17
 * Time: 11:08 ص
 */

namespace Skopenow\Api\Library\AuthUser;

use Skopenow\Api\Library\Errors\ErrorsRequest;
use App\User;

class AuthUser implements AuthUserInterface
{
    protected $authId;
    protected $plan_id;
    protected $api_enabled;
    protected $user;
    protected $plan;


    /**
     * AuthInterface constructor.
     * @param array $AuthData
     */
    public function __construct($AuthUserData)
    {

        $this->authId = $AuthUserData->id;
        $this->plan_id = $AuthUserData->plan_id;
        $this->api_enabled = $AuthUserData->api_enabled;


        if (!$AuthUserData->plan_id) {
            $plan = array(
                'name' => "Default",
                'max_concurrent_searches' => 5,
                'overage_charge' => 5,
            );
        } else {

            $plan = array(
                'name' => $AuthUserData->plan_title,
                'max_concurrent_searches' => $AuthUserData->max_concurrent_searches,
                'overage_charge' => $AuthUserData->addon_id ? $AuthUserData->addon_extra_normal : $AuthUserData->plan_extra_normal
            );

            if ($AuthUserData->addon_id) {
                $plan['name'] .= ":" . ($AuthUserData->plan_max_searches + $AuthUserData->addon_searches_count);
            } else if ($AuthUserData->plan_has_addons) {
                $plan['name'] .= ":" . $AuthUserData->plan_max_searches;
            }

        }

        $plan['search_number'] = 9999;
        $AuthUserData->api_usage = 8888;
        $this->plan = $plan;
        config(['state.user_id' => $this->authId]);

    }

    public function countSearch()
    {
        $count = User::countUserSearchs($this->authId);
        return $count;
    }

    /**
     * @return mixed
     */
    public function getAuthId()
    {
        return $this->authId;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @return array
     */
    public function getPlan(): array
    {
        return $this->plan;
    }

    /**
     * @return mixed
     */
    public function getPlanId()
    {
        return $this->plan_id;
    }

    /**
     * @return mixed
     */
    public function getApiEnabled()
    {
        return $this->api_enabled;
    }


}