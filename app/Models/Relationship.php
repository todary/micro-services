<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Relationship extends Model
{
    /**
     * FriendShips Reasons Flags
     *
     * used when setting the reason
     * for the relations on update
     * by binary bitmasking using
     * the bitwise | operator
     * i.e. to set the reason to be
     * in site and datapoint set reason=F_Insite|F_DataPoint
     */
    const F_INSITE = 0b0000000000001; ## Found in a certain site like(foursquare , pinterest ,etc)
    const F_DATAPOINT = 0b0000000000010; ## matched with each other datapoint .
    const F_RELATIVES = 0b0000000000100; ## related to relative .
    const F_IMAGE = 0b0000000001000; ## matched with each other images .
    const F_USERNAME = 0b0000000010000; ## matched with username .
    const F_LIST = 0b0000000100000; ## came from certain list .

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'relationship';

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

    public function relationshipsLinear()
    {
        return $this->hasMany(RelationshipLinear::class);
    }

    public function report()
    {
        return $this->belongsTo(Report::class, 'report_id');
    }
}
