<?php

namespace Skopenow\UrlInfo\ProfileInfo;

use Skopenow\UrlInfo\UrlInfo\CURL;
use Skopenow\UrlInfo\Interfaces\ProfileInfoInterface;

class DeviantartProfileInfo extends SocialProfileInfoAbstraction implements ProfileInfoInterface
{
    private $source = [
        'name' => "/<h1>\s*<span.*href.*>(.*)<\/a/i",
        'image' => '/avatar\s*float-left[\'"]\s*src\s*=\s*[\'"]([^\'"]*)[\'"]/i',
    ];

    public function getProfileInfo(string $url, array $htmlContent)
    {
        \Log::info('URLInfo: getProfileInfo from Deviantart, URL: ' . $url);
        $content = parent::getProfileInfo($url, $htmlContent);
        if ($content == false) {
            return $this->info;
        }

        $this->info['name'] = $this->getName($this->source, $content);
        $this->info['image'] = $this->getImage($this->source, $content);
        $this->info['profile'] = $content;
        $this->info['status'] = true;

        return $this->info;
    }
}
