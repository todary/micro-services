<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApiAccount extends Model
{
    protected $table = "api_account";

    protected $guarded = array();

    public $timestamps = false;
}
