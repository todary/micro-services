<?php

namespace Skopenow\UrlInfo\UrlInfo;

/**
 * Prune and Prepare Given URL
 */
class Prune
{
    /**
     * Prepare givven URL
     * @param  string $url
     *
     * @return string
     */
    public function prepareContent(string $url) : string
    {
        $url = str_replace('https://', 'http://', $url);
        $url = str_replace('http://www.', 'http://', $url);
        $url = rtrim($url, "/");
        $url = rtrim($url, "?");
        $url = rtrim($url, "&");
        $url = rtrim($url, "/");

        if (stripos($url, 'pipl.com') !== false) {
            $url = preg_replace("/t=([^&]*)&/", '', $url);
        }

        $url = strtolower($url);
        $url = mb_substr($url, 0, 350);

        return $url;
    }
}
