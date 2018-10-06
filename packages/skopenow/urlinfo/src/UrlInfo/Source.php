<?php

namespace Skopenow\UrlInfo\UrlInfo;


/**
 * Responsible for manibulating source and extracting sources from URLIntrface
 *
 */
class Source
{
    /**
     * Key Value CONST array, pattern => Source to match with source
     * Value is the source name
     *
     * @var array
     */
    const SOURCES = [
        "#(\:\/\/|\.|)facebook.com#i" => 'facebook',
        "#(\:\/\/|\.|)twitter.com#" => 'twitter',
        "#(\:\/\/|\.|)youtube.com#" => 'youtube',
        "#(\:\/\/|\.|)pinterest.com#" => 'pinterest',
        "#(\:\/\/|\.|)spokeo.com#" => 'spokeospokeo',
        "#(\:\/\/|\.|)10digits.us#" => '10digits',
        "#(\:\/\/|\.|)instagram.com#" => 'instagram',
        "#(\:\/\/|\.|)pipl.com#" => 'pipl',
        "#(\:\/\/|\.|)myspace.com#" => 'myspace',
        "#(\:\/\/|\.|)linkedin.com#" => 'linkedin',
        "#(\:\/\/|\.|)intelius.com#" => 'intelius',
        "#(\:\/\/|\.|)twitter.com#" => 'twitter',
        "#(\:\/\/|\.|)lookup.com#" => 'lookup',
        "#(\:\/\/|\.|)411locate.com#" => '411locate',
        "#(\:\/\/|\.|)youtube.com#" => 'youtube',
        "#(\:\/\/|\.|)facebook.com#" => 'facebook',
        "#(\:\/\/|\.|)peekyou.com#" => 'peekyou',
        "#(\:\/\/|\.|)courtcasefinder.com#" => 'courtcasefinder',
        "#(\:\/\/|\.|)plus.google.com#" => 'googleplus',
        "#(\:\/\/|\.|)flickr.com#" => 'flickr',
        "#(\:\/\/|\.|)picasaweb.google.com#" => 'picasaweb',
        "#(\:\/\/|\.|)photostream.com#" => 'photostream',
        "#(\:\/\/|\.|)meetup.com#" => 'meetup',
        "#(\:\/\/|\.|)radaris.com#" => 'radaris',
        "#(\:\/\/|\.|)peoplesmart.com#" => 'peoplesmart',
        "#(\:\/\/|\.|)peoplesmart.com#" => 'peoplesmart',
        "#(\:\/\/|\.|)411.com#" => '411',
        "#(\:\/\/|\.|)vimeo.com#" => 'vimeo',
        "#(\:\/\/|\.|)fastcompany.com#" => 'fastcompany',
        "#(\:\/\/|\.|)findthecompany.com#" => 'findthecompany',
        "#(\:\/\/|\.|)github.com#" => 'github',
        "#(\:\/\/|\.|)slideshare.net#" => 'slideshare',
        "#(\:\/\/|\.|)instructables.com#" => 'instructables',
    ];

    /**
     * CONST Array of social sources
     *
     * @var array
     */
    const SOCIAL_SOURCES = [
        "angel",
        "drupal",
        "pandora",
        "metacafe",
        "twitpic",
        "hubpages",
        "github",
        "dribbble",
        "etsy",
        "deviantart",
        "youtube",
        "foursquare",
        "myspace",
        "instagram",
        "flickr",
        "steamcommunity",
        "ebay",
        "plancast",
        "about.me",
        "tripadvisor",
        "vimeo",
        "dailymotion",
        "soundcloud",
        "quora",
        "lifestream.aol",
        "slideshare",
        "twitch",
        "vine",
        "medium",
        "behance",
        "photobucket",
        "producthunt",
        "kik.me",
        "flipboard",
        "bitly",
        "okcupid",
        "instructables",
        "gravatar",
        "keybase",
        "kongregate",
        "stumbleupon",
        "scribd",
        "8tracks",
        "wordpress",
        "wired",
        "tunein",
        "picsart",
        "picasaweb",
        "get.google",
        "linkedin",
        "last.fm",
        "fiverr",
        "500px",
        "9gag",
        "yelp",
        "ustream.tv",
    ];

