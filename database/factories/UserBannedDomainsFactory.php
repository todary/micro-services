<?php

/*
|--------------------------------------------------------------------------
| UserBannedDomains Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your entity model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define(App\Models\UserBannedDomains::class , function(Faker\Generator $faker){
	
	return [
		"id" => 1,
		"user_id" => 1, 
		"url"	=> "full url" , 
		"source"	=>	"facebook",
		"dateline"	=>	""
	] ;
});
