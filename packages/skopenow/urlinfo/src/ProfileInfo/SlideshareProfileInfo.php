<?php

namespace Skopenow\UrlInfo\ProfileInfo;

use Skopenow\UrlInfo\Interfaces\ProfileInfoInterface;
use Skopenow\UrlInfo\UrlInfo\CURL;
use Skopenow\UrlInfo\HtmlDomParser;
use Skopenow\UrlInfo\ProfileInfo;

class SlideshareProfileInfo implements ProfileInfoInterface
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
        \Log::info('URLInfo: getProfileInfo from slideshare');
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

        $content = @substr($content, strpos($content, "<"));
        if ($content) {
            $profilehtml = HtmlDomParser::str_get_html($content);
            if (count($profilehtml)) {
                // Return profile with info ..
                $this->info['profile'] = $content;

                // Get name from profile
                $this->getName($profilehtml);

                // Get location from profile
                $this->getLocation($profilehtml);

                // Get profile picture
                $this->getImage($profilehtml);

                // Get work
                $this->getWork($profilehtml);

                // Get Links profiles
                $this->getLinks($profilehtml);
            }
        }
        return $this->info;
    }

    private function getName($profilehtml)
    {
        $profilehtmlname = $profilehtml->find("h1[class=fn]", 0);
        if (!count($profilehtmlname)) {
            $profilehtmlname = $profilehtml->find("h1[class=notranslate fn]", 0);
        }

        if (count($profilehtmlname)) {
            $this->info['name'] = (trim($profilehtmlname->innertext));
        }
    }

    private function getLocation($profilehtml)
    {
        $profileLocation = "";
        $profileCity = $profilehtml->find("li.location span.city", 0);
        if (count($profileCity)) {
            $profileLocation .= (trim($profileCity->innertext));
        }
        $profileComma = $profilehtml->find("li.location span.comma-separater", 0);
        if (count($profileComma)) {
            $profileLocation .= (trim($profileComma->innertext));
        }
        $profileCountry = $profilehtml->find("li.location span.country-name", 0);
        if (count($profileCountry)) {
            $profileLocation .= (trim($profileCountry->innertext));
        }

        if ($profileLocation != "") {
            $this->info['location'] = trim(
                html_entity_decode(
                    strip_tags(
                        str_replace("&nbsp;", " ", $profileLocation)
                    )
                )
            );
            $this->info['location'] = [preg_replace("#(\(.*\))#i", '', $this->info['location'])];

        }
    }

    private function getImage($profilehtml)
    {
        $profilehtmlImage = $profilehtml->find("img[itemprop=image]", 0);
        if ($profilehtmlImage) {
            $image = strpos($profilehtmlImage->src, 'http') === false ? 'http:' . $profilehtmlImage->src : $profilehtmlImage->src;
            $this->info['image'] = str_ireplace('http://', 'https://', $image);
        }
    }

    private function getWork($profilehtml)
    {
        $profilehtmlWork = $profilehtml->find("span[itemprop=jobTitle]", 0);
        if ($profilehtmlWork) {
            $this->info['work'] = $profilehtmlWork->innertext;
        }
    }

    private function getLinks($profilehtml)
    {
        $profile_links = $profilehtml->find("div[class=profile-social-links]", 0);
        if ($profile_links) {
            $pattern = "#(http[^\']+?(twitter|facebook|linkedin|youtube\\.com\\/\\w)[^\']+?)\'#";
            preg_match_all($pattern, $profile_links->innertext, $matches);
            $this->info['links'] = array_values(array_unique($matches[1]));
        }
    }
}
