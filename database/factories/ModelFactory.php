<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define(App\User::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->email,
    ];
});

// $factory->define(App\Models\Result::class , function(Faker\Generator $faker){
	
// 	return [
// 		// "id"	=> 1000000 ,
// 		"report_id" => 12555,
// 		"source_id" => 22 ,
// 		"url"	=> $faker->url , //"https://linkedin.com/linkedin/ahmedsamir732" ,
// 		// "main_source" => "linkedin",
// 		"flags"	=>	262144 
// 	] ;
// });

// $factory->define(App\Models\Result::class , function(Faker\Generator $faker){
	
// 	return[
// 		// "id"	=> 25144 ,
// 		"report_id" => 12555,
// 		"source_id" => 22 ,
// 		"url"	=> "https://facebook.com/ahmedsamir" ,
// 		// "main_source" => "linkedin",
// 		"flags"	=>	256 
// 	];
// });


	
