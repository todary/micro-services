<?php
/*
|--------------------------------------------------------------------------
| Detect The Application Environment
|--------------------------------------------------------------------------
|
| Laravel takes a dead simple approach to your application environments
| so you can just specify a machine name for the host that matches a
| given environment, then we will automatically detect it for you.
|
 */

if (env("APP_ENV") != "testing") {
    $is_local = false;

    if (!empty($_SERVER['DESKTOP_SESSION']) || !empty($_SERVER['GDMSESSION']) || isset($_SERVER['SERVER_NAME']) && ($_SERVER['SERVER_NAME'] == 'localhost' || strpos($_SERVER['SERVER_NAME'], '192.168.1.') === 0 || strpos($_SERVER['SERVER_NAME'], '.local') !== false || strpos($_SERVER['HTTP_HOST'], '.dev') !== false) || !file_exists(__DIR__ . '/../.production.env')) {
        $is_local = true;
    }

    if ($is_local) {
        (new Dotenv\Dotenv(__DIR__ . '/../', '.local.env'))->overload();
    } else {
        (new Dotenv\Dotenv(__DIR__ . '/../', '.production.env'))->overload();
    }
}
