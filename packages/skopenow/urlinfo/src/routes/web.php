<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

// $app->get('/urlinfo', 'Skopenow\UrlInfo\UrlInfoController@index');
$app->post('/urlinfo/profile_exists', 'Skopenow\UrlInfo\UrlInfoController@profileExists');
$app->post('/urlinfo/is_profile', 'Skopenow\UrlInfo\UrlInfoController@isProfile');
$app->post('/urlinfo/profile_image', 'Skopenow\UrlInfo\UrlInfoController@profileImage');
$app->post('/urlinfo/prune', 'Skopenow\UrlInfo\UrlInfoController@prune');
$app->post('/urlinfo/source', 'Skopenow\UrlInfo\UrlInfoController@source');
$app->post('/urlinfo/normalize', 'Skopenow\UrlInfo\UrlInfoController@urlNormalizer');
$app->post('/urlinfo/username', 'Skopenow\UrlInfo\UrlInfoController@username');
$app->post('/urlinfo/site_tag', 'Skopenow\UrlInfo\UrlInfoController@siteTag');
