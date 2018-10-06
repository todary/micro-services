<?php

namespace Skopenow\UrlInfo\UrlInfo;

use Skopenow\UrlInfo\Interfaces\ProfileImageInterface;

/**
 * Get The Profile Image from given profile URL
 */
class ProfileImage implements ProfileImageInterface
{
    /**
     * Profile URL
     * @var string
     */
    private $url;

    /**
     * Person ID
     * @var int
     */
    private $personID;

    /**
     * Combination ID
     * @var int
     */
    private $combinationID;

    /**
     * Options
     * @var array
     */
    private $options = [];

    /**
     * Contains HTML page as string
     * @var string
     */
    private $htmlContent;

    /**
     * URL of the profile Image
     * @var string
     */
    private $image;

    /**
     * ProfileImage constructor.
     *
     * @param string $url
     * @param CURL $curl
     */
    function __construct(string $url, CURL $curl)
    {
        $this->url = $url;
        $this->image = "";
        $this->curl = $curl;
        $this->personID = config('state.report_id');
        $this->combinationID = config('state.combination_id');
    }

    /**
     * Get The profile Image from given profile URL
     *
     * @return string
     */
    public function getProfileImage() : string
    {
        $host = parse_url($this->url, PHP_URL_HOST);

        if ($this->personID) {
            $this->options["person_id"] = $this->personID;
        }
        if ($this->combinationID) {
            $this->options["combination_id"] = $this->combinationID;
        }
        if (preg_match("#(\://|\.|)facebook.com#", $host)) {
            $this->image = $this->getFacebookImage();
        } else if (preg_match("#(\://|\.|)twitter.com#", $host)) {
            $this->image = $this->getTwitterImage();
        } else if (preg_match("#(\://|\.|)pinterest.com#", $host)) {
            $this->image = $this->getPinterestImage();
        } else if (preg_match("#(\://|\.|)instagram.com#", $host)) {
            $this->image = $this->getInstagramImage();
        } else if (preg_match("#(\://|\.|)youtube.com#", $host)) {
           $this->image = $this->getYoutubeImage();
        } else if (preg_match("#(\://|\.|)linkedin.com#", $host)) {
            $this->image = $this->getLinkedinImage();
        } else if (preg_match("#(\://|\.|)myspace.com#", $host)) {
            $this->image = $this->getMyspaceImage();
        } else if (preg_match("#(\://|\.|)github.com#", $host)) {
            $this->image = $this->getGithubImage();
        } else if (preg_match("#(\://|\.|)wikipedia.org#", $host)) {
           $this->image = $this->getWikipediaImage();
        } else if (preg_match("#(\://|\.|)angel.co#", $host)) {
           $this->image = $this->getAngelImage();
        } else if (preg_match("#(\://|\.|)foursquare.com#", $host)) {
           $this->image = $this->getFoursquareImage();
        } else if (preg_match("#(\://|\.|)flickr.com#", $host)) {
           $this->image = $this->getFlickrImage();
        } else if (preg_match("#(\://|\.|)producthunt.com#", $host)) {
            $this->image = $this->getProducthuntImage();
        } else if (preg_match("#(\://|\.|)quora.com#", $host)) {
            $this->image = $this->getQuoraImage();
        } else if (preg_match("#(\://|\.|)get.google.com#", $host)) {
            $this->image = $this->getGoogleImage();
        } else if (preg_match("#(\://|\.|)picasaweb.google.com#", $host)) {
            $this->image = $this->getPicasaImage();
        } else if (preg_match("#(\://|\.|)f6s.com#", $host)) {
           $this->image = $this->getF6sImage();
        }
        return $this->image;
    }

