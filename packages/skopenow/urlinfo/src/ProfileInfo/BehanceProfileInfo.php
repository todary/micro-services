<?php
namespace Skopenow\UrlInfo\ProfileInfo;

use Skopenow\UrlInfo\Interfaces\ProfileInfoInterface;

class BehanceProfileInfo extends SocialProfileInfoAbstraction implements ProfileInfoInterface
{
    private $source = [
        'name' => "/profile-display-name[\"']>[^>]+>([^<]*)<\\//is",
        'image' => "/class\=\"rf-avatar\s*js-avatar\s*\"\s*data-id=\"\">[^=]*=[\"']([^\"']*)[\"']/is",
        'location' => "/beicons-pre-location[^>]*>([^<]*)<\\//is",
    ];

    public function getProfileInfo(string $url, array $htmlContent)
    {
        \Log::info('URLInfo: getProfileInfo from Behance, URL: ' . $url);
        $content = parent::getProfileInfo($url, $htmlContent);
        if ($content == false) {
            return $this->info;
        }
        $content = json_decode($content, true);
        $data = $content['section_content'][0]['owners'][0];
        $this->info['name'] = $data['display_name'];
        $this->info['image'] = $data['images'][276];
        $this->info['location'][] = $data['location'];
        $this->info['profile'] = $content;
        $this->info['status'] = true;

        return $this->info;
    }
}
