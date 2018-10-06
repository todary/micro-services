<?php

namespace Skopenow\UrlInfo\ProfileInfo;

use Skopenow\UrlInfo\Interfaces\ProfileInfoInterface;
use Skopenow\UrlInfo\UrlInfo\CURL;
use Skopenow\UrlInfo\HtmlDomParser;
use Skopenow\UrlInfo\ProfileInfo;


class FlickrProfileInfo implements ProfileInfoInterface
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
        \Log::info('URLInfo: getProfileInfo from Flickr');
        $profile = $htmlContent;
        if (empty($html_content)) {
            $curl_options = [];
            $profile = $this->curl->curl_content($url, $curl_options);
        }

        if (isset($profile['error_no']) && $profile['error_no']) {
            return false;
        }

        if (isset($profile['body']) && $profile['body']) {
            $content = $profile['body'];
        }

        $content = @substr($content, strpos($content, "<"));
        if ($content) {
            $profile_html = HtmlDomParser::str_get_html($content);
            if (count($profile_html)) {

                // Return profile with info ..
                // $this->info['profile'] = $profile_html;

                // Get name from profile
                $this->getName($profile_html);


                // Get location from profile
                $this->getLocation($profile_html);

                // Get image from profile
                $this->getImage($profile_html);
            }
        }
        return  $this->info;
    }

    private function getName($profile_html)
    {
        $profile_html_name = $profile_html->find("span[class=character-name-holder]", 0);
        $first_alter_name = $profile_html->find(".person>h2", 0);
        $second_alter_name = $profile_html->find("div[class=profile-section] span[class=given-name]", 0);
        $this->info['name'] = "";
        if (count($profile_html_name)) {
            $this->info['name'] = (trim($profile_html_name->innertext));

            if (count($first_alter_name)) {
                $first_alter_name = (trim($first_alter_name->innertext));
                if (strtolower($this->info['name']) != strtolower($first_alter_name)) {
                    if (count(explode(" ", strtolower($first_alter_name))) > count(explode(" ",$this->info['name']))) {
                        $this->info['name'] = $first_alter_name;
                    }
                }
            }

            if (count($second_alter_name)) {
                $second_alter_name = (trim($second_alter_name->innertext));
                if (strtolower($this->info['name']) != strtolower($second_alter_name)) {
                    if (count(explode(" ", strtolower($second_alter_name))) > count(explode(" ", $this->info['name']))) {
                        $this->info['name'] = $second_alter_name;
                    }
                }
            }
        }
    }

    private function getLocation($profile_html)
    {
        $profile_html_location = $profile_html->find("div[class=profile-section] span[class=adr] span[class=fn]", 0);

        if (count($profile_html_location)) {
            $this->info['location'][] = (trim($profile_html_location->innertext));
        }
    }

    private function getImage($profile_html)
    {
        $profile_html_image = $profile_html->find("div[class=sn-avatar] img[class=sn-avatar-ico]", 0);
        $this->info['image'] = "";
        if (count($profile_html_image)) {
            $this->info['image'] = (trim($profile_html_image->src));
        }
    }
}
