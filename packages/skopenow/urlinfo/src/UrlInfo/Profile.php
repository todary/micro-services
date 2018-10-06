<?php

namespace Skopenow\UrlInfo\UrlInfo;

use Skopenow\UrlInfo\Interfaces\ProfileInterface;

/**
* Check if the profile exists in social sites
* and check if the URL represents a profile page
*/
class Profile implements ProfileInterface
{
    /**
     * CONSTANT Array of Host Pattern => URL Pattern
     */
    const HOST_URL_PATTERNS = [
        "#(\://|\.|)myspace.com#" => "#myspace\.com\/((\w|\d|[\.\-_])+)[^\/\&]*$#i",
        "#(\://|\.|)youtube.com#" => "#youtube\.com\/(user|channel)\/((\w|\d|[\.\-_])+)[^\/\&]*$#i",
        "#(\://|\.|)angel.co#" => '#angel\.co\/((\w|\d|[\.\-_])+)[^\/\&]*$#i',
        "#(\://|\.|)slideshare.(net|com)#" => "#slideshare\.(net|com)\/((\w|\d|[\.\-_])+)[^\/\&]*$#i",
        "#(\://|\.|)github.com#" => "#github\.com\/((\w|\d|[\.\-_])+)[^\/\&?]*$#i",
        // new twitch profile has not /profile anymore
        "#(\://|\.|)twitch.tv#" =>   "#twitch\.tv\/((\w|\d|[\.\-_])+)[^\/\&]*$#i",
        "#(\://|\.|)ebay.com#" => "#ebay\.com\/(usr)\/((\w|\d|[\.\-_])+)[^\/\&]*$#i",
        "#(\://|\.|)vine.co#" => "#vine.co\/(u\/)?((\w|\d|[\.\-_])+)[^\/\&]*$#i",
        "#(\://|\.|)flickr.com#" => "#flickr\.com\/(photos)\/((\w|\d|[\.\-_])+)[^\/\&]*$#i",
        "#(\://|\.|)pandora.com#" => "#pandora\.com\/(profile)\/((\w|\d|[\.\-_])+)[^\/\&]*$#i",
        // rdio is  no more exists
        // "#(\://|\.|)rdio.com#" => "#rdio\.com\/(pepole)\/((\w|\d|[\.\-_])+)[^\/\&]*$#i",
        "#(\://|\.|)producthunt.com#" => "#producthunt\.com\/\@((\w|\d|[\.\-_])+)[^\/\&]*$#i",
        "#(\://|\.|)steamcommunity.com#" => "#steamcommunity\.com\/(id)\/((\w|\d|[\.\-_])+)[^\/\&]*$#i",
        "#(\://|\.|)flipboard.com#" => "#flipboard\.com\/\@((\w|\d|[\.\-_])+)[^\/\&]*$#i",
        // "#(\://|\.|)foursquare.com#" => "#foursquare\.com\/((\w|\d|[\.\-_])+)[^\/\&]*$#i",
        "#(\://|\.|)okcupid.com#" => "#okcupid\.com\/(profile)\/((\w|\d|[\.\-_])+)[^\/\&]*$#i",
        "#(\://|\.|)vimeo.com#" => "#vimeo\.com\/([^\d](\w|\d|[\.\-_])+)[^\/\&]*$#i",
        "#(\://|\.|)etsy.com#" => "#etsy\.com\/(people)\/((\w|\d|[\.\-_])+)[^\/\&]*$#i",
        "#(\://|\.|)soundcloud.com#" => "#soundcloud\.com\/((\w|\d|[\.\-_])+)[^\/\&]*$#i",
        "#(\://|\.|)tumblr.com#" => "#((\w|\d|[\.\-_])+)[^\/\&]tumblr\.com\/*$#i",
        "#(\://|\.|)scribd.com#" => "#scribd\.com\/((\w|\d|[\.\-_])+)[^\/\&]*$#i",
        "#(\://|\.|)dailymotion.com#" => "#dailymotion\.com\/((\w|\d|[\.\-_])+)[^\/\&]*$#i",
        "#(\://|\.|)about.me#" => "#about\.me\/((\w|\d|[\.\-_])+)[^\/\&]*$#i",
        "#(\://|\.|)disqus.com#" => "#disqus\.com\/((\w|\d|[\.\-_])+)[^\/\&]*$#i",
        "#(\://|\.|)medium.com#" => "#medium\.com\/\@((\w|\d|[\.\-_])+)[^\/\&]*$#i",
        "#(\://|\.|)behance.net#" => "#behance\.net\/((\w|\d|[\.\-_])+)[^\/\&]*$#i",
        "#(\://|\.|)photobucket.com#" => "#photobucket\.com\/(user)\/((\w|\d|[\.\-_])+)[^\/\&]*\/profile$#i",
        "#(\://|\.|)kik.com#" => "#kik\.com\/(u)\/((\w|\d|[\.\-_])+)[^\/\&]*$#i",
        "#(\://|\.|)imgur.com#" => "#imgur\.com\/(user)\/((\w|\d|[\.\-_])+)[^\/\&]*$#i",
        "#(\://|\.|)bitly.com#" => "#bitly\.com\/(u)\/((\w|\d|[\.\-_])+)[^\/\&]*$#i",
        "#(\://|\.|)instructables.com#" => "#instructables\.com\/(member)\/((\w|\d|[\.\-_])+)[^\/\&]*$#i",
        "#(\://|\.|)en.gravatar.com#" => "#en\.gravatar\.com\/((\w|\d|[\.\-_])+)[^\/\&]*$#i",
        "#(\://|\.|)gravatar.com#" => "#gravatar\.com\/((\w|\d|[\.\-_])+)[^\/\&]*$#i",
        "#(\://|\.|)keybase.io#" => "#keybase\.io\/((\w|\d|[\.\-_])+)[^\/\&]*$#i",
        "#(\://|\.|)kongregate.com#" => "#kongregate\.com\/(accounts)\/((\w|\d|[\.\-_])+)[^\/\&]*$#i",
        "#(\://|\.|)stumbleupon.com#" => "#stumbleupon\.com\/(stumbler)\/((\w|\d|[\.\-_])+)[^\/\&]*$#i",
        "#(\://|\.|)deviantart.com#" => "#((\w|\d|[\.\-_])+)[^\/\&]\.deviantart\.com\/*$#i",
        "#(\://|\.|)8tracks.com#" => "#8tracks\.com\/((\w|\d|[\.\-_])+)[^\/\&]*$#i",
        "#(\://|\.|)9gag.com#" => "#9gag\.com\/(u)\/((\w|\d|[\.\-_])+)[^\/\&]*$#i",
        "#(\://|\.|)500px.com#" => "#500px\.com\/((\w|\d|[\.\-_])+)[^\/\&]*$#i",
        "#(\://|\.|)delicious.com#" => "#delicious\.com\/((\w|\d|[\.\-_])+)[^\/\&]*$#i",
        "#(\://|\.|)dribbble.com#" => "#dribbble\.com\/((\w|\d|[\.\-_])+)[^\/\&]*$#i",
        "#(\://|\.|)drupal.com#" => "#drupal\.com\/(u)\/((\w|\d|[\.\-_])+)[^\/\&]*$#i",
        "#(\://|\.|)fiverr.com#" => '#fiverr\.com\/((\w|\d|[\.\-_])+)[^\/\&]*$#i',
        "#(\://|\.|)last.fm#" => "#last\.fm\/(user)\/((\w|\d|[\.\-_])+)[^\/\&]*$#i",
        "#(\://|\.|)linkedin.com#" => "#linkedin\.com\/(in)\/((\w|\d|[\.\-_])+)[^\/\&]*$#i",
        "#(\://|\.|)metacafe.com#" => "#metacafe\.com\/(channels)\/((\w|\d|[\.\-_])+)[^\/\&]*$#i",
        "#(\://|\.|)path.com#" => "#path\.com\/(i)\/((\w|\d|[\.\-_])+)[^\/\&]*$#i",
        "#(\://|\.|)picasaweb.google.com#" => "#picasaweb\.google\.com\/((\w|\d|[\.\-_])+)[^\/\&]*$#i",
        "#(\://|\.|)get.google.com#" => "/get.google.com\\/albumarchive\\/(\\w+)/",
        "#(\://|\.|)picsart.com#" => "#((\w|\d|[\.\-_])+)[^\/\&]\.picsart\.com\/*$#i",
        // "#(\://|\.|)quora.com#" => "#quora\.com\/((\w|\d|[\.\-_])+)[^\/\&]*$#i",
        "#(\://|\.|)reddit.com#" => "#reddit\.com\/(user)\/((\w|\d|[\.\-_])+)[^\/\&]*$#i",
        "#(\://|\.|)squarespace.com#" => "#((\w|\d|[\.\-_])+)[^\/\&]\.squarespace\.com\/*$#i",
        "#(\://|\.|)tripadvisor.com#" => "#tripadvisor\.com\/(member)\/((\w|\d|[\.\-_])+)[^\/\&]*$#i",
        "#(\://|\.|)tunein.com#" => "#tunein\.com\/(user)\/((\w|\d|[\.\-_])+)[^\/\&]*$#i",
        "#(\://|\.|)en.wikipedia.org#" => "#en\.wikipedia\.org\/(wiki)\/((\w|\d|[\.\-_])+)[^\/\&]*$#i",
        "#(\://|\.|)insights.wired.com#" => "#insights\.wired\.com\/(profile)\/((\w|\d|[\.\-_])+)[^\/\&]*$#i",
        "#(\://|\.|)profiles.wordpress.org#" => "#profiles\.wordpress\.org\/((\w|\d|[\.\-_])+)[^\/\&]*$#i",
        "#(\://|\.|)f6s.com#" => "#f6s\.com\/((\w|\d|[\.\-_])+)[^\/\&]*$#i"
    ];

