<?php
namespace Skopenow\UrlInfo\ProfileInfo;

use Skopenow\UrlInfo\Interfaces\ProfileInfoInterface;

class GravatarProfileInfo extends SocialProfileInfoAbstraction implements ProfileInfoInterface
{
    private $source = [
        "name" => "/class\\s*=['\"]fn['\"].*?<a[^>]*?>([^<]*)/is",
        'image' => "/gallery-main.*?\\s*?.*?href=['\\\"]([^\\\"]*)/is",
    ];

    public function getProfileInfo(string $url, array $htmlContent)
    {
        \Log::info('URLInfo: getProfileInfo from gravatar');
        $content = parent::getProfileInfo($url, $htmlContent);
        if ($content == false) {
            return $this->info;
        }

        $this->info['name'] = $this->getName($this->source, $content);
        $this->info['image'] = $this->getImage($this->source, $content);
        $this->info['image'] = str_ireplace('http://', 'https://', $this->info['image']);
        $this->info['profile'] = $content;
        $this->info['status'] = true;

        return $this->info;
    }
}
