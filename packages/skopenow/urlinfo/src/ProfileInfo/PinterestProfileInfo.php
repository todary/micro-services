<?php

namespace Skopenow\UrlInfo\ProfileInfo;

use Skopenow\UrlInfo\Interfaces\ProfileInfoInterface;
use Skopenow\UrlInfo\UrlInfo\CURL;
use Skopenow\UrlInfo\UrlInfo\Username;
use Skopenow\UrlInfo\UrlInfo\URLNormalizer;
use Skopenow\UrlInfo\HtmlDomParser;
use Skopenow\UrlInfo\ProfileInfo;

class PinterestProfileInfo implements ProfileInfoInterface
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
        \Log::info('URLInfo: getProfileInfo from Pinterest');
        $profile = $htmlContent;

        if (empty($htmlContent)) {
            $curl_options = [];
            $curl_options["headers"] = [
                "Connection: close",
                "X-Requested-With:XMLHttpRequest",
                "X-Push-State-Request:true"
            ];
            $profile = $this->curl->curl_content($url, $curl_options);
        }

        if (isset($profile['error_no']) && $profile['error_no']) {
            return $this->info;
        }



        $content = $profile['body'];
        $profile = @json_decode($content , true);

        $profileHTML = [];
        $json = [];

        if (isset($profile["module"]["html"])) {
            $content = $profile["module"]["html"];
            $profileHTML = HtmlDomParser::str_get_html($content);
            if (isset($profile["module"]["tree"]['data'])) {
                $json = $profile["module"]['tree']['data'];
            }
        } elseif (!isset($profile["module"]["html"])) {
            $profileHTML = HtmlDomParser::str_get_html($content);
            preg_match('#jsInit1[^>]*>([^<]*)#is', $content, $match);
            if (isset($match[1])) {
                $extractJson = @json_decode($match[1] , true);
                if(isset($extractJson['tree']['data'])) {
                    $json = $extractJson['tree']['data'];
                }
            }
        }

        if (count($profileHTML) && !empty($content)) {
            // Return profile with info ..
            $this->info['profile'] = $profileHTML;

            // Get name from profile
            $this->info['name'] = $this->getName($json, $content, $profileHTML);

            // Get Location from profile
            $this->info['location'] = $this->getLocation($json, $content, $profileHTML);

            // Get profile picture from profile
            $this->info['image'] = $this->getImage($json, $profileHTML);

            // Get bio from profile
            $this->info['bio'] = $this->getBio($json, $content, $profileHTML);

            // Get Twitter from profile
            $twitter = $this->getTwitter($json, $content, $profileHTML);
            if (!empty($twitter)) {
                $this->info['links'][] = $twitter;
            }

            // Get Facebook from profile
            $facebook = $this->getFacebook($json, $content, $profileHTML);
            if (!empty($facebook)) {
                $this->info['links'][] = $facebook;
            }
        }
        return $this->info;
    }

    /**
     * @param $json
     * @param $content
     * @param $profileHTML
     * @return mixed
     */
    private function getName($json, $content, $profileHTML)
    {
        $name = "";
        if (!empty($json['full_name'])) {
            $name = trim($json['full_name']);
        } elseif (preg_match('/class=\"_su _sm _ju _js _st _t7 _5k _sr\"/', $content)) {
            $profilehtmlname = $profileHTML->find("h1[class=name]");
            if (count($profilehtmlname) > 0) {
                $name = trim(strip_tags($profilehtmlname[0]->innertext));
            }
        } elseif (preg_match('/class="fullname"/', $content)) {
            $profilehtmlname = $profileHTML->find("h4[class=fullname]");
            if (count($profilehtmlname) > 0) {
                $name = trim(strip_tags($profilehtmlname[0]->innertext));
            }
        }
        return $name;
    }

    /**
     * @param $pinterestFromJson
     * @param $content
     * @param $profilehtml
     * @return mixed
     */
    private function getLocation($pinterestFromJson, $content, $profilehtml)
    {
        $location = [];
        if (isset($pinterestFromJson['location'])) {
            if (!empty($pinterestFromJson['location'])) {
                $location[] = trim($pinterestFromJson['location']);
            }
        } elseif (preg_match('/class="locationWrapper"/', $content)) {
            $profilehtmllocation = $profilehtml->find("div.about .locationWrapper");

            if (count($profilehtmllocation) > 0) {
                $location[] = trim(strip_tags($profilehtmllocation[0]->innertext));
            }
        }
        return $location;
    }

    /**
     * @param $json
     * @param $profileHTML
     * @return string
     */
    private function getImage($json, $profileHTML)
    {
        $image = '';
        if (isset($json['image_large_url'])) {
            if (!empty($json['image_large_url'])) {
                $image = $json['image_large_url'];
            }

        } else {
            $pages = $profileHTML->find("div.profileImage", 0);
            if ($pages) {
                preg_match("#src\s*=\s*['\"](.+?)['\"]#i", $pages->innertext, $matches);

                if (isset($matches[1])) {
                    $image = $matches[1];
                }
            }
        }
        return $image;
    }

    /**
     * @param $json
     * @param $content
     * @return mixed
     */
    private function getBio($json, $content)
    {
        $bio = "";
        if (!empty($json['about'])) {
            $bio = trim($json['about']);

        } elseif (preg_match('/userLocationDescription.*?<div class="antialiased[^>]+>(.*?)<\/div>/si', $content, $match)) {
            $bio = $match[1];
            $bio = trim($bio);
        }
        return $bio;
    }

    /**
     * @param $json
     * @param $content
     * @param $profileHTML
     * @return array
     */
    private function getTwitter($json, $content, $profileHTML)
    {
        $twitter = null;
        if (!empty($json['twitter_url'])) {
            $twitter = $json['twitter_url'];
        } elseif (preg_match('/class="twitter"/', $content)) {
            $profileTwitter = $profileHTML->find("a.twitter");
            if (count($profileTwitter) > 0) {
                $twitter = trim($profileTwitter[0]->href);
            }
        }

        if (!empty($twitter) && strripos($twitter, 'twitter') !== false) {
            if (!$this->info['location']) {
                $htmlContent = ['body' => $content];
                $profileInfo = (new TwitterProfileInfo($this->curl))->getProfileInfo(
                    $twitter,
                    $htmlContent
                );
                if (isset($profileInfo['location'])) {
                    $this->info['location'] = $profileInfo['location'];
                }
            }
        }
        return $twitter;
    }

    /**
     * @param $json
     * @param $content
     * @param $profileHTML
     */
    private function getFacebook($json, $content, $profileHTML)
    {
        $facebook = null;
        if (!empty($json['facebook_url'])) {
            $facebook = $json['facebook_url'];
        } elseif (preg_match('/class="facebook"/', $content)) {
            $profilehtmllfacebook = $profileHTML->find("a.facebook");
            if (count($profilehtmllfacebook) > 0) {
                $facebook = trim($profilehtmllfacebook[0]->href);
            }
        }

        return $facebook;

        if (!empty($facebook) && strripos($facebook, 'facebook') !== false) {
            $username = new Username($facebook);
            if (strpos($facebook, "/app_scoped_user_id/") !== false) {
                $facebook = $username->getProfileLink($facebook);
            }
            $facebook = (new URLNormalizer)->normalize($facebook);
            $username = $username->getUsername();

            $this->info['facebook'] = $username;
            $this->info['usernames'][] = $username;

            if (!$this->info['location']) {
                $htmlContent = ['body' => $content];
                $profileInfo = (new FacebookProfileInfo($this->curl))->getProfileInfo($facebook, $htmlContent);
                if (
                    isset($profileInfo['location']['livesin']) &&
                    $profileInfo['location']['livesin']
                ) {
                    $this->info['location'] = $profileInfo['location']['livesin'];
                } elseif (
                    isset($profileInfo['location']['hometown']) &&
                    $profileInfo['location']['hometown']
                ) {
                    $this->info['location'] = $profileInfo['location']['hometown'];
                }

                if (
                    isset($profileInfo['location']['hometown']) &&
                    $profileInfo['location']['hometown']
                ) {
                    $this->info['otherLocation'] = $profileInfo['location']['hometown'];
                }
            }

            return $facebook;
        }
    }
}
