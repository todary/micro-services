<?php

namespace Skopenow\UrlInfo\UrlInfo;

use Skopenow\UrlInfo\Interfaces\UsernameInterface;

class Username implements UsernameInterface
{
    /**
     * A CONSTANT array of SITENAME => pattern
     *
     * @var constant array
     */
    const SITE_NAME_PATTERN = [
        "PLUS.GOOGLE" => "#plus\.google\.com\/([+]?\w+)#i",
        "TWITTER" => "#twitter\.com\/((\w|\d|[\.\-_])+)[^\/\&]*$#i",
        "FACEBOOK" => "#facebook\.com\/((\w|\d|[\.\-_])+)[^\/\&]*$#i",
        "PINTEREST" => "#pinterest\.com\/((\w|\d|[\.\-_])+)[^\/\&]*$#i",
        "INSTAGRAM" => "#instagram\.com\/((\w|\d|[\.\-_])+)[^\/\&]*$#i",
        "MYSPACE" => "#myspace\.com\/((\w|\d|[\.\-_])+)[^\/\&]*#i",
        "YOUTUBE" => "#youtube\.com\/(user|channel)\/((\w|\d|[\.\-_])+)[^\/\&]*$#i",
        "ANGEL" => "#angel\.co\/((\w|\d|[\.\-_])+)[^\/\&]*$#i",
        "SLIDESHARE" => "#slideshare\.net\/((\w|\d|[\.\-_])+)[^\/\&]*$#i",
        "GITHUB" => "#github\.com\/((\w|\d|[\.\-_])+)[^\/\&]*$#i",
        "TWITCH" => "#twitch\.tv\/((\w|\d|[\.\-_])+)[^\/\&]*\/profile$#i",
        "EBAY" => "#ebay\.com\/usr\/((\w|\d|[\.\-_])+)[^\/\&]*$#i",
        "VINE" => "#vine.co\/(u\/)?((\w|\d|[\.\-_])+)[^\/\&]*$#i",
        "FLICKR" => "#flickr\.com\/people\/((@|\w|\d|[\.\-_])+)[^\/\&]*$#i",
        "PANDORA" => "#pandora\.com\/profile\/((\w|\d|[\.\-_])+)[^\/\&]*$#i",
        "RDIO" => "#rdio\.com\/pepole\/((\w|\d|[\.\-_])+)[^\/\&]*$#i",
        "PRODUCTHUNT" => "#producthunt\.com\/\@((\w|\d|[\.\-_])+)[^\/\&]*$#i",
        "STEAMCOMMUNITY" => "#steamcommunity\.com\/id\/((\w|\d|[\.\-_])+)[^\/\&]*$#i",
        "FLIPBOARD" => "#flipboard\.com\/\@((\w|\d|[\.\-_])+)[^\/\&]*$#i",
        "OKCUPID" => "#okcupid\.com\/profile\/((\w|\d|[\.\-_])+)[^\/\&]*$#i",
        "VIMEO" => "#vimeo\.com\/((\w|\d|[\.\-_])+)[^\/\&]*$#i",
        "ETSY" => "#etsy\.com\/people\/((\w|\d|[\.\-_])+)[^\/\&]*$#i",
        "SOUNDCLOUD" => "#soundcloud\.com\/((\w|\d|[\.\-_])+)[^\/\&]*$#i",
        "TUMBLR" => "#((\w|\d|[\.\-_])+)[^\/\&]tumblr\.com\/*$#i",
        "SCRIBD" => "#scribd\.com\/((\w|\d|[\.\-_])+)[^\/\&]*$#i",
        "DAILYMOTION" => "#dailymotion\.com\/((\w|\d|[\.\-_])+)[^\/\&]*$#i",
        "ABOUT" => "#about\.me\/((\w|\d|[\.\-_])+)[^\/\&]*$#i",
        "DISQUS" => "#disqus\.com\/((\w|\d|[\.\-_])+)[^\/\&]*$#i",
        "MEDIUM" => "#medium\.com\/\@((\w|\d|[\.\-_])+)[^\/\&]*$#i",
        "BEHANCE" => "#behance\.net\/((\w|\d|[\.\-_])+)[^\/\&]*$#i",
        "PHOTOBUCKET" => "#photobucket\.com\/user\/((\w|\d|[\.\-_])+)[^\/\&]*\/profile$#i",
        "KIK" => "#kik\.com\/u\/((\w|\d|[\.\-_])+)[^\/\&]*$#i",
        "IMGUR" => "#imgur\.com\/user\/((\w|\d|[\.\-_])+)[^\/\&]*$#i",
        "BITLY" => "#bitly\.com\/u\/((\w|\d|[\.\-_])+)[^\/\&]*$#i",
        "INSTRUCTABLES" => "#instructables\.com\/member\/((\w|\d|[\.\-_])+)[^\/\&]*$#i",
        "GRAVATAR" => "#en\.gravatar\.com\/((\w|\d|[\.\-_])+)[^\/\&]*$#i",
        "KEYBASE" => "#keybase\.io\/((\w|\d|[\.\-_])+)[^\/\&]*$#i",
        "KONGREGATE" => "#kongregate\.com\/accounts\/((\w|\d|[\.\-_])+)[^\/\&]*$#i",
        "STUMBLEUPON" => "#stumbleupon\.com\/stumbler\/((\w|\d|[\.\-_])+)[^\/\&]*$#i",
        "DEVIANTART" => "#((\w|\d|[\.\-_])+)[^\/\&]deviantart\.com\/*$#i",
        "8TRACKS" => "#8tracks\.com\/((\w|\d|[\.\-_])+)[^\/\&]*$#i",
        "9GAG" => "#9gag\.com\/u\/((\w|\d|[\.\-_])+)[^\/\&]*$#i",
        "500PX" => "#500px\.com\/((\w|\d|[\.\-_])+)[^\/\&]*$#i",
        "DELICIOUS" => "#delicious\.com\/((\w|\d|[\.\-_])+)[^\/\&]*$#i",
        "DRIBBBLE" => "#dribbble\.com\/((\w|\d|[\.\-_])+)[^\/\&]*$#i",
        "DRUPAL" => "#drupal\.com\/u\/((\w|\d|[\.\-_])+)[^\/\&]*$#i",
        "FIVERR" => "#fiverr\.com\/((\w|\d|[\.\-_])+)[^\/\&]*$#i",
        "LAST" => "#last\.fm\/(user)\/((\w|\d|[\.\-_])+)[^\/\&]*$#i",
        "LINKEDIN" => "#linkedin\.com\/in\/((\w|\d|[\.\-_])+)[^\/\&]*$#i",
        "METACAFE" => "#metacafe\.com\/channels\/((\w|\d|[\.\-_])+)[^\/\&]*$#i",
        "PATH" => "#path\.com\/i\/((\w|\d|[\.\-_])+)[^\/\&]*$#i",
        "PICSART" => "#((\w|\d|[\.\-_])+)[^\/\&]picsart\.com\/*$#i",
        "QUORA" => "/quora\\.com\\/profile\\/((\\w|\\d|[\\.\\-_])+)[^\\/\\&\\#]*/i",
        "REDDIT" => "#reddit\.com\/user\/((\w|\d|[\.\-_])+)[^\/\&]*$#i",
        "SQUARESPACE" => "#((\w|\d|[\.\-_])+)[^\/\&]squarespace\.com\/*$#i",
        "TRIPADVISOR" => "#tripadvisor\.com\/member\/((\w|\d|[\.\-_])+)[^\/\&]*$#i",
        "TUNEIN" => "#tunein\.com\/user\/((\w|\d|[\.\-_])+)[^\/\&]*$#i",
        "WIKIPEDIA" => "#wikipedia\.org\/wiki\/((\w|\d|[\.\-_])+)[^\/\&]*$#i",
        "WIRED" => "#insights\.wired\.com\/profile\/((\w|\d|[\.\-_])+)[^\/\&]*$#i",
        "WORDPRESS" => "#profiles\.wordpress\.org\/((\w|\d|[\.\-_])+)[^\/\&]*$#i",
        "GET.GOOGLE" => "/get.google.com\\/albumarchive\\/(\\w+)/",
        "PICASA" => "#picasaweb\.google\.com\/((\w|\d|[\.\-_])+)[^\/\&]*$#i",
        "F6S" => "#f6s\.com\/((\w|\d|[\.\-_])+)[^\/\&]*$#i",
    ];

