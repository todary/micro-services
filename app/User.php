<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

class User extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    /**
     * @param $apiKey
     * @return $user is the user data
     */
    public static function getUser($apiKey)
    {
        $user = DB::table('user as u')->
//        from("user u")->
        leftJoin("corporation as c", "u.corporate_id", "=", "c.id")->
        leftJoin("subscription_plans as p", "c.subscription_plan", "=", "p.id")->
        leftJoin("subscription_plan_prices as pr", "c.subscription_plan_price_id", "=", "pr.id")->
        leftJoin("subscription_plan_addon as pd", "c.subscription_plan_addon_id", "=", "pd.id")->
        select('u.corporate_id', "u.id", "u.name", "c.name as company", "u.status", "u.email", "p.id as plan_id", "pd.id as addon_id", "p.plan_title", "u.api_enabled", "u.api_usage", "u.api_limit_sent", "p.max_concurrent_searches", "pr.extra_normal_search_price as plan_extra_normal", "pd.extra_normal_search_price as addon_extra_normal", "p.max_searches as plan_max_searches", "p.has_addons as plan_has_addons", "pd.searches_count as addon_searches_count","u.api_key","u.id as user_id")->
        where("u.is_deleted", "=", "0")->
        where('u.api_key', '=', "$apiKey")->get()->all();
        if (!empty($user)){
            $user = $user[0];
        }

        return $user;
    }

    /**
     * @param $user_id
     * @return mixed
     */
    public static function getApiKey($user_id)
    {

        $api_key = DB::table('user')->
        select('api_key')->
        where("id", "=", "$user_id")->get()->all();


        if (!empty($api_key)){
            $api_key = $api_key[0];
        }
        return $api_key;
    }

    public static function countUserSearchs($authId)
    {
        $count = DB::table('persons')->select("id")->
        where('completed', '=', '0')->
        where('is_hidden', '=', '0')->
        where('is_api', '=', '1')->
        where('insert_date', '>', (time() - 60 * 60 * 6))->
        where('user_id', '=', '$authId')->count();

        return $count;
    }
}
