<?php
$app->post('extract/extractFacebookPosts', 'Skopenow\Extract\ExtractController@extractFacebookPosts');
$app->post('extract/extractFacebookUserImages', 'Skopenow\Extract\ExtractController@extractFacebookUserImages');
$app->post('extract/extractFacebookPageImages', 'Skopenow\Extract\ExtractController@extractFacebookPageImages');
$app->post('extract/extractYoutubeProfiles', 'Skopenow\Extract\ExtractController@extractYoutubeProfiles');
$app->post('extract/extractTwitterPosts', 'Skopenow\Extract\ExtractController@extractTwitterPosts');
$app->post('extract/extractTwitterMedia', 'Skopenow\Extract\ExtractController@extractTwitterMedia');
$app->post('extract/extractInstagramImages', 'Skopenow\Extract\ExtractController@extractInstagramImages');
$app->post('extract/extractLinkedinSkills', 'Skopenow\Extract\ExtractController@extractLinkedinSkills');
$app->post('extract/extractLinkedinEndorsersUsingSkills', 'Skopenow\Extract\ExtractController@extractLinkedinEndorsersUsingSkills');