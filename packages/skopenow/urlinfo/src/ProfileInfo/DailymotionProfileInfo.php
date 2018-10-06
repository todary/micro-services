<?php
namespace Skopenow\UrlInfo\ProfileInfo;

use Skopenow\UrlInfo\Interfaces\ProfileInfoInterface;

class DailymotionProfileInfo extends SocialProfileInfoAbstraction implements ProfileInfoInterface
{
    private $source = [
        'name' => "/class\=\"biographical\".*?>([^'\"]*)<\/span>/is",
        'image' => "/<div\s*class\=\"avatar-container\".*?src=['\"]([^'\"]*)[\"']/is",
    ];

    public function getProfileInfo(string $url, array $htmlContent)
    {
        \Log::info('URLInfo: getProfileInfo from DailyMotion, URL: ' . $url);
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
