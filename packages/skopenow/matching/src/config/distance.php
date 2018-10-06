<?php


config([

    "distance_match_threshold" =>  \Cache::rememberForever("distance_match_threshold" , function(){
        return \Illuminate\Support\Facades\DB::table("settings")->where('key', 'distance_match_threshold')->first();
    }),

    "distance_match_threshold_big_city" => \Cache::rememberForever("distance_match_threshold_big_city" , function(){
        return \Illuminate\Support\Facades\DB::table("settings")->where('key', 'distance_match_threshold_big_city')->first();
    }),
    "distance_match_threshold_small_city" => \Cache::rememberForever("distance_match_threshold_small_city" , function(){
        return \Illuminate\Support\Facades\DB::table("settings")->where('key', 'distance_match_threshold_small_city')->first();
    }),
    'distance_threshold' => \Cache::rememberForever("distance_threshold" , function(){
        return \Illuminate\Support\Facades\DB::table("settings")->where('key', 'distance_threshold')->first();
    }),

]);

