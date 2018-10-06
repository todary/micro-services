<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RelationshipLinear extends Model
{
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    protected $table = "relationship_linear";

    public $timestamps = false;

    public function relationship()
    {
    	return $this->belongsTo('App\Models\Relationship');
    }
}
