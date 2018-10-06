<?php

/*
|--------------------------------------------------------------------------
| Entity Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your entity model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define(App\Models\Entity::class , function(Faker\Generator $faker){
	
	return [
		"report_id" => 12555,
		"type" => $faker->randomElement(['result','sub_result','datapoint','combination']), 
		"deleted"	=> 0 , 
		"hidden"	=>	0
	] ;
});
