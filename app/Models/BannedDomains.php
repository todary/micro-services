<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BannedDomains extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
	protected $table = "banned_domains";

    /**
     * The column which could be saved in DB
     *
     * @var array
     */
    protected $fillable = ['domain'];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;
}
