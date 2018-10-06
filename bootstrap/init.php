<?php

// Default state data
config([
    'state.environment' => env("APP_ENV"),
    'state.user_id' => null,
    'state.user_role_id' => null,
    'state.report_id' => null,
    'state.combination_id' => null,
    'state.combination_level_id' => null,
    'state.result_id' => null,
    'state.version' => env("APP_VERSION"),
]);

if (env("APP_ENV") != "testing") {
    $settings = \App\Models\Settings::all();
    $settings->each(function ($setting) {
        config(['settings.' . $setting['key']=>$setting['value']]);
    });
}
