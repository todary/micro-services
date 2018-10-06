<?php

namespace Skopenow\UrlInfo\ProfileInfo;

use Skopenow\UrlInfo\Interfaces\ProfileInfoInterface;
use Skopenow\UrlInfo\UrlInfo\{CURL, Profile};
use Skopenow\UrlInfo\ProfileInfo;

class VineProfileInfo implements ProfileInfoInterface
{

    private $info;
    private $curl;

    public function __construct(CURL $curl)
    {
        $this->info = (new ProfileInfo)->info;
        $this->curl = $curl;
    }

    public function getProfileInfo(string $url, array $htmlContent)
    {
        \Log::info('URLInfo: getProfileInfo from vine');
        if (!(new Profile($this->curl))->isProfile($url)) {
            return $this->info;
        }

        $profile = $htmlContent;
        if (empty($htmlContent)) {
            $alt_url = $this->alternativeUrl($url);
            if ($alt_url !== false) {
                $url = $alt_url;
            }
            $curl_options = [];
            $curl_options['headers'] = [
                    'Connection' => 'close',
                    'X-Requested-With' => 'XMLHttpRequest',
                    'X-Push-State-Request' => 'true',
                ];
            $profile = $this->curl->curl_content($url, $curl_options);
        }

        if (isset($profile['error_no']) && $profile['error_no']) {
            return $this->info;
        }

        $content = false;

        if (isset($profile['body']) && $profile['body']) {
            $content = json_decode($profile['body'], true);
            if (!empty($content['data'])) {
                $content = $content['data'];
            }
        }

        if (is_array($content) && $content) {
            // Return profile with info
            $this->info['profile'] = $profile;

            // Get name from profile
            $this->getName($content);


            // Get image from profile
            $this->getProfile($content);


            //Get the profile url
            $this->getProfileURL($content);


            // Get location from profile
            $this->getLocation($content);
        }
        return $this->info;
    }

    /**
     * @param $content
     * @return mixed
     */
    private function getName($content)
    {
        if (!empty($content['username'])) {
            $name = $content['username'];
            $this->info['name'] = (trim($name));
        }
        return $content;
    }

    /**
     * @param $content
     * @return mixed
     */
    private function getProfile($content)
    {
        if (!empty($content['avatarUrl']) && $image = $content['avatarUrl']) {
            $this->info['image'] = (trim($image));
        }
        return $content;
    }

    /**
     * @param $content
     */
    private function getProfileURL($content)
    {
        if (array_key_exists('shareUrl', $content)) {
            $this->info['profileUrl'] = trim($content['shareUrl']);
        }
    }

    /**
     * @param $content
     */
    private function getLocation($content)
    {
        if (!empty($content['location'])) {
            $location = $content['location'];
            $this->info['location'][] = (trim($location));
        }
    }

    private function alternativeUrl($url)
    {
        $host = parse_url($url, PHP_URL_HOST);
        $pattern = "#vine.co\/(u\/)?((\w|\d|[\.\-_])+)[^\/\&]*$#i";
        preg_match($pattern, $url, $matches);
        if (isset($matches[2])) {
            $username = $matches[2];
        } else {
            return false;
        }

        if (!is_numeric($username)) {
            $url = 'https://vine.co/api/users/profiles/vanity/' . $username;
            if (!empty($matches[1])) {
                $url = 'https://vine.co/api/users/profiles/' . $username;
            }

            $content = $this->curl->curl_content($url);
            $data = json_decode($content['body'], true);
            if (empty($data['data'])) {
                return false;
            }
            $id = $data['data']['userIdStr'];
        }

        return 'https://api.vineapp.com/users/profiles/' . $id;
    }
}
