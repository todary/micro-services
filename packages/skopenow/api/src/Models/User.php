<?php
/**
 * Created by PhpStorm.
 * User: todary
 * Date: 12/11/17
 * Time: 04:47 م
 */

namespace Skopenow\Api\Models;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
/**
 * Class User
 * @package Skopenow\Api\Models
 */
class User extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];


    /**
     * @param $apiKey
     * @return $user is the user data
     */
    public static function getUser($apiKey)
    {
        $user = DB::table('roles')->
        select("u.id,u.name ,c.name as company ,u.status, u.email, p.id plan_id, pd.id addon_id, p.plan_title, u.api_enabled,u.api_usage,u.api_limit_sent, p.max_concurrent_searches, pr.extra_normal_search_price plan_extra_normal, pd.extra_normal_search_price addon_extra_normal, p.max_searches plan_max_searches, p.has_addons plan_has_addons, pd.searches_count addon_searches_count")->
        from("user u")->
        leftJoin("corporation c", "u.corporate_id = c.id")->
        leftJoin("subscription_plans p", "c.subscription_plan = p.id")->
        leftJoin("subscription_plan_prices pr", "c.subscription_plan_price_id = pr.id")->
        leftJoin("subscription_plan_addon pd", "c.subscription_plan_addon_id = pd.id")->
        where('u.is_deleted = 0 and u.api_key=:key', array(':key' => $apiKey))->get();

        return $user;
    }
}

