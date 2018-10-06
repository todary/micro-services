<?php
/**
 * Created by PhpStorm.
 * User: todary
 * Date: 25/11/17
 * Time: 02:29 م
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApiWebhookRequest extends Model
{
    protected $table = "api_webhook_request";

    protected $guarded = array();

    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'person_id', 'url', 'data', 'hash', 'trials', 'last_trial', 'last_reply', 'is_succeeded', 'dateline'];



}