<?php
$app->post('nameInfo/nameSplit', 'Skopenow\NameInfo\NameInfoController@nameSplit');
$app->post('nameInfo/nickNames', 'Skopenow\NameInfo\NameInfoController@nickNames');
$app->post('nameInfo/uniqueName', 'Skopenow\NameInfo\NameInfoController@uniqueName');