    /**
     * Optional Givven Pattern to match with profile
     * @var string
     */
    private $pattern;

    /**
     * HTML Content of the profile
     * @var array
     */
    private $htmlContent;

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

    public function __construct(CURL $curl)
    {
        $this->curl = $curl;
    }
    /**
     * Check if the URL represents a Profile
     *
     * @param string $url
     *
     * @return bool
     */
    public function isProfile(string $url, bool $skipRequests = false) : bool
    {
        $url = rtrim($url, "/");

        $normalizer = new URLNormalizer($skipRequests);
        $url = $normalizer->normalize($url);

        $host = parse_url($url, PHP_URL_HOST);

        if (preg_match("#(\://|\.|)facebook.com#", $host)) {
            return $this->isFacebookProfile($url, $skipRequests);
        } elseif (preg_match("#(\://|\.|)instagram.com#", $host)) {
            return $this->isInstagramProfile($url, $skipRequests);
        } elseif (preg_match("#(\://|\.|)twitter.com#", $host)) {
            return $this->isTwitterProfile($url, $skipRequests);
        } elseif (preg_match("#(\://|\.|)linkedin.com#", $host)) {
            return $this->isLinkedinProfile($url, $skipRequests);
        } elseif (preg_match("#(\://|\.|)angel.co#", $host)) {
            return $this->isAngelProfile($url, $skipRequests);
        } elseif (preg_match("#(\://|\.|)vine.co#", $host)) {
            return $this->isVineProfile($url, $skipRequests);
        } elseif (preg_match("#(\://|\.|)foursquare.com#", $host)) {
            return $this->isFoursquareProfile($url, $skipRequests);
        } elseif (preg_match("#(\://|\.|)quora.com#", $host)) {
            return $this->isQuoraProfile($url, $skipRequests);
        } elseif (
            preg_match("#(\://|\.|)pinterest.com#", $host) &&
            preg_match("#pinterest\.com\/[^\/]+\/?$#", $url)
        ) {
            $url = preg_replace(
                "#^.+pinterest\.[^\\/]+/#",
                "https://www.pinterest.com/",
                $url
            );
            return true;
        } elseif (preg_match("#(\://|\.|)plus.google.com#", $host)) {
            $pattern = "#plus\.google\.com\/[+]?((\w|\d|[\.\-_])+)[^\/\&]*$#i";
            preg_match($pattern, $url, $matches);
            if (isset($matches[1])) {
                return true;
            }
        }
        foreach (self::HOST_URL_PATTERNS as $hostPattern => $urlPattern) {
            if (preg_match($hostPattern, $host)) {
                preg_match($urlPattern, $url, $matches);
                return isset($matches[1]);
            }
        }
        return false;
    }

