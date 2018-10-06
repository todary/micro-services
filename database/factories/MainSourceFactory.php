<?php

/*
|--------------------------------------------------------------------------
| Result Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your result model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define(App\Models\MainSource::class , function(Faker\Generator $faker){
	
	return [

		"name" => $faker->name,
		"list_order" => $faker->randomElement([5,10,20,15,25,30,40,45,50,65,60,75,70,80,90,100,110,120,150]),
	] ;
});
