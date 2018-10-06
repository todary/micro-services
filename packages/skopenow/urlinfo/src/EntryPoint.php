<?php

namespace Skopenow\UrlInfo;

use Skopenow\UrlInfo\UrlInfo\{
    URLNormalizer,
    Source,
    Profile,
    CURL,
    ProfileImage,
    Prune,
    Username
};
use App\Models\EmailBlacklist;
use Skopenow\UrlInfo\ProfileInfo\GooglePlusProfileInfo;

/**
 * Class EntryPoint
 * The Entry point for the UrlInfo Package
 *
 * @package Skopenow\UrlInfo
 */
class EntryPoint
{
    const PROFILES = [
        'about.me' => 'AboutmeProfileInfo',
        'angel' => 'AngelProfileInfo',
        'behance' => 'BehanceProfileInfo',
        'dailymotion' => 'DailymotionProfileInfo',
        'deviantart' => 'DeviantartProfileInfo',
        'dribbble' => 'DribbbleProfileInfo',
        'drupal' => 'DrupalProfileInfo',
        'ebay' => 'EbayProfileInfo',
        'etsy' => 'EtsyProfileInfo',
        'f6s' => 'F6sProfileInfo',
        'facebook' => 'FacebookProfileInfo',
        'fiverr' => 'FiverrProfileInfo',
        'flickr' => 'FlickrProfileInfo',
        'flipboard' => 'FlipboardProfileInfo',
        'foursquare' => 'FoursquareProfileInfo',
        '9gag' => 'GagProfileInfo',
        'get.google' => 'GetgoogleProfileInfo',
        'picasa' => 'GetgoogleProfileInfo',
        'picasaweb' => 'GetgoogleProfileInfo',
        'github' => 'GithubProfileInfo',
        'plus.google' => 'GooglePlusProfileInfo',
        'googleplus' => 'GooglePlusProfileInfo',
        'gravatar' => 'GravatarProfileInfo',
        'hubpages' => 'HubpagesProfileInfo',
        'iconosquare' => 'IconosquareProfileInfo',
        'instagram' => 'InstagramProfileInfo',
        'instructables' => 'InstructablesProfileInfo',
        'keybase' => 'KeybaseProfileInfo',
        'kongregate' => 'KongregateProfileInfo',
        'last.fm' => 'LastfmProfileInfo',
        'linkedin' => 'LinkedinProfileInfo',
        'medium' => 'MediumProfileInfo',
        'meetup' => 'MeetupProfileInfo',
        'metacafe' => 'MetacafeProfileInfo',
        'myspace' => 'MyspaceProfileInfo',
        'okcupid' => 'OkcupidProfileInfo',
        'pandora' => 'PandoraProfileInfo',
        'photobucket' => 'PhotobucketProfileInfo',
        'picsart' => 'PicsartProfileInfo',
        'pinterest' => 'PinterestProfileInfo',
        'producthunt' => 'ProducthuntProfileInfo',
        '500px' => 'PxProfileInfo',
        'quora' => 'QuoraProfileInfo',
        'screenname.aol' => 'ScreennameaolProfileInfo',
        'slideshare' => 'SlideshareProfileInfo',
        'soundcloud' => 'SoundcloudProfileInfo',
        'steamcommunity' => 'SteamcommunityProfileInfo',
        '8tracks' => 'TracksProfileInfo',
        'tripadvisor' => 'TripadvisorProfileInfo',
        'tunein' => 'TuneinProfileInfo',
        'twitch' => 'TwitchProfileInfo',
        'twitter' => 'TwitterProfileInfo',
        'ustream.tv' => 'UstreamtvProfileInfo',
        'vimeo' => 'VimeoProfileInfo',
        'vine' => 'VineProfileInfo',
        'websta' => 'WebstaProfileInfo',
        'wired' => 'WiredProfileInfo',
        'wordpress' => 'WordpressProfileInfo',
        'yelp' => 'YelpProfileInfo',
        'youtube' => 'YoutubeProfileInfo',
    ];
    /**
     * Return Normalized URL
     *
     * @param string $url
     *
     * @return string
     */
    public function normalizeURL(string $url, bool $skipRequests = false) : string
    {
        $normalizer = new URLNormalizer($skipRequests);
        return $normalizer->normalize($url);
    }

