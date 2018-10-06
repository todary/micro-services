<?php

if (!function_exists('EncryptID')) {
    function EncryptID($id, $k = null)
    {
        if ($k) {
            $key = $k;
        } else {
            $key = "g%&jkll#g!*545__";
        }
        // $key = "g%&jkll#g!*545__";
        // mcrypt_get_iv_size($key, $mode);
        $iv_size = @mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
        $iv = @mcrypt_create_iv($iv_size, MCRYPT_RAND);
        $crypttext = @mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $key, $id, MCRYPT_MODE_ECB, $iv);
        $crypttext = base64url_encode($crypttext);
        return $crypttext;
    }
}

if (!function_exists('DecryptID')) {
    function DecryptID($encID, $k = null)
    {
        if ($k) {
            $key = $k;
        } else {
            $key = "g%&jkll#g!*545__";
        }
        // $key = "g%&jkll#g!*545__";
        $encID = base64url_decode($encID);
        $iv_size = @mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
        $iv = @mcrypt_create_iv($iv_size, MCRYPT_RAND);
        $decrypttext = @mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $key, $encID, MCRYPT_MODE_ECB, $iv);
    
        return trim($decrypttext);
    }
}

if (!function_exists('base64url_encode')) {
    function base64url_encode($plainText)
    {
        $base64 = base64_encode($plainText);
        $base64 = trim($base64, "=");
        $base64url = strtr($base64, '+/=', '-_.');
        return $base64url;
    }
}

if (!function_exists('base64url_decode')) {
    function base64url_decode($plainText)
    {
        $base64url = strtr($plainText, '-_.', '+/=');
        
        $re = "/[^A-Za-z0-9+\\/=\\-_\\.]/";
        if (preg_match($re, $base64url)) {
            return "";
        }
        
        $base64 = base64_decode($base64url);
        return $base64;
    }
}
