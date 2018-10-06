<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $table ="persons";
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

    public function relationships()
    {
        return $this->hasMany(Relationship::class);
    }

    public function relationshipsLinear()
    {
        return $this->hasMany(RelationshipLinear::class);
    }

    public function entities()
    {
        return $this->hasMany(Entity::class);
    }

    public function results()
    {
        $this->hasMany(Result::class, 'person_id');
    }
}
