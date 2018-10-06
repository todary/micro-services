<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailBlacklist extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'email_blacklist';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;
}
