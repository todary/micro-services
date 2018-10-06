<?php

if (!function_exists('load_data'))
{
    function loadData($file)
    {
        return include __DIR__ . "/../../resources/data/{$file}.php";
    }
}
