<?php

if (!function_exists('isBannedDomain')) {

    function isBannedDomain(string $domain)
    {
        $entry = loadService('urlInfo');
        return $entry->isBannedDomain($domain);
    }
}

if (!function_exists('isDomain')) {

    function isDomain(string $domain)
    {
        $entry = loadService('urlInfo');
        return $entry->isDomain($domain);
    }
}