    /**
     * Determine the Source name and Main source name
     *
     * @param string $url
     * @param string $sourceSuffix
     * @param string $defaultSource
     * @param string $defaultMainSource
     *
     * @return array
     */
    public function determineSource(
        string $url,
        string $sourceSuffix = "",
        string $defaultSource = "google",
        string $defaultMainSource = "google"
    ) : array {
        $source = new Source;
        $source->setSourceSuffix($sourceSuffix);
        $source->setDefaultMainSource($defaultMainSource);
        $source->setDefaultSource($defaultSource);
        $source->determineSource($url);
        $sourceName = $source->getSource();
        $mainSourceName = $source->getMainSource();
        return [$sourceName, $mainSourceName];
    }


    /**
     * Determine the Source name and Main source name as associative array
     *
     * @param string $url
     * @param string $sourceSuffix
     * @param string $defaultSource
     * @param string $defaultMainSource
     *
     * @return array
     */
    public function determineSourceAssoc(
        string $url,
        string $sourceSuffix = "",
        string $defaultSource = "google",
        string $defaultMainSource = "google"
    ) : array {
        $source = new Source;
        $source->setSourceSuffix($sourceSuffix);
        $source->setDefaultMainSource($defaultMainSource);
        $source->setDefaultSource($defaultSource);
        $source->determineSource($url);
        $sourceName = $source->getSource();
        $mainSourceName = $source->getMainSource();
        return ['source' => $sourceName, 'mainSource' => $mainSourceName];
    }

    /**
     * Return Site tag
     *
     * @param string $url
     *
     * @return string
     */
    public function getSiteTag(string $url) : string
    {
        $source = new Source;
        return $source->getSiteTag($url);
    }

    /**
     * Return Site Name
     *
     * @param string $url
     *
     * @return string
     */
    public function getSiteName(string $url) : string
    {
        $source = new Source;
        return $source->getSiteName($url);
    }


    /**
     * check the URL if presents a profile and return bool
     *
     * @param array $url
     *
     * @return bool
     */
    public function isProfile(string $url, array $htmlContent = [], bool $skipRequests = false) : bool
    {
        $profile = new Profile(new CURL);
        $profile->setPersonID(config('state.report_id'));
        $profile->setCombinationID(config('state.combination_id'));
        $profile->setHTMLContent($htmlContent);
        return $profile->isProfile($url, $skipRequests);
    }

    /**
     * Check if the profile represents an existed profile
     *
     * @param string $url
     * @param string $htmlContent
     * @param string $pattern
     *
     * @return bool
     */
    public function profileExists(string $url, array $htmlContent = [], string $pattern = "") : bool
    {
        $profile = new Profile(new CURL);
        $profile->setHTMLContent($htmlContent);
        $profile->setPattern($pattern);
        return $profile->profileExists($url);
    }

    /**
     * Extract the profile image from a profile URL
     *
     * @param string $url
     * @param string $htmlContent
     *
     * @return string
     */
    public function getProfileImage(string $url, string $htmlContent = "") : string
    {
        $profileImage = new ProfileImage($url, new CURL);
        $profileImage->setPersonID(config('state.report_id'));
        $profileImage->setCombinationID(config('state.combination_id'));
        $profileImage->setHTMLContent(['body' => $htmlContent]);
        return $profileImage->getProfileImage();
    }

    /**
     * Prune given URL
     *
     * @param string $url
     *
     * @return string
     */
    public function prepareContent($url) : string
    {
        if (!is_string($url) || is_null($url)) {
            return '';
        }
        return (new Prune)->prepareContent($url);
    }

    /**
     * Get the User name from profile Link
     *
     * @param string $url
     *
     * @return string
     */
    public function getUsername(string $url, bool $skipRequests = false) : string
    {
        $username = new Username($url, $skipRequests);
        $username->setPersonID(config('state.report_id'));
        return $username->getUsername();
    }

    public function getProfileInfoRequest(string $url)
    {
        $source = $this->determineSource($url);
        $method = 'GET';
        $options = [];
        if (in_array($source[1], array('twitter', 'pinterest', 'behance', 'vine'))) {
            $options["headers"] = [
                "Connection: close",
                "X-Requested-With:XMLHttpRequest",
                "X-Push-State-Request:true"
            ];
        }

        if ($source[1] == "googleplus") {
            list($url, $method, $options) = (new GooglePlusProfileInfo(new CURL))->getCURLOptions($url);
            return ['url' => $url, 'method' => $method, 'options' => $options];
        }

        if ($source[1] != "facebook") {
            return ['url' => $url, 'method' => $method, 'options' => $options];
        }

        $profile = $url;

        if (strpos($url, "/app_scoped_user_id//") !== false || preg_match('/facebook.com\/(\d*)(\/|$)/i', $profile, $match)) {
            $profile = $this->normalizeURL($url);
            //if (!empty($match[1])) {
            //    $profile = 'https://www.facebook.com/profile.php?id=' . $match[1];
            //}
        }
        $url = $profile;
        if (strpos($profile, "www")) {
            $url = str_replace("/www", "/m", $profile);
        } elseif (!strpos($profile, "m.facebook")) {
            $url = str_replace("facebook", "m.facebook", $profile);
        }
        if (!strpos($url, "about") && strpos($url, "profile.php")===false) {
            $url .= "/about";
        }
        if (!strpos($url, "//")) {
            str_replace('//', '/', $url);
        }

        if (strpos($url, "profile.php")) {
            $url = str_replace("profile.php?", "profile.php?v=info&", $url);
        }
        return ['url' => $url, 'method' => $method, 'options' => $options];
    }

