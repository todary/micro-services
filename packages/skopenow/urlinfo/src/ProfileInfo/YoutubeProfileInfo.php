<?php

namespace Skopenow\UrlInfo\ProfileInfo;

use Skopenow\UrlInfo\Interfaces\ProfileInfoInterface;
use Skopenow\UrlInfo\UrlInfo\{CURL, Source};
use Skopenow\UrlInfo\ProfileInfo;

class YoutubeProfileInfo implements ProfileInfoInterface
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
        \Log::info('URLInfo: getProfileInfo from youtube');
        $url = trim($url,"/");
        if (!empty($url)) {
            if (!stripos("about", strtolower($url)))
            {
                $url .= "/about";
            }
        }

        $profile = $htmlContent;
        if (empty($htmlContent)) {
            $curl_options = [];
            $profile = $this->curl->curl_content($url, $curl_options);
        }

        if (isset($profile['error_no']) && $profile['error_no']) {
            return $this->info;
        }

        $content = "";
        if (isset($profile['body']) && $profile['body']) {
            $content = $profile['body'];
        }

        $pattern = '/window\[\"ytInitialData\"\]\s*\=\s*([^;]+)/is';
        preg_match($pattern, $content, $json);
        $data = @json_decode($json[1], true);
        if ($data) {

            // Return profile with info ..
            $this->info['profile'] = $profile;

            // Get name from profile
            $this->getName($data);
            // $this->getName($profilehtml);

            // Get image from profile
            $this->getImage($data);

            // Get Location from profile
            $this->getLocation($data);

            // Get Links
            $this->getProfileLinks($data);
        }
        return $this->info;
    }

    /**
     * @param $data
     */
    private function getName($data)
    {
        if (count($data)) {
            if (!empty($data['header']['c4TabbedHeaderRenderer']['title'])) {
                $this->info['name'] = $data['header']['c4TabbedHeaderRenderer']['title'] ?? "";
            }
        }
    }

    /**
     * @param $data
     */
    private function getImage($data)
    {
        if (count($data)) {
            $this->info['image'] = $data['header']['c4TabbedHeaderRenderer']['avatar']['thumbnails'][0]['url']??"";
        }
    }

    /**
     * @param $data
     */
    private function getLocation($data)
    {
        if (count($data)) {
            $tabs = [];
            if (!empty($data['contents']['twoColumnBrowseResultsRenderer']['tabs'])) {
                $tabs = $data['contents']['twoColumnBrowseResultsRenderer']['tabs'];
            }
            $about = count($tabs) - 2;
            if ($about < 0) {
                $content = $this->info['location'];
                return;
            }
            $content = $tabs[$about]['tabRenderer']["content"]['sectionListRenderer']['contents'][0]['itemSectionRenderer']['contents'][0]['channelAboutFullMetadataRenderer']??[];
            if (!empty($content['country']['simpleText'])) {
                $this->info['location'][] = $content['country']['simpleText'];
            }
        }
    }

    /**
     * @param $data
     */
    private function getProfileLinks($data)
    {
        $tabs = [];
        if (!empty($data['contents']['twoColumnBrowseResultsRenderer']['tabs'])) {
            $tabs = $data['contents']['twoColumnBrowseResultsRenderer']['tabs'];
        } else {
            return;
        }

        $about = count($tabs) - 2;
        if ($about < 0) {
            $content = $this->info['location'];
            return;
        }
        $links = $tabs[$about]['tabRenderer']["content"]['sectionListRenderer']['contents'][0]['itemSectionRenderer']['contents'][0]['channelAboutFullMetadataRenderer']['primaryLinks']??[];
        if (is_array($links)) {
            foreach ($links as $link) {
                if (empty($link['navigationEndpoint']['urlEndpoint']['url'])) continue;

                $source = new Source;
                $url = $link['navigationEndpoint']['urlEndpoint']['url'];
                $source->determineSource($url);
                $main_source = $source->getMainSource();
                if (
                    strtolower($main_source) == "youtube" ||
                    strtolower($main_source) == "googleplus"
                ) {
                    continue;
                }
                $this->info['links'][] = $link['navigationEndpoint']['urlEndpoint']['url'];
            }
        }

        if ($this->info['links']) {
            $this->info['links'] = array_unique($this->info['links']);
        }
    }
}
