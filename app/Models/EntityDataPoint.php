<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EntityDataPoint extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'entity_data_point';

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

    public function entities()
    {
        return $this->belongsTo(Entity::class);
    }

    public function results()
    {
        return $this->hasManyThrough();
    }
}
