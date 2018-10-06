<?php

if (!function_exists('request_ip')) {
    function request_ip() {
        if (isset($_SERVER['HTTP_CLIENT_IP']) && !empty($_SERVER['HTTP_CLIENT_IP'])) {
            //check ip from share internet
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && !empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            //check ip is passed from proxy
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR']??null;
        }
        return substr($ip, 0, 20);
    }
}

if (!function_exists('request_user_agent')) {
    function request_user_agent() {
        return $_SERVER['HTTP_USER_AGENT'] ?? null;
    }
}
