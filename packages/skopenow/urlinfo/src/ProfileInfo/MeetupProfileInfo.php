<?php

namespace Skopenow\UrlInfo\ProfileInfo;

use Skopenow\UrlInfo\Interfaces\ProfileInfoInterface;
use Skopenow\UrlInfo\ProfileInfo;
use Skopenow\UrlInfo\HtmlDomParser;
use Skopenow\UrlInfo\UrlInfo\CURL;

class MeetupProfileInfo implements ProfileInfoInterface
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
        \Log::info('URLInfo: getProfileInfo from meetup');
        $profile = $htmlContent;
        if (empty($htmlContent)) {
            $curl_options = [];
            $profile = $this->curl->curl_content($url,$curl_options);
        }

        if (isset($profile['error_no']) && $profile['error_no']) {
            return false;
        }

        if (isset($profile['body']) && $profile['body']) {
            $content = $profile['body'];
        }


        $content = @substr($content, strpos($content, "<"));
        if ($content) {
            $profilehtml = HtmlDomParser::str_get_html($content);

            if (count($profilehtml)) {
                // Return profile with info ..
                $this->info['profile'] = $profilehtml;


                // Get name from profile
                $this->getName($profilehtml);

                // Get location from profile
                $this->getLocation($profilehtml);
            }
        }
        return  $this->info;
    }

    private function getName($profilehtml)
    {
        $profilehtmlname = $profilehtml->find("span[class=memName fn]", 0);
        if (count($profilehtmlname)) {
            $this->info['name'] = (trim($profilehtmlname->innertext));
        }
    }

    private function getLocation($profilehtml)
    {
        $profilehtmllocation = $profilehtml->find("span[class=locality]", 0);
        if (count($profilehtmllocation)) {
            $this->info['location'][] = str_replace("Hometown: ", "",trim($profilehtmllocation->innertext));
        }
    }
}
