<?php

$factory->define(App\Models\Relationship::class , function(Faker\Generator $faker){
	
	return [
		"report_id" => 12555 ,
		"source_entity" => function () {
            return factory(App\Models\Result::class)->create()->id;
        } ,
		"target_entity"	=> function () {
            return factory(App\Models\Result::class)->create()->id;
        } ,
		"type"		=>	$faker->randomElement(['R2R', 'R2C', 'C2C', 'D2D', 'R2D', 'C2D']),
		"reason"	=>	$faker->randomElement([1 , 2, 4, 8, 16]),
	] ;
});