    /**
     * @var string
     */
    private $url;

    /**
     * @var int
     */
    private $personID;

    /**
     * @var string
    */
    private $username;

    private $skipRequests;

    /**
     * Username constructor
     * @param string $url
     */
    public function __construct($url, bool $skipRequests = false)
    {
        $this->url = $url;
        $this->username = "";
        $this->curl = new CURL;
        $this->skipRequests = $skipRequests;
    }

    /**
     * Get the Username from the given URL
     * @return string
     */
    public function getUsername() : string
    {
        $this->url = (new URLNormalizer($this->skipRequests))->normalize($this->url);

        $siteName = $this->getSiteName($this->url);
        $this->usernameLower = "";
        $this->url = rtrim($this->url, "/");
        if ($siteName == "TWITTER") {
            $this->username = $this->getTwitterUsername($this->url);
        } elseif ($siteName == "FACEBOOK") {
            $this->username = $this->getFacebookUsername($this->url);
        } elseif ($siteName == "LINKEDIN") {
            $this->username = $this->getLinkedinUsername($this->url);
        } elseif (strpos($this->url, 'foursquare')) {
            $this->username = $this->getFoursquareUsername($this->url);
        } elseif (array_key_exists($siteName, self::SITE_NAME_PATTERN)) {
            $this->username = $this->usernameLookUp($siteName, self::SITE_NAME_PATTERN[$siteName]);
        }

        $this->username = trim($this->username);
        if (strlen($this->username) < 3) $this->username = "";

        if (preg_match("#\Wsearch(\W|\$)#i", $this->username) == 0 &&
            preg_match("#\Wprofile(\W|\$)#i", $this->username) == 0 &&
            preg_match("#\.(php|asp|cgi)#i", $this->username) == 0 &&
            preg_match("#\Wpermaurl(\W|\$)#i", $this->username) == 0
        ) {
            $usernameLower = $this->username;
        }

        $usernameLower = strtolower($this->username);


        return $usernameLower;
    }

