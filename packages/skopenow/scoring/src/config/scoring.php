<?php

/* 
 * Where we will place the scoring comparing values .
 */

config([
	
	"scoreIdentities" =>  \Cache::rememberForever("score_identity" , function(){
		return \Illuminate\Support\Facades\DB::table("score_identity")->get()->all();
	}),
			
	"score_single_result" => \Cache::rememberForever("score_single_result" , function(){
		return \Illuminate\Support\Facades\DB::table("score_single_result")->get()->all();
	}),
	"score_results_count" => \Cache::rememberForever("score_results_count" , function(){
		return \Illuminate\Support\Facades\DB::table("score_results_count")->get()->all();
	}),
	'score_sources' => \Cache::rememberForever("score_sources" , function(){
		return \Illuminate\Support\Facades\DB::table("score_sources")->get()->all();
	}),
	"score_type" => \Cache::rememberForever("score_type" , function(){
		return \Illuminate\Support\Facades\DB::table("score_type")->get()->all();
	}),
			
	
]);

