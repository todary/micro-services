<?php

namespace Skopenow\UrlInfo\ProfileInfo;

use Skopenow\UrlInfo\UrlInfo\CURL;
use Skopenow\UrlInfo\ProfileInfo;
use Skopenow\UrlInfo\HtmlDomParser;
use Skopenow\UrlInfo\Interfaces\ProfileInfoInterface;

class MyspaceProfileInfo implements ProfileInfoInterface
{
    private $person;
    private $comb;
    private $curl;
    private $info;

    public function __construct(CURL $curl)
    {
        $this->curl = $curl;
        $this->info = (new ProfileInfo)->info;
        $this->person = ['id' => config('state.report_id')];
        $this->comb = [
            'id' => config('state.combination_id'),
            'person_id' => config('state.user_id')
        ];
    }

    public function getProfileInfo(string $url, array $htmlContent)
    {
        \Log::info('URLInfo: getProfileInfo from myspace');
        $profile = $htmlContent;
        if (empty($htmlContent)) {
            $curl_options = [];
            $profile = $this->curl->curl_content($url, $curl_options);
        }

        if (isset($profile['error_no']) && $profile['error_no']) {
            return $this->info;
        }

        if (isset($profile['body']) && $profile['body']) {
            $content = $profile['body'];
        }

        $this->info['profileUrl'] = $url;

        $content = @substr($content, strpos($content, "<"));
        if ($content) {
            $profilehtml = HtmlDomParser::str_get_html($content);
            if (count($profilehtml)) {
                // Return profile with info ..
                $this->info['profile'] = $content;

                // Get name from profile
                $this->getName($profilehtml);

                // Get image from profile
                $this->getImage($profilehtml);

                // Get Location from profile
                $this->getLocation($profilehtml);

                // Get links profiles
                $this->getLinks($profilehtml);
            }
        }
        return $this->info;
    }

    private function getName($profilehtml)
    {
        $profilehtmlname = $profilehtml->find("a[data-click-object-type=ProfileContextualNav]", 0);
        if (count($profilehtmlname)) {
            $this->info['name'] = (trim($profilehtmlname->innertext));
        }
    }

    private function getImage($profilehtml)
    {
        $profilehtmlname = $profilehtml->find("a#profileImage img", 0);
        if (count($profilehtmlname)) {
            $this->info['image'] = (trim($profilehtmlname->getAttribute("src")));
        }
    }

    private function getLocation($profilehtml)
    {
        $profilehtmlname = $profilehtml->find("div[class=location_white location]", 0);
        if (count($profilehtmlname)) {
            $this->info['location'][] = (trim($profilehtmlname->innertext));
        }
    }

    private function getLinks($profilehtml)
    {
         $profileLinks = $profilehtml->find("#locAndWeb .website a");
        foreach ($profileLinks as $link) {
            $this->info['links'][] = $link->href;
        }

        if ($this->info['links']) {
            $this->info['links'] = array_unique($this->info['links']);
            return;
        }
    }
}