    /**
     * Check if the sitename in CONST SITE_NAME_PATTERN
     * and match the URL with the pattern and get the username
     * @param string $siteName
     * @param string $pattern
     * @return string username
     */
    private function usernameLookUp(string $siteName, string $pattern) : string
    {
        $match = 1;
        if (in_array($siteName, ['LAST', 'VINE', 'YOUTUBE'])) {
            $match = 2;
        }
        preg_match($pattern, $this->url, $matches);
        if (isset($matches[$match])) {
            return $matches[$match];
        }
        return "";
    }

    /**
     * Get the facebook username from given URL
     * @param  string $url
     *
     * @return string
     */
    private function getFacebookUsername(string $url) : string
    {
        $username = "";
        if (!$this->skipRequests && strpos($url, "/app_scoped_user_id/") !== false) {
            $url = $this->getProfileLink($url, $this->personID);
        }

        if (strpos($url, "/people/") === false) {

            $pattern = "#facebook\.com\/((\w|\d|[\.\-_\?\=])+)[^\/\&]*$#i";

            preg_match($pattern, $url, $matches);
            if (isset($matches[1])) {
                $username = $matches[1];
            }

            if (strpos($url, "profile.php") !== false) {
                if (preg_match("#profile\.php\?__sid=automation_sessions_facebook&id\=(.+)#i", $url, $matches) ||
                    preg_match("#profile\.php\?id\=(.+)#i", $url, $matches)
                ) {
                    $info = @file_get_contents("http://graph.facebook.com/" . $matches[1]);
                    if ($info) {
                        $info = json_decode($info, true);
                        if (isset($info["username"])) {
                            return $info['username'];
                        }
                        return $matches[1];
                    }
                    return $matches[1];
                }
            }
        } else {
            $pattern = "#facebook\.com\/people\/[^\/\&\?\=]+\/((\d)+)[^\w\/\&]*$#i";
            preg_match($pattern, $url, $prof);
            if (isset($prof[1])) {
                $info = @file_get_contents("http://graph.facebook.com/" . $prof[1]);
                if ($info) {
                    $info = json_decode($info, true);
                    if (isset($info["username"])) {
                        return $info['username'];
                    }
                    return $prof[1];
                }
                return $prof[1];
            }
        }

        if (strpos($this->username, "?") !== false) {
            $tmz = explode("?", $username);
            return $tmz[0];
        }
        return $username;
    }

    /**
     * Get the Twitter username from given URL
     * @param  string $url
     *
     * @return string
     */
    private function getTwitterUsername($url) : string
    {
        if (strpos($url, "/status/") === false) {
            preg_match("#twitter\.com\/((\w|\d|[\.\-_])+)[^\/\&]*$#i", $url, $matches);
            if (isset($matches[1])) {
                return str_replace("@", "", $matches[1]);
            }
        }
        return "";
    }