    /**
     * Check if the URL represents a facebook Profile
     *
     * @param string $url
     *
     * @return bool
     */
    private function isFacebookProfile(string $url, bool $skipRequests = false)
    {
        if (strpos($url, 'people')) {
            $normalizer = new URLNormalizer($skipRequests);
            $url = $normalizer->normalize($url);
        }
        $siteTag = (new Source)->getSiteTag($url);
        if ($siteTag == "Profile" || $siteTag == "About") {
            if ($skipRequests) {
                if (strpos($url, "/app_scoped_user_id/") !== false) {
                    // return true;
                }
                $username = new Username($url, $skipRequests);
                $username = $username->getUsername();
                return !empty($username);
            }
            
            $url2 = $this->getfacebookAboutPage($url);

            $options = [];
            $options['timeout'] = 30;
            $profile = $this->htmlContent;
            if (empty($this->htmlContent)) {
                $profile = $this->curl->curl_content($url2, $options);
                if (is_string($profile)) {
                    $text = $profile;
                    $profile = [];
                    $profile['body'] = $text;
                }
            }
            if (stripos($profile['body'], 'profile-card') !== false) {
                return true;
            }
            return false;
        }

       return false;
    }

    /**
     * Check if the URL represents an Instagram Profile
     *
     * @param string $url
     *
     * @return bool
     */
    private function isInstagramProfile(string $url) : bool
    {
        if (strpos($url, "/status/") !== false ||
            strpos($url, "/statuses/") !== false ||
            strpos($url, "/lists/") !== false ||
            stripos($url, "VIEW-INSTAGRAM") !== false ||
            stripos($url, "preprod") !== false
        ) {
            return false;
        }

        if (strpos($url,'instagram.com/p/')!== false) {
            return false;
        }
        return true;

    }