    /**
     * Source name
     * Google is the default source name
     *
     * @var string
     */
    private $source = '';

    /**
     * Main source name
     * Google is the default main source name
     *
     * @var string
     */
    private $mainSource = '';

    /**
     * Source name suffix
     * Without suffix is the default
     *
     * @var string
     */
    private $sourceSuffix = "";

    /**
     * return the source name
     *
     * @return string $this->source source name
     */
    public function getSource() : string
    {
        return $this->source;
    }

    /**
     * return the main source name
     *
     * @return string $this->mainSource main source name
     */
    public function getMainSource() : string
    {
        return $this->mainSource;
    }

    /**
     * Get an instance of URLInterface and extract the source and main source
     *
     * @param  string $url url to extract the source and main source from
     *
     * @return null
     */
    public function determineSource(string $url)
    {
        // Iterate over sources array and match the url with each pattern
        // and return the matched source and main source
        foreach (self::SOURCES as $pattern => $source) {
            if (preg_match($pattern, $url)) {
                $this->mainSource = $source;
                $this->source = $this->mainSource . $this->sourceSuffix;
                return null;
            }
        }

        $host = parse_url($url, PHP_URL_HOST);

        // Iterate over social sources and match the url with each pattern
        // and return the matched source and main source
        foreach (self::SOCIAL_SOURCES as $mainSource) {
            if (stripos($host, $mainSource) !== false) {
                $this->mainSource = $mainSource;
                $this->source = $this->mainSource . $this->sourceSuffix;
                return null;
            }
        }
    }

    /**
     * Change the default source suffix
     *
     * @param string $suffix
     *
     * @return void
     */
    public function setSourceSuffix(string $suffix)
    {
        $this->sourceSuffix = $suffix;
    }

