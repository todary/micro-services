<?php
namespace Skopenow\UrlInfo\UrlInfo;

use Skopenow\UrlInfo\Interfaces\NormalizerInterface;

/**
 * Normalize given URL
 */
class URLNormalizer implements NormalizerInterface
{
    private $personID;
    private $skipRequests;

    public function __construct(bool $skipRequests = false)
    {
        $this->personID = config('state.report_id');
        $this->skipRequests = $skipRequests;
    }
    /**
     * Normalize the url
     *
     * @param string $url
     * @return string
     */
    public function normalize(string $url) : string
    {
        $host = parse_url($url, PHP_URL_HOST);
        $url = preg_replace("/^.+?\.fb\.me\//", "https://www.facebook.com/", $url);
        $url = preg_replace('/(^fb\.me\/)/', "https://www.facebook.com/", $url);
        $url = preg_replace("/^.+?\.fb\.me\//", "https://www.facebook.com/", $url);
        $url = preg_replace('/(^fb\.com\/)/', "https://www.facebook.com/", $url);
        $url = preg_replace('/flickr\.com\/(photos)\//', "flickr.com/people/", $url);

        if (preg_match("#(\://|\.|)facebook.com#", $host, $matches)) {
            $url = $this->normalizeFacebook($url);
        } elseif (
            preg_match("#(\://|\.|)pinterest.com#", $host) &&
            preg_match("#pinterest\.com\/(pin\/)?[^\/]+\/?$#", $url)
        ) {
            $url = $this->normalizePinterest($url);
        } elseif (preg_match("#(\://|\.|)github.com#", $host)) {
            $url = $this->normalizeGithub($url);
        } elseif (preg_match("/picasaweb.google.com\\/([^\\?]*)/i", $url, $matches)) {
            $url = $this->normalizePicasa($url, $matches);
        } elseif (preg_match(
            "#(.*?linkedin\.com\/)pub\/([\w\-]+)\/(\w+)\/(\w+)\/(\w+)*#i",
            $url,
            $matches
            )
        ){
            $url = "https://www.linkedin.com/in/".$matches[2] . '-' . $matches[5] . $matches[4];

            if (!empty($matches[3])) {
                $url .= $matches[3];
            }
            $url =  preg_replace("#(\-|\w|\d)+\.linkedin#i", "www.linkedin", $url);

        } elseif (preg_match(
            "/\/linkedin\.com\/profile\/view.?id=([^\\&]*)/i",
            $url,
            $matches
            )
        ){

            if(!empty($matches[1])) {
                $url = 'https://www.linkedin.com/profile/view?id=' . $matches[1];
            }


        } elseif (preg_match("/:\\/\\/[^\\/]*?slideshare\\.[^\\/]+?\\/(.*)/i", $url, $matches)) {
            $url = $this->normalizeSlideshare($url, $matches);
        } elseif (preg_match("#(\://|\.|)linkedin.com/(.+)#", $url, $matches)) {
            $url = $this->normalizeLinkedin($url, $matches);
        } elseif (preg_match("/[^\/]+(\\:\/\/|\\.|)youtube[^\/]+\/watch\/([^\/=]+)/", $url, $matches)) {
            $url = $this->normalizeYoutube($url, $matches);
        } elseif (preg_match("#(\://|\.|)stumbleupon.com#", $host)) {
            $url = $this->normalizeStumbleupon($url);
        } elseif (preg_match("#(\://|\.|)instagram.com#", $host)) {
            $url = $this->normalizeInstagram($url);
        } elseif (
            preg_match("/twitter\.com\/[\w-\.]+/i", $url, $matches) ||
            preg_match("!twitter.com/@!", $url)
        ) {
            $url = $this->normalizeTwitter($url, $matches);
        } elseif (preg_match("/quora\\.com\\/profile\\/((\\w|\\d|[\\.\\-_])+)[^\\/\\&\\#]*/i", $url, $matches)) {
            $url = $this->normalizeQuora($url, $matches);
        }

        if (stripos($url, ".com/#!/")) {
            $url = str_ireplace(".com/#!/", ".com/", $url);
        }

        return $url;
    }

    /**
     * Normalize a Facebook profile link
     * @param string $url
     * @return string
     */
    private function normalizeFacebook(string $url) : string
    {
        $url = preg_replace("#^.+?\\.facebook\.[^\\/]+\\/#", "https://www.facebook.com/", $url);
        $re = '/\/people\/_\/(\d+)$/';
        preg_match($re, $url, $matches);

        if (empty($matches[1])) {
            $re = '/\/(\d+)/';
            preg_match($re, $url, $matches);
        }

        if (! empty($matches[1])) {
            // $url = 'https://www.facebook.com/profile.php?id=' . $matches[1];
            $url = 'https://www.facebook.com/' . $matches[1];
        }
        if (strpos($url, 'profile.php')) {
            $url = $this->hasProfileID($url);
        } elseif (strpos($url, 'search.php') || stripos($url, "public?query")){

        } elseif (strpos($url, 'photo.php') !== false) {
            if(strpos($url, '&') !== false) {
                $url = strstr($url, '&', true);
            }
        } elseif (
            strpos($url,'timeline/story') !== false ||
            strpos($url, 'permalink.php') !== false ||
            stripos($url, 'ajax/typeahead/profile_browser/friends/query.php') !== false
        ) {
            // do nothing
        } elseif (preg_match("#\\/people\\/[^\\/]*\\/(\\d+)(\\W|$)#i", $url, $match)){

            // This pice of code to fixing problem duplicated profile facebook
            // because the url is deffrient
            // ex : Convert https://www.facebook.com/people/Nicholas-Woodhams/12101952
            // To : https://www.facebook.com/12101952
            if(!empty($match[1])) {
                $url = 'https://www.facebook.com/' . $match[1];
            }
        } else {
            $url = explode('?', $url);
            $url = $url[0];
        }

        if (
            strpos($url, "/app_scoped_user_id//") !== false ||
            preg_match('#facebook\.com\/profile\.php#', $url) ||
            preg_match('#facebook\.com\/\d#', $url)
        ) {
            $url = preg_replace("#^.+?\\.facebook\.[^\\/]+\\/#", "https://www.facebook.com/", $url);
            if (!$this->skipRequests) {
                $url = (new Username($url))->getProfileLink($url, $this->personID);
            }
        }

        return $url;
    }