    /**
     * /**
     * Get The profile Image from given facebook profile URL
     *
     * @return string
     */
    private function getFacebookImage() : string
    {
        $options = $this->options;
        $sessionID = "automation_sessions_facebook";
        $request_url = $this->url;

        $request_url = (strpos($request_url, '?') !== false) ? (str_replace('?', '?__sid=' . $sessionID . '&', $request_url)) : ($request_url . '?__sid=' . $sessionID);

        $options['timeout'] = 30;

        $profile = $this->curl->curl_content($request_url, $options);
        if (isset($profile['error_no']) && $profile['error_no']) {
            return "";
        }

        $matchesCount = preg_match(
            '#<meta[^>]+property=["\']og:image["\'][^>]+content=["\'][^"\']+["\']#s',
            $profile["body"],
            $matches
        );

        if (!$matchesCount) {
            $matchesCount = preg_match(
                '#<img\s+class=[\'"][^\'"]*profilePic[^\'"]+[\'"][^>]+src=[\'"]([^\'"]+)[\'"]#s',
                $profile["body"],
                $matches
            );
        }
        if (!$matchesCount) {
            $matchesCount = preg_match(
                '#<meta[^>]+itemprop=[\'"]image[\'"][^>]+content=[\'"]([^\'"]+)[\'"]#s',
                $profile["body"],
                $matches
            );
        }

        if (!$matchesCount) {
            return "";
        }
        return html_entity_decode($matches[1]);
    }

    /**
     * Get The profile Image from given Twitter profile URL
     *
     * @return string
     */
    private function getTwitterImage() : string
    {
        $image = $this->image;
        $this->options['headers'] = [
                    "Connection: close",
                    "X-Requested-With:XMLHttpRequest",
                    "X-Push-State-Request:true"
            ];
        $this->options["nocookies"] = true;

        $curl = $this->curl->curl_content($this->url, $this->options);
        if (isset($curl["error_no"])) {
            return $image;
        }
        // $body = @json_decode($curl, true);
        $body = $curl;
        if (!is_array($body) || !isset($body["body"])) {
            return $image;
        }

        $content = $body["body"];
        if ($content) {
            preg_match("#<img[^>]*class\s*=\s*['\"]ProfileAvatar-image[^>]*src\s*=\s*['\"]([^'\"]+)#i",
                $content,
                $matches
            );

            if (!isset($matches[1])) {
                return "";
            }
            $image = $matches[1];
        }
        return $image;
    }

    /**
     * Get The profile Image from given Pinterest profile URL
     *
     * @return string
     */
    private function getPinterestImage() : string
    {
        $image = $this->image;
        $curl = $this->curl->curl_content($this->url, $this->options);
        $content = null;
        if (isset($curl['body']) && $curl['body']) {
            $content = $curl['body'];
        }
        $content = @substr($content, strpos($content, "<"));
        if ($content) {
            $pattern = '/class="_mj _25 _3x _2h"\s*src="([^"]+)"/';
            $image = $this->getImageFromHTML($content, $pattern);
        }
        return $image;
    }

    /**
     * Get The profile Image from given Instagram profile URL
     *
     * @return string
     */
    private function getInstagramImage() : string
    {
        $image = $this->image;
        $curl = $this->curl->curl_content($this->url, $this->options);
        $content = null;
        if (isset($curl['body']) && $curl['body']) {
            $content = $curl['body'];
        }
        $content = @substr($content, strpos($content, "<"));

        if ($content) {
            $pattern = '/<img class="_9bt3u"\s*src="([^"]+)"/';
            $image = $this->getImageFromHTML($content, $pattern);
        }
        return $image;
    }

    /**
     * Get The profile Image from given Youtube profile URL
     *
     * @return string
     */
    private function getYoutubeImage() : string
    {
        $image = $this->image;
        $curl = $this->curl->curl_content($this->url, $this->options);

        $content = null;
        if (isset($curl['body']) && $curl['body']) {
            $content = $curl['body'];
        }

        $content = @substr($content, strpos($content, "<"));

        if ($content) {
            $pattern = '/<img\s*class="channel-header-profile-image"\s*src="([^"]+)"/';
            $image = $this->getImageFromHTML($content, $pattern);
        }
        return $image;
    }