    /**
     * Check if the URL represents an Twitter Profile
     *
     * @param string $url
     *
     * @return bool
     */
    private function isTwitterProfile(string $url) : bool
    {
        if (strpos($url, "/status/") !== false ||
            strpos($url, "/statuses/") !== false ||
            strpos($url, "/lists/") !== false
        ) {
            return false;
        }
        if (!strpos($url, 'search') && strpos($url, 'twitter')) {
            $url = explode('?', $url);
            $url = $url[0];
            if (strpos($url, '/hashtag')) {
                return false;
            }
        }
        return true;
    }

    /**
     * Check if the URL represents a LinkedIn Profile
     * @param string $url
     *
     * @return bool
     */
    private function isLinkedinProfile(string $url) : bool
    {
        if (strpos($url, "pub/dir/") === false)
        {
            $patterns = [];
            $patterns[] = "#linkedin\.com\/in\/((\w|\d|[\.\-_\?\=\%\★])+)[^\/\&]*$#u";
            $patterns[] = "#linkedin\.com\/pub\/((\w|\d|[\.\-_\?\=])+)[^\/\&]*#i";
            $patterns[] = "#linkedin\.com\/profile\/view(.+)#i";

            foreach ($patterns as $pattern)
            {
                preg_match($pattern, urldecode($url), $match);
                if (isset($match[1])) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * Check if the URL represents an Angel Profile
     *
     * @param string $url
     *
     * @return bool
     */
    private function isAngelProfile(string $url) : bool
    {
        $pattern = '#angel\.co\/((\w|\d|[\.\-_])+)[^\/\&]*$#i';
        preg_match($pattern, $url, $match);
        if (isset($match[1])) {
            return true;

            /*
            if (empty($this->htmlContent['body'])) {
                $options = [];
                $this->htmlContent = $this->curl->curl_content($url, $options);
            }
            if (stripos($this->htmlContent['body'], "js-avatar-img")) {
                return true;
            }
            */
        }
        return false;
    }

    /**
     * Check if the URL represents a Vine Profile
     *
     * @param string $url
     *
     * @return bool
     */
    private function isVineProfile(string $url) : bool
    {
        $pattern = "#vine.co\/(u\/)?((\w|\d|[\.\-_])+)[^\/\&]*$#i";
        preg_match($pattern, $url, $match);
        if (isset($match[2])) {
            return true;
        }
        return false;
    }

    /**
     * Check if the URL represents a FourSquare Profile
     *
     * @param string $url
     *
     * @return bool
     */
    private function isFoursquareProfile(string $url) : bool
    {
        $pattern = "#foursquare\.com\/((\w|\d|[\.\-_])+)[^\/\&]*$#i";
        preg_match($pattern, $url, $match);
        if (!isset($match[1])) {
            $pattern = '#foursquare\.com\/(user)\/((\w|\d|[\.\-_])+)[^\/\&]*$#i';
            preg_match($pattern, $url, $match);
        }
        if (isset($match[1])) {
            return true;
        }
        return false;
    }

    /**
     * Check if the URL represents a Quora Profile
     *
     * @param string $url
     *
     * @return bool
     */
    private function isQuoraProfile(string $url) : bool
    {
        $pattern = "#quora\.com\/((\w|\d|[\.\-_])+)[^\/\&]*$#i";
        preg_match($pattern, $url, $match);
        if (isset($match[1])) {
            return true;
        }
        $pattern = "#quora\.com\/(profile)\/((\w|\d|[\.\-_])+)[^\/\&]*$#i";
        preg_match($pattern, $url, $match);
        if (isset($match[1])) {
            return true;
        }
        return false;
    }

    /**
     * Check if the profile represents an existed profile
     *
     * @param string $url
     *
     * @return bool
     */
    public function profileExists(string $url) : bool
    {
        $url = str_replace(" ", "%20", $url);
        $result = $this->htmlContent;
        $parseUrl = parse_url($url);
        $rs = false;
        if (!isset($result['header']['http_code'])) {
            $result['header'] = ['http_code' => 0];
        }
        if (
            $result['header']['http_code'] >= 200 &&
            $result['header']['http_code'] < 300
        ) {
            $rs = true;
        } if (stripos($parseUrl['host'], '8tracks.com') !== false) {
            $rs = $this->profileIn8tracks($result);
        } elseif (stripos($parseUrl['host'], 'yelp.com') !== false) {
            $rs = $this->profileInYelp($result);
        } elseif (stripos($parseUrl['host'], 'twitch.tv') !== false) {
            $rs = $this->profileInTwitch($result);
        } elseif (stripos($parseUrl['host'] , "vine.co") !== false) {
            $rs = $this->profileInVine($result);
        } elseif (stripos($parseUrl['host'] , "about.me") !== false) {
            $rs = $this->profileInAboutme($result);
        } elseif ($this->inHostsList($parseUrl['host'])) {
            $rs = $this->profileInHostsList($result, $parseUrl);
        }
        $rs = false;
        if ($result['header']['http_code'] >= 200 && $result['header']['http_code'] < 300){
                $rs = true;
        }

        if ($this->pattern) {
            if (isset($result['body']) && $result['body']) {
                $result = $result['body'];
            } else {
                $result = "";
            }

            preg_match($this->pattern, $result, $res);
            $rs = false;

            if (empty($res)) {
                $rs = true;
            }

            if ($parseUrl['host'] == 'kik.me') {
                if (!empty($res[2]) && trim($res[2])) {
                    $rs = true;
                } elseif (!empty($res[4]) && trim($res[4])) {
                    $rs = true;
                }
            }
        }
        if (strpos($url, 'xanga.com') && $rs) {
            $rs = false;
            $pattern = '#\/\/(.*).xanga#i';
            preg_match($pattern, $url, $res);
            if (count($res)) {
                $rs = true;
            }
        }
        return $rs;
    }

    /**
     * Check if the host in a list of Hosts
     * @param string $host
     *
     * @return bool
     */
    private function inHostsList(string $host) : bool
    {
        $hosts = [
            'xanga',
            'producthunt',
            '9gag',
            'delicious',
            'drupal',
            'fiverr',
            'wired',
            'wordpress',
        ];
        foreach ($hosts as $hostItem) {
            if (strpos($host, $hostItem) !== false) {
                return true;
            }
        }
        return false;
    }

    /**
     * Check if the profile exists in 8 Tracks
     *
     * @param array $result
     *
     * @return bool
     */
    private function profileIn8tracks(array $result) : bool
    {
        if (
            $result['header']['http_code'] >= 200 &&
            $result['header']['http_code'] < 300 &&
            stripos($result['body'], 'This page has vanished, or perhaps it never even existed') === false
        ) {
            return true;
        }
        return false;
    }

    /**
     * Check if the profile exists in Yelp
     *
     * @param array $result
     *
     * @return bool
     */
    private function profileInYelp(array $result) : bool
    {
        if (
            $result['header']['http_code'] >= 200 &&
            $result['header']['http_code'] < 300
        ) {
            return true;
        }
        return false;
    }

    /**
     * Check if the profile exists in Twitch
     *
     * @param array $result
     *
     * @return bool
     */
    private function profileInTwitch(array $result) : bool
    {
        $decodedData = $result;

        if (!empty($decodedData['_id']) && !empty($decodedData['name'])) {
            return true;
        }
        return false;
    }

    /**
     * Check if the profile exists in About me
     *
     * @param array $result
     *
     * @return bool
     */
    private function profileInAboutme(array $result) : bool
    {
        $path = "";
        if (isset($result['header']['url'])) {
            $path = parse_url($result['header']['url'], PHP_URL_PATH);
            $path = trim($path, '/');
        }
        if (
            $path &&
            ($result['header']['http_code'] >= 200 &&
                $result['header']['http_code'] < 300)
        ) {
            return true;
        }
        return false;
    }

    /**
     * Check if the profile exists in Vine
     *
     * @param array $result
     *
     * @return bool
     */
    private function profileInVine(array $result) : bool
    {
        return false;
    }

    /**
     * Check if the profile exists in 9 Gag
     *
     * @param array $result
     *
     * @return bool
     */
    private function profileIn9gag(array $result) : bool
    {
        $resultBody = "";
        if (isset($result['body']) && $result['body']) {
            $resultBody = $result['body'];
        }
        $pattern = "#\<title\>9GAG\s*-\s*Go\s*Fun\s*Yourself\s*.*\<\/title\>#i";
        preg_match($pattern, $resultBody, $match);
        if (!count($match)) {
            return true;
        }
        return false;
    }

    /**
     * Check if the profile exists in Delecious
     *
     * @param array $result
     *
     * @return bool
     */
    private function profileInDeleciuos(array $result) : bool
    {
        $resultBody = "";
        if (isset($result['body']) && $result['body']) {
            $resultBody = $result['body'];
        }
        $resultBodyArray = @json_decode($resultBody);
        if (isset(
            $resultBodyArray->pkg->username) &&
            $resultBodyArray->pkg->username
        ) {
            return true;
        }
        return false;
    }

    /**
     * Check if the profile exists in a list of hosts
     *
     * @param array $result
     * @param string $parseUrl
     *
     * @return bool
     */
    private function profileInHostsList(array $result, array $parseUrl) : bool
    {
        $headerHost = "";
        if (isset($result['header']['url'])) {
            $headerHost = parse_url($result['header']['url'],PHP_URL_HOST);
        }
        $exist = false;
        if (
            strpos($parseUrl['host'], 'producthunt') !== false ||
            strpos($parseUrl['host'], 'fiverr') !== false ||
            strpos($parseUrl['host'], 'wired') !== false
        ) {
            if ($parseUrl['path'] == parse_url($result['header']['url'], PHP_URL_PATH)) {
                $exist = true;
            }
        } elseif (strpos($parseUrl['host'], '9gag') !== false) {
            $exist = $this->profileIn9gag($result);
        } elseif (strpos($parseUrl['host'],'delicious') !== false) {
            $exist = $this->profileInDeleciuos($result);
        }
        if ($parseUrl['host'] == $headerHost) {
            $exist = true;
        }

        if (
            $exist &&
            ($result['header']['http_code'] >= 200 && $result['header']['http_code'] < 300)
        ) {
            $exist = true;
        }
        return $exist;
    }

    /**
     * Get Facebook About page
     *
     * @param string $url
     *
     * @return string
     */
    private function getFacebookAboutPage(string $url) : string
    {
        if (preg_match('/facebook.com\/(\d*)(\/|$)/i',  $url, $match)) {
            if (!empty($match[1])) {
                $url = 'https://www.facebook.com/profile.php?id='.$match[1];
            }
        }
        if (strpos($url, "www")) {
            $url=str_replace("/www", "/m", $url);
        } elseif (!strpos($url,"m.facebook")) {
            $url=str_replace("facebook", "m.facebook", $url);
        }

        if (strpos($url,"profile.php")) {
            return str_replace("profile.php?", "profile.php?v=info&", $url);
        }
        if (!strpos($url, "about")) {
            $url .= "/about";
        }
        return $url;
    }

    /**
     * @param int/null $personID
     *
     * @return void
     */
    public function setPersonID($personID)
    {
        $this->personID = $personID;
    }

    /**
     * @param mixed $combinationID
     *
     * @return void
     */
    public function setCombinationID($combinationID)
    {
        $this->combinationID = $combinationID;
    }

    /**
     * @param array $htmlContent
     *
     * @return void
     */
    public function setHTMLContent(array $htmlContent)
    {
        $this->htmlContent = $htmlContent;
    }

    /**
     * @param string $pattern
     *
     * @return void
     */
    public function setPattern(string $pattern)
    {
        $this->pattern = $pattern;
    }
}
