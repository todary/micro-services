<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\ResultData;

class SubResult extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'sub_result';

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

    public function CreateFromSubResultData(SubResultDataInterface $resultData)
    {
        $this->report_id = $resultData->report_id;
        $this->entity_id = $resultData->entity_id;
        $this->result_id = $resultData->parent_id;
        $this->type = $resultData->type;
        $this->url  = $resultData->url;
        $this->unique_content  = $resultData->unique_url;
        $this->tags  = '[]';
        $this->is_deleted = $resultData->isDelete;
        $this->child_rank = $resultData->rank;
        $this->is_parent = $resultData->is_parent;

        if ($this->save()) {
            $resultData->id = $this->id;
            return true;
        }

        return false;
    }

}