    /**
     * check if the facebook profile link has a profile ID
     * and return the new URL
     * @param string $url
     *
     * @return string
     */
    private function hasProfileID(string $url) : string
    {
        $exploded_url = explode('?', $url);

        if (isset($exploded_url[1])) {
            parse_str($exploded_url[1], $queryStrings);
            $url = '';
            if (array_key_exists('id', $queryStrings)) {
                $url = $exploded_url[0] . '?id='. $queryStrings['id'];
                $url = array_key_exists('sk', $queryStrings) ? $url . "&sk=" . $queryStrings['sk'] : $url;
            }
        }
        return $url;
    }

    /**
     * Normalize Pinterest Profile URL
     * @param string $url
     * @return string
     */
    private function normalizePinterest(string $url) : string
    {
        return preg_replace(
            "#^.+?pinterest\.[^\\/]+/#",
            "https://www.pinterest.com/",
            $url
        );
    }

    /**
     * Normalize GitHub URL
     * @param string $url
     * @return string
     */
    private function normalizeGithub(string $url) : string
    {
        preg_match("/github\.com\/([^\/?]+)/i", $url, $matches);
        if(!empty($matches[1])) {
            $url = "https://github.com/" . $matches[1];
        }
        return preg_replace('#\?.*#', '', $url);
    }

    /**
     * Normalize Picasa URL
     * @param string $url
     * @param array $matches
     *
     * @return string
     */
    private function normalizePicasa(string $url, array $matches) : string
    {
        if(!empty($matches[1])) {
            $url = 'http://picasaweb.google.com/' . $matches[1];
        }
        return $url;
    }

    /**
     * Normalize LinkedIn URL
     * @param string $url
     * @param array $matches
     *
     * @return string
     */
    /*private function normalizeLinkedin(string $url, array $matches) : string
    {
        if (preg_match("/\/linkedin\.com\/profile\/view.?id=([^\\&]*)/i", $url, $matches)) {
            if(!empty($matches[1])) {
                $url = 'http://linkedin.com/profile/view?id='.$matches[1];
            }
            return $url;
        }

        $url = $matches[1] . "in/".$matches[2] . '-' . $matches[5] . $matches[4];
        if ($matches[3] != 0) {
            $url .= $matches[3];
        }
        return preg_replace("#(\-|\w|\d)+\.linkedin#i", "www.linkedin", $url);
    }
    */

    /**
     * Normalize Slideshare URL
     * @param string $url
     * @param array $matches
     *
     * @return string
     */
    private function normalizeSlideshare(string $url, array $matches) : string
    {
        return "http://www.slideshare.net/" . $matches[1];
    }

    /**
     * Normalize Youtube URL
     * @param string $url
     * @param array $matches
     *
     * @return string
     */
    private function normalizeYoutube(string $url, array $matches) : string
    {
        return "https://www.youtube.com/watch?v=" . $matches[2];
    }

    /**
     * Normalize Stumbleupon URL
     * @param string $url
     * @return string
     */
    private function normalizeStumbleupon(string $url) : string
    {
        return explode("?",$url)[0];
    }

    /**
     * Normalize Instagram URL
     * @param string $url
     * @return string
     */
    private function normalizeInstagram(string $url) : string
    {
        if (strpos($url, 'preprod') !== false) {
            $url = str_replace('preprod', 'www', $url);
        }
        $urlParts = explode("?", $url);
        return rtrim($urlParts[0],'/');
    }

    /**
     * Normalize Twitter URL
     * @param string $url
     * @param array $matches
     *
     * @return string
     */
    private function normalizeTwitter(string $url, array $matches) : string
    {
        $url = preg_replace('!@!', '', $url);
        if (isset($matches[0])) {
            if (!strpos($url, "/status") && !strpos($url, "/search")) {
                $url = "http://" . $matches[0];
            }
        }
        return $url;
    }

    /**
     * Normalize Quora URL
     * @param string $url
     * @param array $matches
     *
     * @return string
     */
    private function normalizeQuora(string $url, array $matches) : string
    {
        if (!empty($matches[1])) {
            $url = "http://quora.com/profile/".$matches[1];
        }
        return $url;
    }

    /**
     * Normalize Linkedin URL
     * @param string $url
     * @param array $matches
     *
     * @return string
     */
    private function normalizeLinkedin(string $url, array $matches) : string
    {
        if (!empty($matches[1])) {
            $url = "https://www.linkedin.com/".$matches[2];
        }
        return $url;
    }
}
