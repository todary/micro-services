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

$factory->define(App\Models\Report::class , function(Faker\Generator $faker){

	return [
		"id" => 12555,
		"first_name" => $faker->firstName,
		"middle_name" => $faker->name,
		"last_name" => $faker->lastName,
        "country" => $faker->country,
        "usernames" => $faker->userName,
        "added_usernames" => $faker->userName,
	] ;
});
