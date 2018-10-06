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

$factory->define(App\Models\Result::class , function(Faker\Generator $faker){
    
    $source1 = factory(App\Models\MainSource::class)->create()->name;
    $source2 = factory(App\Models\MainSource::class)->create()->name;
    $source3 = factory(App\Models\MainSource::class)->create()->name;
    $source4 = factory(App\Models\MainSource::class)->create()->name;
    $source5 = factory(App\Models\MainSource::class)->create()->name;
    $source6 = factory(App\Models\MainSource::class)->create()->name;

    $url = $faker->url;
    return [
         "id"   => function () {
            return factory(App\Models\Entity::class)->create()->id;
        } ,
        "report_id" => 12555,
        "source_id" => $faker->randomElement([1,2,3,4,5,6]/*[$source1,$source2,$source3,$source4,$source5,$source6]*/), 
        "source" => $faker->randomElement([1,2,3,4,5,6], [$source1,$source2,$source3,$source4,$source5,$source6]), 
        "main_combination_id"=>154,
        "type"=>"result",
        "raw_type"=>"result",
        "url"   => $url , 
        "unique_content"=> $url, 
        "flags" =>  $faker->randomElement([8192 , 262144, 29 , 7 , 84 , 16 , 5 , 24 , 32 , 554 , 12 , 10 , 21544, 216 , 6546 , 5346 , 4564 , 255, 500, 450, 847555, 64564, 548896 , 134217728]),
        "invisible" =>  $faker->randomElement([1,0 ]),
    ] ;
});
