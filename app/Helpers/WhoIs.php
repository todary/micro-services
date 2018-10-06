<?php

use Illuminate\Support\Facades\App;

if (!function_exists('getEmailFromWhoIsResult')) {
    function getEmailFromWhoIsResult($result)
    {
        if (!isset($result['snippet']) || !$result['snippet']) {
            return false;
        }

        return cutEmailFromTest($result['snippet']);
    }
}

if (!function_exists('getEmailsByWhoIs')) {
    function getEmailsByWhoIs($url)
    {
        $return = array();
        $url = 'http://dnstools.com/?target=' . $url;
        $html = curl_content($url);
        if (!array_key_exists('body', $html) or !$html['body']) {
            return $return;
        }
        $htmlObj = str_get_html($html['body']);
        if (!$htmlObj || empty($htmlObj->find('pre', 0)->plaintext)) {
            return $return;
        }

        $text = $htmlObj->find('pre', 0)->plaintext;

        $return[] = getEmailFromWhoIsText($text, 'Admin Email');
        $return[] = getEmailFromWhoIsText($text, 'Registrant Email');

        return array_unique($return);
    }
}

if (!function_exists('getEmailFromWhoIsText')) {
    function getEmailFromWhoIsText($text, $word)
    {
        if (!$text || !$word) {
            return false;
        }

        try {
            //ex:: this is my Admin Email: email@email.com and not accually an email
            $firstSlice = stristr($text, $word); //Admin Email: email@email.com and not accually an email
            if (!$firstSlice) {
                return false;
            }

            $colonSlice = strstr($firstSlice, ':'); //: email@email.com and not accually an email
            $emailSlice = trim(trim($colonSlice, ':')); //email@email.com and not accually an email
            if (strpos($emailSlice, '@') === false) {
                $email = trim(strstr($emailSlice, ' ', true)); //email@email.com
            }

            if (isset($email)) {
                $email = preg_replace('/[^a-zA-Z@.0-9_]/', '', $remaining);
                if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    return $email;
                }
            }
            return false;
        } catch (Exception $ex) {
            $ex_syn = "[error] " . date("d/m/Y : H:i:s", time()) . "\n";
            $ex_syn .= $ex . "\n";
            $ex_syn .= "--------------------------------------------------------\n";
            // logginig:: SearchApis::logData($personId, $ex_syn, $text, "ERROR");
            if (App::environment('local')) {
                throw $ex;
            }

            return false;
        }
    }
}
