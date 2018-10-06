<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\ResultData;

class Result extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'result';

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

    public function entity()
    {
        return $this->belongsTo(Entity::class, 'id');
    }

    public function mainSource()
    {
        return $this->belongsTo(MainSource::class, 'source_id');
    }

    public function convertToResultData(): ResultData
    {
        $resultData = new ResultData($this->url);
        $resultData->id = $this->id;
        $resultData->image = $this->profile_image;
        $resultData->setFlags($this->flags);
        $resultData->setFlags($this->flags);
        $resultData->setScore($this->score);
        $resultData->setScoreIdentities(json_decode($this->score_identity));
        $resultData->setIsDelete($this->is_deleted);
        $resultData->setInvisible($this->invisible);
        
        return $resultData;
    }
}
