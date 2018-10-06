<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Result;

class MainSource extends Model
{
    
	protected $table = "main_source" ;

	protected $dates = [];

	public $timestamps = false;

	public function result()
	{
		$this->hasMany(Result::class, 'source_id');
	}

}
