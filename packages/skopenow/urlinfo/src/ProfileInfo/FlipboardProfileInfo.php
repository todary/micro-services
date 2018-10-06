<?php
namespace Skopenow\UrlInfo\ProfileInfo;

use Skopenow\UrlInfo\Interfaces\ProfileInfoInterface;

class FlipboardProfileInfo extends SocialProfileInfoAbstraction implements ProfileInfoInterface
{
    private $source = [
        'name' => "/<h1>([^<]*)<\\/h1/i",
        'image' => "/authorImage\":{\"smallURL\":\"([^\"]*)\"}/",
    ];

    public function getProfileInfo(string $url, array $htmlContent)
    {
        \Log::info('URLInfo: getProfileInfo from flipboard');
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
