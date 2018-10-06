<?php

namespace Skopenow\UrlInfo\ProfileInfo;

use Skopenow\UrlInfo\ProfileInfo;
use Skopenow\UrlInfo\UrlInfo\CURL;
use Skopenow\UrlInfo\Interfaces\ProfileInfoInterface;

class InstagramProfileInfo implements ProfileInfoInterface
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
        \Log::info('URLInfo: getProfileInfo from instagram');
        $profile = $this->curl->curl_content($url);

        if (!empty($profile['error_no'])) {
            return $this->info ;
        }

        $profilehtml = $profile['body'];
        $re = '/window\._sharedData\s*=([^<]+);<\/script>/is';
        if (preg_match($re, $profilehtml, $match)) {
            $profileData = @json_decode($match[1], true);

            if (!empty($profileData['entry_data']['ProfilePage'][0]['user'])) {
                $profileData = $profileData['entry_data']['ProfilePage'][0]['user'];

                // Return profile with info ..
                $this->info['profile'] = $profilehtml;


                // Get name from profile
                $this->getName($profileData);


                // Get profile pic
                $this->getImage($profileData);
            }
        }
        return  $this->info;
    }

    private function getName($profileData)
    {
        if (!empty($profileData['full_name'])) {
            $this->info['name'] = trim($profileData['full_name']);

            // To clear any html tags beside the name
            if (strpos($this->info['name'], '<')) {
                $this->info['name'] = trim(preg_replace('#<.*#', '', $this->info['name']));
            }
        } elseif (!empty($profileData['username'])) {
            $this->info['name'] = trim($profileData['username']);
            // To clear any html tags beside the name
            if (strpos($this->info['name'], '<')) {
                $this->info['name'] = trim(preg_replace('#<.*#', '', $this->info['name']));
            }
        }
    }

    private function getImage($profileData)
    {
        if (!empty($profileData['profile_pic_url'])) {
            $this->info['image'] = (trim($profileData['profile_pic_url']));
        }
    }
}
