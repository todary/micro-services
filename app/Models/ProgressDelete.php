<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProgressDelete extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'progress_delete';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    public function report()
    {
        return $this->belongsTo(Report::class, 'person_id');
    }
}
