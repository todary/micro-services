<?php
namespace Skopenow\UrlInfo\ProfileInfo;

use Skopenow\UrlInfo\Interfaces\ProfileInfoInterface;

class HubpagesProfileInfo extends SocialProfileInfoAbstraction implements ProfileInfoInterface
{
    private $source = [
        'name' => "/author_primary_name[\"']>([^<]*)</i",
        'image' => "/big_bio.*?original\=[\"']([^\"']*)[\"']/i",
        'location' => "/bio_stats.*?from\\s([^.]*)./i"
    ];

    public function getProfileInfo(string $url, array $htmlContent)
    {
        \Log::info('URLInfo: getProfileInfo from hubpages');
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
