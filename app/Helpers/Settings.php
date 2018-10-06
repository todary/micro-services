<?php

if (!function_exists('getDBSetting')) {
    function getDBSetting($key) {
        $setting = \App\Models\Settings::where("key", $key)->first();
        return $setting;
    }
}
