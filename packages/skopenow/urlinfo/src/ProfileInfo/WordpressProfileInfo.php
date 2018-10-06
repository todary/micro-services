<?php
namespace Skopenow\UrlInfo\ProfileInfo;

use Skopenow\UrlInfo\Interfaces\ProfileInfoInterface;

class WordpressProfileInfo extends SocialProfileInfoAbstraction implements ProfileInfoInterface
{
    private $source = [
        'name' => "/<h2\s*class=\"fn\">(.*?)<\/h2>/is",
        'image' => "/<img\s*[^>]*src=\'(.*?)\'[^>]*class='avatar\s*avatar-150 photo'/is",
        'location' => '/"user-location"[^>]*?>.*?<\/div>([^<]*)<\/li/is',
    ];

    public function getProfileInfo(string $url, array $htmlContent)
    {
        \Log::info('URLInfo: getProfileInfo from wordpress');
        $content = parent::getProfileInfo($url, $htmlContent);
        if ($content == false) {
            return $this->info;
        }

        $this->info['name'] = $this->getName($this->source, $content);
        if (!empty($this->getImage($this->source, $content))) {
            $this->info['image'] = 'http:' . $this->getImage($this->source, $content);
        }
        $this->info['location'][] = $this->getLocation($this->source, $content);
        $this->info['profile'] = $content;
        $this->info['status'] = true;

        return $this->info;
    }
}
