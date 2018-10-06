<?php
namespace Skopenow\UrlInfo\ProfileInfo;

use Skopenow\UrlInfo\Interfaces\ProfileInfoInterface;

class YelpProfileInfo extends SocialProfileInfoAbstraction implements ProfileInfoInterface
{
    private $source = [
        'name' => "/user-profile_info[^<]*?<h1>([^<]*)<\\/h1>/s",
        'location' => "/user-profile_info.*?<h3[^>]*>([^<]*)<\\/h3>/s",
        'image' => "/user-profile_avatar[\"'].*?src=[\"']([^\"']*)[\"']/s"
    ];

    public function getProfileInfo(string $url, array $htmlContent)
    {
        \Log::info('URLInfo: getProfileInfo from yelp');
        $content = parent::getProfileInfo($url, $htmlContent);
        if ($content == false) {
            return $this->info;
        }

        $this->info['name'] = $this->getName($this->source, $content);
        $this->info['image'] = $this->getImage($this->source, $content);
        $this->info['location'][] = $this->getLocation($this->source, $content);
        $this->info['profile'] = $content;
        $this->info['status'] = true;

        return $this->info;
    }
}
