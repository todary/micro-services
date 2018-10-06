<?php

$factory->define(App\Models\RelationshipLinear::class , function(Faker\Generator $faker){
	
	$relationship = factory(App\Models\Relationship::class)->create() ;
	return [
		"report_id" => 12555 ,
		"relationship_id"	=> $relationship->id , 
		"first_party"	=>	$relationship->source_entity ,
		"second_party"	=>	$relationship->target_entity ,
	] ;
});