    /**
     * return Profile Info from URL
     * @param string $url profile url
     * @param string $source source name
     * @param array $htmlContent html page
     *
     * @return array profile info
     */
    public function getProfileInfo(string $url, string $source, array $htmlContent = array()) : array
    {
        $normalizer = new URLNormalizer();
        $url = $normalizer->normalize($url);
        $info = (new ProfileInfo)->info;
        $info = ['profileUrl' => $url];

        if (array_key_exists($source, self::PROFILES)) {
            $class = '\\Skopenow\\UrlInfo\\ProfileInfo\\' . self::PROFILES[$source];
            $profile = new $class(new CURL);
            $info = $profile->getProfileInfo($url, $htmlContent);
        } else {
            return $info;
        }

        $currentUrl = $info['profileUrl']??$url;

        if (empty($info['username'])) {
            $info['username'] = $this->getUsername($currentUrl);
        }

        if (!empty($info['image']) && strpos($info['image'], 'default')!==false) {
            $info['image'] = null;
        }
        if ($info['name'] && $info['name']==$info['username']) {
            $info['username_as_name'] = $info['name'];
            $info['name'] = null;
        }
        return $info;
    }

    /**
     * return Profile Info from URL
     * @param array $urls
     *
     * @return ArrayIterator profile info
     */
    public function getMultipleProfilesInfo(array $urls) : \ArrayIterator
    {
        $normalizer = new URLNormalizer();
        $defaultInfo = (new ProfileInfo)->info;
        $profilesInfo = [];
        $urlsWithHTML = [];
        $httpService = loadService('HttpRequestsService');
        foreach ($urls as $url) {
            $profilesInfo[$url] = null;
            $request = $this->getProfileInfoRequest($normalizer->normalize($url));
            $httpService->createRequest($request['url'], null, $request['method'], $request['options'], function ($response) use (&$urlsWithHTML, $url) {
                    $response = $response->getResponse();
                    $response->getBody()->rewind();
                    $body = $response->getBody()->getContents();
                    if (!$body) {
                        return;
                    }
                    $urlsWithHTML[$url] = ['body' => $body];
                }, function ($err) {
                }
            );
        }
        \Log::info("URLInfo: Getting profile pages of:", $urls);
        $httpService->processRequests();
        \Log::info("URLInfo: Done getting profile pages");
        foreach ($urlsWithHTML as $url => $htmlContent) {
            $info = $defaultInfo;
            $info['profileUrl'] = $url;
            $source = $this->determineSource($url)[0];
            if (!array_key_exists($source, self::PROFILES) || empty($htmlContent)) {
                continue;
            }
            $class = '\\Skopenow\\UrlInfo\\ProfileInfo\\' . self::PROFILES[$source];
            $profile = new $class(new CURL);
            $info = $profile->getProfileInfo($url, $htmlContent);
            $currentUrl = $info['profileUrl']??$url;

            if (empty($info['username'])) {
                $info['username'] = $this->getUsername($currentUrl);
            }

            if (!empty($info['image']) && strpos($info['image'], 'default')!==false) {
                $info['image'] = null;
            }
            if ($info['name'] && $info['name']==$info['username']) {
                $info['username_as_name'] = $info['name'];
                $info['name'] = null;
            }
            $profilesInfo[$url] = $info;
        }
        return new \ArrayIterator($profilesInfo);
    }

    public function isBannedDomain(string $domain) : bool
    {
        $blackList = EmailBlacklist::where('domain', $domain)->first();
        return $blackList !== null;
    }

    public function isDomain(string $url) : bool
    {
        if (strpos($url, '//') === false) {
            $url = '//' . $url;
        } else {
            return false;
        }
        if (!(strpos($url, 'www') === false)) {
            return false;
        }

        $path = parse_url($url, PHP_URL_PATH);
        $path = trim($path, '/ ');
        return empty($path);
    }
}
