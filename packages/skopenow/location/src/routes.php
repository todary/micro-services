<?php

$app->post('location/distance', 'Skopenow\Location\LocationController@distance');
$app->post('location/city', 'Skopenow\Location\LocationController@findCities');
$app->post('location/address', 'Skopenow\Location\LocationController@findAddress');
$app->post('location/latLng', 'Skopenow\Location\LocationController@findLatLng');
$app->post('location/located', 'Skopenow\Location\LocationController@locatedInUS');
$app->post('location/nearest', 'Skopenow\Location\LocationController@nearestCities');
$app->post('location/zipcodes', 'Skopenow\Location\LocationController@getCityZipCodes');
$app->post('location/stateAbv', 'Skopenow\Location\LocationController@getStateAbv');
$app->post('location/stateName', 'Skopenow\Location\LocationController@getStateName');
$app->post('location/statebyarea', 'Skopenow\Location\LocationController@getStateNameByAreaCode');
$app->post('location/normalizestate', 'Skopenow\Location\LocationController@normalizeStateName');

