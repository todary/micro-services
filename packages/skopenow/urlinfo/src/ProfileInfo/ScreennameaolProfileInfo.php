<?php
namespace Skopenow\UrlInfo\ProfileInfo;

use Skopenow\UrlInfo\Interfaces\ProfileInfoInterface;

class ScreennameaolProfileInfo extends SocialProfileInfoAbstraction implements ProfileInfoInterface
{
    private $source = [
        'name' => "/Profile<\/a>\s*for\s*([^'\"]*)<\/h2><\/p><\/div>/i",
        'image' => "/<div\s*id\=\"oa-icon\"\>\<img\s*src\=['\"]([^\"']*)[\"']/i",
    ];

    public function getProfileInfo(string $url, array $htmlContent)
    {
        \Log::info('URLInfo: getProfileInfo from aol');
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