    /**
     * Get Site Tag from given URL
     * @param string $url
     * @return string
     */
    public function getSiteTag(string $url) : string
    {
        $tag = 'Website';
        $host = parse_url($url, PHP_URL_HOST);
        $this->determineSource($url);
        $siteName = $this->mainSource;
        if (
            preg_match("#(\:\/\/|\.|)peekyou.com#", $host) ||
            preg_match("#(\:\/\/|\.|)lookup.com#", $host) ||
            preg_match("#(\:\/\/|\.|)google.com#", $host) ||
            preg_match("#(\:\/\/|\.|)bing.com#", $host) ||
            preg_match("#(\:\/\/|\.|)intelius.com#", $host) ||
            preg_match("#(\:\/\/|\.|)pipl.com#", $host) ||
            preg_match("#(\:\/\/|\.|)yellowpage.com#", $host) ||
            preg_match("#(\:\/\/|\.|)10digits.us#", $host) ||
            preg_match("#(\:\/\/|\.|)courtcasefinder.com#", $host) ||
            preg_match("#(\:\/\/|\.|)411locate.com#", $host) ||
            preg_match("#(\:\/\/|\.|)spokeo.com#", $host)
        ) {
            $tag = 'Search Results';
        }
        if (
            preg_match("#(\:\/\/|\.|)plus.google.com#", $host) ||
            preg_match("#(\:\/\/|\.|)twitter.com#", $host) ||
            preg_match("#(\:\/\/|\.|)pinterest.com#", $host) ||
            preg_match("#(\:\/\/|\.|)myspace.com#", $host) ||
            preg_match("#(\:\/\/|\.|)linkedin.com#", $host) ||
            preg_match("#(\:\/\/|\.|)instagram.com#", $host) ||
            preg_match("#(\:\/\/|\.|)youtube.com#", $host)
        ) {
            $tag = 'Profile';
        }

        // Facebook profile
        $facebookRegex = '@(?:(?:http|https):\\/\\/)?(?:www.)?facebook.com\\/(?:(?:\\w)*#!\\/)?(?:pages\\/)?(?:[?\\w\\-]*\\/)?(?:profile.php.*\\?.*id=(?=\\d.*))?([\\w\\-]*)?@is';

        if (
            ($siteName == 'facebook' && strpos($url, "/app_scoped_user_id/")) ||
            (
                !stripos($url, "photo.php") &&
                strpos($url, "sk=") === false &&
                !stripos($url, "search.php") &&
                substr_count($url, "/") == 3 &&
                preg_match($facebookRegex, $url)
            )
        ) {
            $tag = 'Profile';
        }

        if (
            ($siteName == 'facebook' && stripos($url, "permalink.php")) ||
            ($siteName == 'facebook' && stripos($url, "posts"))
            ) {
            $tag = 'Post';
        }

        if (
            $siteName == 'facebook' &&
            strpos($url, "/public/") === false &&
            strpos($url, "permalink.php") === false
        ) {
            $arr = @parse_url($url);
            if (!isset($arr['path']) || $arr['path'] == "" || $arr['path'] == "/") {
                return $tag;
            }

            $expect_arr = array(
                    'photos', 'app_airbedandbreakfast', 'app_yelpyelp', 'app_playfoursquare', 'app_instapp', 'app_pinterestapp',
                    'posts', 'photos_all', 'photos_untagged', 'photos_albums', 'photos_synced', 'friends', 'about', 'games', 'map',
                    'music', 'notes', 'fitness', 'groups', 'events', 'likes', 'books', 'tv', 'movies', 'music_saved', 'followers',
                    'favorites', 'lists', 'connect', 'mentions', 'settings', 'account', 'security', 'notifications', 'profile',
                    'applications', 'widgets', 'blog', 'messages'
            );

            $flag = false;
            $delim = "/";
            $delim2 = "?sk=";
            $delim3 = "&sk=";

            foreach ($expect_arr as $expect) {
                $pos1 = strpos($url, $delim . $expect);
                $pos2 = strpos($url, $delim2 . $expect);
                $pos3 = strpos($url, $delim3 . $expect);

                if ($pos1 !== false || $pos2 !== false || $pos3 !== false) {
                    $tag = $expect;
                    if ($tag == 'app_pinterestapp') {
                        $tag = 'Pinterest';
                    } else if ($tag == 'app_instapp') {
                        $tag = 'Instagram';
                    } else if ($tag == 'app_playfoursquare') {
                        $tag = 'Foursquare';
                    } else if ($tag == 'app_yelpyelp') {
                        $tag = 'Yelp';
                    } else if ($tag == 'app_airbedandbreakfast') {
                        $tag = 'Airbnb';
                    }
                    $flag = true;
                    break;
                }
            }

            if (!$flag) {
                $pattz = "#facebook\.com\/((\w|\d|[\.\-_\?\=])+)[^\/\&]*$#i";
                $link = rtrim($url, "/");
                preg_match($pattz, $link, $prof);

                if (
                    isset($prof[1]) &&
                    strpos($prof[1], "sk=") === false &&
                    strpos($prof[1], "photo.php") === false
                ) {
                    $tag = 'Profile';
                }
            }
        }
        return ucfirst($tag);
    }

    public function getSiteName($url)
    {
        $formatter = loadService("formatter");
        $inputs = ["websites"=>[$url]];
        $formatted = $formatter->format(new \ArrayIterator($inputs));
        $url = $formatted["websites"][0]["formatted"];

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
        } elseif (strpos($sitename, "deviantart") !== false) {
            $sitename = "deviantart";
        } elseif (strpos($sitename, "tumblr") !== false) {
            $sitename = "tumblr";
        } elseif (strpos($sitename, "picsart") !== false) {
            $sitename = "picsart";
        } elseif (strpos($sitename, "gravatar") !== false) {
            $sitename = "gravatar";
        } elseif (strpos($sitename, "picasaweb") !== false) {
            $sitename = "picasa";
        } elseif (strpos($sitename, "wordpress") !== false) {
            $sitename = "wordpress";
        } elseif (strpos($sitename, "yelp") !== false) {
            $sitename = "yelp";
        } elseif (strpos($sitename, "photobucket") !== false) {
            $sitename = "photobucket";
        } elseif (strpos($sitename, "api.twitch") !== false) {
            $sitename = "twitch";
        } elseif (stripos($sitename, "en.") === 0) {
            $sitename = substr($sitename,3);
        }
        return strtoupper($sitename);
    }

    /**
     * @param string $source
     *
     * @return void
     */
    public function setDefaultSource(string $source)
    {
        $this->source = $source;
    }

    /**
     * @param string $mainSource
     *
     * @return void
     */
    public function setDefaultMainSource(string $mainSource)
    {
        $this->mainSource = $mainSource;
    }
}
