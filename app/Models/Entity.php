<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;

class Entity extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'entity';

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

    public function datapoint()
    {
        return $this->hasOne(EntityDataPoint::class, 'entity_id');
    }

    public function baseRelationshipsLinear()
    {
        return $this->hasMany(RelationshipLinear::class, 'first_party')
            ->orWhere('second_party', $this->id);
    }

    public function relationshipsLinear()
    {
        return $this->reportFilter($this->baseRelationshipsLinear());
    }

    public function baseRelationships()
    {
        return $this->hasMany(Relationship::class, 'source_entity')
            ->orWhere('target_entity', $this->id);
    }

    public function relationships()
    {
        return $this->reportFilter($this->baseRelationships());
    }

    public function result()
    {
        return $this->hasOne(Result::class, 'id');
    }

    public function combination()
    {
        return $this->reportFilter($this->hasOne(Combination::class, 'id'));
    }

    /**
     * Getting Entity relationships where report_id fields are matched
     *
     * @param Relation $relationship Relationship to filter
     *
     * @return Relation
     */
    public function reportFilter(Relation $relationship): Relation
    {
        return $relationship->where('report_id', $this->report_id);
    }
}
