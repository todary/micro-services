<?php

namespace Skopenow\UrlInfo\ProfileInfo;

use Skopenow\UrlInfo\Interfaces\ProfileInfoInterface;
use Skopenow\UrlInfo\ProfileInfo;
use Skopenow\UrlInfo\UrlInfo\CURL;


class FoursquareProfileInfo implements ProfileInfoInterface
{
    private $curl;
    private $info;

    public function __construct(CURL $curl)
    {
        $this->info = (new ProfileInfo)->info;
        $this->curl = $curl;
    }

    public function getProfileInfo(string $url, array $htmlContent)
    {
        \Log::info('URLInfo: getProfileInfo from Fiverr');
        if (empty($htmlContent)) {
            $htmlContent = $this->curl->curl_content($url);
        }

        $content = (is_array($htmlContent) && array_key_exists('body', $htmlContent)) ? $htmlContent['body'] : '';


        if (stripos($url, "api.foursquare")) {
            $this->info = $this->extractDataFromAPI($content);
        } elseif (stripos($url, "foursquare.com")) {
            $this->info = $this->extractDataFromHtml($content, $url);
        }
        if (empty($this->info['profileUrl'])) {
            $this->info['profileUrl'] = $url;
        }
        return $this->info ;
    }

    private function extractDataFromAPI($content)
    {
        $data = @json_decode($content, true);
        if (!empty($data) && is_array($data)) {
            $this->info['status'] = 404 ;
            if (
                !empty($data['meta']) &&
                $data['meta']['code'] == 200 &&
                !empty($data['response']['user'])
            ) {
                $userData = $data['response']['user'];
                // $this->info['profileUrl'] = $userData['canonicalUrl'];
                $this->info['status'] = 1;

                $this->info['name'] .= isset($userData['firstName']) ? $userData['firstName'] . ' ' : '';
                $this->info['name'] .= isset($userData['lastName']) ? $userData['lastName'] : '';
                if (!empty($userData['homeCity'])) {
                    $this->info['location'] = $userData['homeCity'];
                }

                if (!empty($userData['photo'])) {
                    $this->info['image'] = trim($userData['photo']['prefix'], "/ ") . "/130x130/" . trim($userData['photo']['suffix'], "/ ");
                }
                if (!empty($userData['contact']['facebook'])){
                    $this->info['links'][] = $userData['contact']['facebook'];
                }
                if (!empty($userData['contact']['twitter'])
                ) {
                    $this->info['links'][] = $userData['contact']['twitter'];
                }
                $this->info['profileUrl'] = $userData['canonicalUrl'];
            }
        }
        return $this->info ;
    }

    private function extractDataFromHtml($content, $url)
    {
        $this->info['profileUrl'] = $url;
        $re = '/<meta\s*content\=\"playfoursquare:person\"\s*property\=\"og:type\"\s*\/><meta\s*content\=\"(.*)\"\s*property\=\"og:url"\s*\/>/i';
        preg_match($re, $content, $match);

        if (!empty($match[1])) {
            $this->info['profileUrl'] = $match[1];
        }

        $re = '/<h1 class="name">([^<]+)<\/h1>/si';
        if (preg_match($re, $content,$match)) {
            $this->info['name'] = $match[1];
        }

        $re = '/<span class="userLocation">([^<]+)<\/span>/si';
        if (preg_match($re, $content,$match)) {
            $this->info['location'][] = $match[1];
        }

        $re = '/class="userPic"><img\s*src="([^"]+)"/si';
        if (preg_match($re, $content,$match)) {
            $this->info['image'] = $match[1];
        }

        $re = '/<a href="([^"]+)" rel="nofollow" target="_blank" class="fbLink iconLink"/si';
        if (preg_match($re, $content,$match)) {
            $this->info['links'][] = $match[1];
        }

        $re = '/<a href="([^"]+)" rel="nofollow" target="_blank" class="twLink iconLink"/si';
        if (preg_match($re, $content,$match)) {
            $this->info['links'][] = $match[1];
        }

        return $this->info;
    }
}
