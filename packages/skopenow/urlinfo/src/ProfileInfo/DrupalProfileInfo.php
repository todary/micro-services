<?php
namespace Skopenow\UrlInfo\ProfileInfo;

use Skopenow\UrlInfo\Interfaces\ProfileInfoInterface;

class DrupalProfileInfo extends SocialProfileInfoAbstraction implements ProfileInfoInterface
{
    private $source = [
        'name'=>"/page-title[\"'][^>]*>([^<]*)<\\/h1/is",
        'image'=>"/user-picture[\"'].*?img\\s*src=['\"]([^'\"]*)[\"']/is",
        'location'=>"/field-name-field-country.*?even[\"']>([^<]*)</is"
    ];

    public function getProfileInfo(string $url, array $htmlContent)
    {
        \Log::info('URLInfo: getProfileInfo from Drupal, URL: ' . $url);
        $content = parent::getProfileInfo($url, $htmlContent);
        if ($content == false) {
            return $this->info;
        }

        $this->info['name'] = $this->getName($this->source, $content);
        $this->info['location'][] = $this->getLocation($this->source, $content);
        $this->info['image'] = $this->getImage($this->source, $content);
        $this->info['profile'] = $content;
        $this->info['status'] = true;

        return $this->info;
    }
}