    /**
     * Get the LinkedIn username from given URL
     * @param  string $url
     *
     * @return string
     */
    private function getLinkedinUsername($url)
    {
        $username = "";
        if (strpos($url, "profile/view") === false && strpos($url, "pub/dir/") === false) {
            $pattern = "#linkedin\.com\/in\/((\w|\d|[\.\-_\?\=])+)[^\/\&]*$#i";
            preg_match($pattern, $url, $matches);
            if (isset($matches[1])) {
                $username = $matches[1];
            } else {
                $pattern = "#linkedin\.com\/pub\/((\w|\d|[\.\-_\?\=\/])+)[^\/\&]*#i";
                preg_match($pattern, $url, $matches);
                if (isset($matches[1])) {
                    $username = $matches[1];
                }
            }
        } elseif (strpos($url, "profile/view") !== false) {
            preg_match("#[\&\?]id=(\d+)#i", $url, $matches);
            if (isset($matches[1])) {
                $username = $matches[1];
            }
        }
        return $username;
    }

    /**
     * Get the Foursquare username from given URL
     * @param  string $url
     *
     * @return string
     */
    private function getFoursquareUsername($url)
    {
        $pattern = "/foursquare\.com\/user\/(\d+)$/";
        if (preg_match($pattern, $url, $matches)) {
            return $matches[1];
        }
        $pattern = '/foursquare\.com\/([^\/]+)$/';

        if (preg_match($pattern, $url, $matches)) {
            return $matches[1];
        }

        return "";
    }

    /**
     * Get the site name from given URL
     * @param string $url
     *
     * @return string UPPERCASE
     */
    private function getSiteName(string $url) : string
    {
        if (
            strpos($url, "http://") === false &&
            strpos($url, "https://") === false &&
            filter_var("http://" . $url, FILTER_VALIDATE_URL) != FALSE
        ) {
            $url = "http://" .$url;
        }
        $sitename = '';
        if ($url && $url != 'undefined') {
            $parts = parse_url($url);
            if (isset($parts['host'])) {
                $urlexp = explode('.', $parts['host']);
                unset($urlexp[count($urlexp) - 1]);
                if (strpos($parts['host'], 'www.') !== false) {
                    unset($urlexp[0]);
                }
                $sitename = implode('.', $urlexp);
            }
        }

        if (strpos($sitename, "facebook") !== false) {
            $sitename = "facebook";
        }elseif (strpos($sitename, "deviantart") !== false) {
            $sitename = "deviantart";
        }elseif (strpos($sitename, "tumblr") !== false) {
            $sitename = "tumblr";
        }elseif (strpos($sitename, "squarespace") !== false) {
            $sitename = "squarespace";
        }elseif (strpos($sitename, "picsart") !== false) {
            $sitename = "picsart";
        }elseif (strpos($sitename, "gravatar") !== false) {
            $sitename = "gravatar";
        }elseif (strpos($sitename, "picasaweb") !== false) {
            $sitename = "picasa";
        }elseif (strpos($sitename, "wordpress") !== false) {
            $sitename = "wordpress";
        }elseif (strpos($sitename, "wired") !== false) {
            $sitename = "wired";
        }elseif (strpos($sitename, "photobucket") !== false) {
            $sitename = "photobucket";
        }elseif (strpos($sitename, "api.twitch") !== false) {
            $sitename = "twitch";
        }elseif (stripos($sitename, "en.") === 0) {
            $sitename = substr($sitename, 3);
        }
        return strtoupper($sitename);
    }

    /**
     * @param mixed $personID
     *
     * @return self
     */
    public function setPersonID($personID)
    {
        $this->personID = $personID;

        return $this;
    }

    /**
     * Get the Facebook Profile URL
     * @param string   $url
     * @param int|null $personID
     *
     * @return string
     */
    public function getProfileLink(string $url, int $personID = null) : string
    {
        //$sess_id = "automation_sessions_facebook";

        //$url = (strpos($url, '?') !== false) ? (str_replace('?', '?&__sid=' . $sess_id . '&', $url)) : ($url . '?__sid=' . $sess_id);
        $options = [];
        $options['allow_redirects'] = false;
        //$options['ignore_auto_select_ip'] = true;

        try {
            $entry = loadService('HttpRequestsService');
            $response = $entry->fetch($url, 'GET', $options);
            $location = $response->getHeader('location');
            if ($location) {
                $url = reset($location);
            }
        } catch (\Exception $ex) {
            return $url;
        }

        $url = rtrim($url, "?");
        $url = rtrim($url, "&");
        $url = rtrim($url, "?");
        $url = str_replace("?&", "?", $url);

        return $url;
    }
}
