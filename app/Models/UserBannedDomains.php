<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserBannedDomains extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
	protected $table = "user_banned_domains";

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;
}