    /**
     * Get The profile Image from given LinkedIn profile URL
     *
     * @return string
     */
    private function getLinkedinImage() : string
    {
        $image = $this->image;
        $options = $this->options;
        $combinationID = $this->combinationID;
        if (empty($this->htmlContent)) {
            if($this->personID) {
                $options['person_id'] = $this->personID;
            }
            if($combinationID) {
                $options['combination_id'] = $combinationID;
            }
            $options['ignore_auto_select_ip'] = true;
            $request = $this->curl->curl_content($this->url, $options);
        } else {
            $request = $this->htmlContent;
        }
        preg_match(
            '/(?={&quot;data&quot;:{&quot;patentView[^*]+profileView)(.+?[^<]+)/i',
            $request['body'],
            $linkedProfileNewStyleEncoded
        );

        if (isset($curl['body']) && $curl['body']) {
            $request['body'] = $curl['body'];
        }
        preg_match(
            '/(?={&quot;data&quot;:{&quot;patentView[^*]+profileView)(.+?[^<]+)/i',
            $request['body'],
            $linkedProfileNewStyleEncoded
        );
        preg_match(
            '#profile-picture(.*)src\s*=\s*["\'](.+?)["\']#i',
            $request['body'],
            $matches
        );
        if ($matches && isset($matches[2])) {
            return $matches[2];
        }
        if (!empty($linkedProfileNewStyleEncoded[1])) {
            preg_match(
                '/croppedImage&quot;:&quot;(.*?)&quot;/i',
                $linkedProfileNewStyleEncoded[1],
                $matchImage
            );
            if (isset($matchImage[1]) && !empty($matchImage[1])) {
                $image = 'https://media.licdn.com/mpr/mpr/shrinknp_400_400' . $matchImage[1];
            } else {
                $image = 'https://static.licdn.com/scds/common/u/images/themes/katy/ghosts/person/ghost_person_100x100_v1.png';
            }
        }

        return $image;
    }

    /**
     * Get The profile Image from given MySpace profile URL
     * @return string
     */
    private function getMyspaceImage() : string
    {
        $image = $this->image;
        $curl = $this->curl->curl_content($this->url, $this->options);
        $content = null;
        if (isset($curl['body']) && $curl['body']) {
            $content = $curl['body'];
        }
        $content = @substr($content, strpos($content, "<"));
        if ($content) {
            $pattern = '/id="profileImage".*?\n*<img src="([^"]+)"/';
            $image = $this->getImageFromHTML($content, $pattern);
        }
        return $image;
    }

    /**
     * Get The profile Image from given GitHub profile URL
     * @return string
     */
    private function getGithubImage() : string
    {
        $image = $this->image;
        $curl = $this->curl->curl_content($this->url, $this->options);
        $content = null;

        if (isset($curl['body']) && $curl['body']) {
            $content = $curl['body'];
        }

        if ($content) {
            $pattern = '/\s*class="avatar\s*width-full\s*rounded-2"\s*height="230"\s*src="([^"]+)"/';
            $image = $this->getImageFromHTML($content, $pattern);
            $image = explode('?', $image)[0];
        }
        return $image;
    }

    /**
     * Get The profile Image from given WikiPedia profile URL
     * @return string
     */
    private function getWikipediaImage() : string
    {
        $image = $this->image;
        $curl = $this->curl->curl_content($this->url, $this->options);
        $content = null;
        if (isset($curl['body']) && $curl['body']) {
            $content = $curl['body'];
        }

        if ($content) {
            $pattern = '/<meta [^>]+?og:image[^>]+?content[^"]+?"([^"]+?)"/';
            if (preg_match($pattern, $content, $match)) {
                $image = $match[1];
                $image = str_replace("/1200px", "/220px", $image);
            }
        }
        return $image;
    }

    private function getAngelImage()
    {
        $image = $this->image;
        $curl = $this->curl->curl_content($this->url, $this->options);
        $content = null;
        if (isset($curl['body']) && $curl['body']) {
            $content = $curl['body'];
        }
        $content = @substr($content, strpos($content, "<"));
        if ($content) {
            $pattern = '/itemprop="image"\s*class="js-avatar-img"\s*src="([^"]+)"/';
            $image = $this->getImageFromHTML($content, $pattern);
        }
        return $image;
    }

    /**
     * Get The profile Image from given FourSquare profile URL
     * @return string
     */
    private function getFoursquareImage() : string
    {
        $image = $this->image;
        $curl = $this->curl->curl_content($this->url, $this->options);
        $content = null;
        if (isset($curl['body']) && $curl['body']) {
            $content = $curl['body'];
        }
        $content = @substr($content, strpos($content, "<"));
        if ($content) {

            preg_match("#src\s*=\s*['\"](.+?)['\"]#i", $content, $matches);
            if (isset($matches[1])) {
                $image = $matches[1];
            }
        }
        return $image;
    }

