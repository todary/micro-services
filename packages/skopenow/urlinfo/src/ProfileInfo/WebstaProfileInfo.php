<?php

namespace Skopenow\UrlInfo\ProfileInfo;

use Skopenow\UrlInfo\Interfaces\ProfileInfoInterface;
use Skopenow\UrlInfo\UrlInfo\CURL;
use Skopenow\UrlInfo\HtmlDomParser;
use Skopenow\UrlInfo\ProfileInfo;

class WebstaProfileInfo implements ProfileInfoInterface
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
        \Log::info('URLInfo: getProfileInfo from websta');
        $profile = $htmlContent;
        if (empty($htmlContent)) {
            $curl_options = [];
            $profile = $this->curl->curl_content($url, $curl_options);
        }

        if (isset($profile['error_no']) && $profile['error_no']) {
            return false;
        }

        if (isset($profile['body']) && $profile['body']) {
            $content = $profile['body'];
        }

        $content = @substr($content, strpos($content, "<"));

        if ($content) {
            $profilehtml = HtmlDomParser::str_get_html($content);
            if (count($profilehtml)) {
                // Return profile with info
                $this->info['profile'] = $profilehtml;

                // Get name from profile
                $this->getName($profilehtml);
            }
        }
        return $this->info;
    }

    /**
     * @param $profilehtml
     */
    private function getName($profilehtml)
    {
        $profilehtmlname = $profilehtml->find("div[class=text-center-not-lg text-center-not-md] > p > strong", 0);
        if (count($profilehtmlname)) {
            $this->info['name'] = (trim($profilehtmlname->innertext));
        }
    }
}
