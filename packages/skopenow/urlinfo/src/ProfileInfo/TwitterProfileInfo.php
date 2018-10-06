<?php

namespace Skopenow\UrlInfo\ProfileInfo;

use Skopenow\UrlInfo\Interfaces\ProfileInfoInterface;
use Skopenow\UrlInfo\UrlInfo\CURL;
use Skopenow\UrlInfo\HtmlDomParser;
use Skopenow\UrlInfo\ProfileInfo;

class TwitterProfileInfo implements ProfileInfoInterface
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
        \Log::info('URLInfo: getProfileInfo from twitter');
        if (empty($htmlContent)) {
            $curl_options = [];
            $curl_options["headers"] = [
                "Connection: close",
                "X-Requested-With:XMLHttpRequest",
                "X-Push-State-Request:true"
            ];

            $htmlContent = $this->curl->curl_content($url, $curl_options);
        }
        if (isset($htmlContent['error_no']) && $htmlContent['error_no']) {
            return false;
        }

        $profilehtml = HtmlDomParser::str_get_html($htmlContent['body']);

        $htmlContent = @json_decode($htmlContent['body'], true);
        if (!empty($htmlContent["page"])) {
            $profilehtml = HtmlDomParser::str_get_html($htmlContent["page"]);
        }
        if ($profilehtml === false) {
            return $this->info;
        }

        if (count($profilehtml)) {

            // Return profile with info ..
            $this->info['profile'] = $profilehtml;

            // Get name from profile
            $this->getName($profilehtml);

            // Get Location from profile
            $this->getLocation($profilehtml);

            // Get profile pic
            $this->getImage($profilehtml);

            // get link from twitter
            $this->getLinks($profilehtml);

            // get bio from twitter
            $this->getBio($profilehtml);
        }
        return $this->info;
    }

    private function getName($profilehtml)
    {
        $profilehtmlname = $profilehtml->find("a.ProfileHeaderCard-nameLink", 0);
        if (count($profilehtmlname)) {
            $this->info['name'] = trim($profilehtmlname->innertext);
            // To clear any html tags beside the name
            if (strpos($this->info['name'], '<')) {
                $this->info['name'] = trim(preg_replace('#<.*#', '', $this->info['name']));
            }
        }
    }

    private function getLocation($profilehtml)
    {
        $profilehtmllocation = $profilehtml->find("span.ProfileHeaderCard-locationText", 0);
        if (count($profilehtmllocation)) {
            $this->info['location'][] = strip_tags(trim($profilehtmllocation->innertext));
        }
    }

    private function getImage($profilehtml)
    {
        $profilehtmlpicture = $profilehtml->find("img.ProfileAvatar-image", 0);
        if (count($profilehtmlpicture)) {
            $this->info['image'] = (trim($profilehtmlpicture->src));
        }
    }

    private function getLinks($profilehtml)
    {
        $link = $profilehtml->find(".ProfileHeaderCard-urlText a", 0);
        if (isset($link)) {
            if(strpos($link->innertext, 'http') === false){
                $this->info['links'][] = "https://".trim($link->innertext);
            }
        }
    }

    private function getBio($profilehtml)
    {
        $profilehtmlBio = $profilehtml->find(".ProfileHeaderCard-bio", 0);
        if ($profilehtmlBio) {
            $this->info['bio'] = strip_tags($profilehtmlBio->innertext);
        }
    }
}