    /**
     * Get The profile Image from given Flickr profile URL
     * @return string
     */
    private function getFlickrImage() : string
    {
        $image = $this->image;
        $curl = $this->curl->curl_content($this->url, $this->options);

        $content = null;
        if (isset($curl['body']) && $curl['body']) {
            $content = $curl['body'];
        }

        $pattern = "/coverphoto.*?avatar.*?background-image.*?url\\(\\/\\/(.*?)\\)/s";
        if (preg_match($pattern, $content, $match))
        {
            return $match[1];
        }
        return $image;
    }

    /**
     * Get The profile Image from given ProductHunt profile URL
     * @return string
     */
    private function getProducthuntImage() : string
    {
        $image = $this->image;
        $curl = $this->curl->curl_content($this->url, $this->options);
        $content = null;
        if (isset($curl['body']) && $curl['body']) {
            $content = $curl['body'];
        }
        $content = @substr($content, strpos($content, "<"));
        if ($content) {
            $pattern = "#src\s*=\s*['\"](.+?)['\"]#i";
            $image = $this->getImageFromHTML($content, $pattern);
        }
        return $image;
    }

    /**
     * Get The profile Image from given Quora profile URL
     * @return string
     */
    private function getQuoraImage() : string
    {
        $image = $this->image;
        $curl = $this->curl->curl_content($this->url, $this->options);
        if (isset($curl["error_no"])) {
            return "";
        }
        $content = $curl["body"];
        if ($content) {
            $re = "/<img class=\"profile_photo_img\" src=\"([^\"]+)\"/i";
            preg_match($re, $content, $matches);
            if (!isset($matches[1])) {
                return "";
            }
            $image = $matches[1];
        }
        return $image;
    }

    /**
     * Get The profile Image from given Google profile URL
     * @return string
     */
    private function getGoogleImage() : string
    {
        $image = $this->image;
        $curl = $this->curl->curl_content($this->url, $this->options);
        $content = null;
        if (isset($curl['body']) && $curl['body']) {
            $content = $curl['body'];
        }

        if ($content) {
            $pattern = "/fNtDS vCjazd.*?url\\(([^)]+)/";
            preg_match($pattern, $content, $matches);

            if (!isset($matches[1])) {
                return "";
            }
            $image = $matches[1];
        }
        return $image;
    }

    /**
     * Get The profile Image from given Picasa profile URL
     * @return string
     */
    private function getPicasaImage() : string
    {
        $image = $this->image;
        $curl = $this->curl->curl_content($this->url, $this->options);
        $content = null;
        if (isset($curl['body']) && $curl['body']) {
            $content = $curl['body'];
        }
        if ($content) {
            $pattern = "/fNtDS vCjazd.*?url\\(([^)]+)/";
            preg_match($pattern, $content, $matches);
            if (!isset($matches[1])) {
                return "";
            }
            $image = $matches[1];
        }
        return $image;
    }

    /**
     * Get The profile Image from given F6S profile URL
     * @return string
     */
    private function getF6sImage() : string
    {
        $image = $this->image;
        $curl = $this->curl->curl_content($this->url, $this->options);
        $content = null;
        if (isset($curl['body']) && $curl['body']) {
            $content = $curl['body'];
        }
        if ($content) {
            $pattern = '/<div\s*class="profile-picture\s*rounded">\s*<img\s*src="([^"]+)"/';
            $image = $this->getImageFromHTML($content, $pattern);
        }
        return $image;
    }

    /**
     * Get Image from given HTMl page
     * @param string $html
     * @param string $pattern
     *
     * @return string
     */
    private function getImageFromHTML(string $html, string $pattern) : string
    {
        preg_match($pattern, $html, $matches);
        if (isset($matches[1])) {
            return $matches[1];
        }
        return "";
    }

    /**
     * @param mixed $personID
     */
    public function setPersonID($personID)
    {
        $this->personID = $personID;

    }

    /**
     * @param mixed $combinationID
     */
    public function setCombinationID($combinationID)
    {
        $this->combinationID = $combinationID;
    }

    /**
     * @param array $htmlContent
     */
    public function setHTMLContent(array $htmlContent)
    {
        $this->htmlContent = $htmlContent;
    }
}
