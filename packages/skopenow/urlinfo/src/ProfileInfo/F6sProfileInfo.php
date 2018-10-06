<?php

namespace Skopenow\UrlInfo\ProfileInfo;

use Skopenow\UrlInfo\Interfaces\ProfileInfoInterface;
use Skopenow\UrlInfo\ProfileInfo;
use Skopenow\UrlInfo\HtmlDomParser;
use Skopenow\UrlInfo\UrlInfo\CURL;


class F6sProfileInfo implements ProfileInfoInterface
{
    private $curl;
    private $url;
    private $info;

    public function __construct(CURL $curl)
    {
        $this->info = (new ProfileInfo)->info;
        $this->curl = $curl;
    }

    public function getProfileInfo(string $url, array $htmlContent)
    {
        $this->url = $url;
        \Log::info('URLInfo: getProfileInfo from F6S, URL: ' . $url);
        $profile = $htmlContent;
        if (empty($htmlContent)) {
            $curl_options = [];
            $profile = $this->curl->curl_content($url, $curl_options);
        }

        if ((isset($profile['error_no']) && $profile['error_no']) || empty($profile['body'])) {
            \Log::info('URLInfo: getProfileInfo ... 404 profile not found' . $this->url);
            return $this->info;
        }
        $content = '';
        if (isset($profile['body']) && $profile['body']) {
            $content = $profile['body'];
        }

        if (!empty($content)) {
            $profilehtml = HtmlDomParser::str_get_html($content);

            if (!empty($profilehtml)) {
                $this->info['profile'] = $content;
                // Get name from profile
                $this->getName($profilehtml);

                // Get location from profile
                $this->getLocation($profilehtml);

                // Get image from profile
                $this->getImage($profilehtml);

                // Get links profiles
                $this->getLinks($profilehtml);
            }
        }
        return $this->info;
    }

    private function getName($profilehtml)
    {
        \Log::info('URLInfo: getProfileInfo getting name from profile: ' . $this->url);
        $titleNode = $profilehtml->find(".cover-title");
        if ($titleNode && isset($titleNode[0])) {
            $this->info['name'] = $titleNode[0]->innertext;
            \Log::info('URLInfo: getProfileInfo ... got name: ' . $titleNode[0]->innertext);
        } else {
            \Log::info('URLInfo: getProfileInfo ... no name found in this profile');
        }
    }

    private function getLocation($profilehtml)
    {
        \Log::info('URLInfo: getProfileInfo getting location');
        $locationNode = $profilehtml->find("#csInlineLocation .inner");
        if ($locationNode && isset($locationNode[0])) {
            $this->info['location'][] = trim(strip_tags($locationNode[0]->innertext));
            \Log::info('URLInfo: getProfileInfo ... got location');
        } else {
            \Log::info('URLInfo: getProfileInfo ... no location found in this profile');
        }
    }

    private function getImage($profilehtml)
    {
        \Log::info('URLInfo: getProfileInfo getting image');
        $imageNode = $profilehtml->find(".profile-picture", 0);
        if ($imageNode) {
            preg_match("#src\s*=\s*['\"](.+?)['\"]#i", $imageNode->innertext, $matches);
            if (isset($matches[1])) {
                $this->info['image'] = $matches[1];
                \Log::info('URLInfo: getProfileInfo ... got Image: ' . $matches[1]);
                return;
            }
        }
        \Log::info('URLInfo: getProfileInfo ... no Image found in this profile');
    }

    private function getLinks($profilehtml)
    {
        \Log::info('URLInfo: getProfileInfo getting links');
        $profileLinks = $profilehtml->find(".cover-links a");
        if (count($profileLinks > 0)) {
            foreach ($profileLinks as $link)
            {
                if (!$link->href) {
                    continue;
                }
                $this->info['links'][] = $link->href;
            }
        }
        if ($this->info['links']) {
            $this->info['links'] = array_unique($this->info['links']);
            \Log::info('URLInfo: getProfileInfo ... got Links: ', $this->info['links']);
        }
    }
}
