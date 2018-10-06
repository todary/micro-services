<?php
namespace Skopenow\UrlInfo\ProfileInfo;

use Skopenow\UrlInfo\Interfaces\ProfileInfoInterface;

class VimeoProfileInfo extends SocialProfileInfoAbstraction implements ProfileInfoInterface
{
    private $source = [
        'name' => "/<h1><span>([^<]*)</i",
        'image' => "/portrait_main['\"]\\s*src=[\"']([^'\"]*)[\"']/i",
        'location' => "/location[\"']>([^<]*)</i"
    ];

    public function getProfileInfo(string $url, array $htmlContent)
    {
        \Log::info('URLInfo: getProfileInfo from vimeo');
        $content = parent::getProfileInfo($url, $htmlContent);
        if ($content == false) {
            return $this->info;
        }
        $pattern = '/<script type="application\/ld\+json">\n*\s*\[([^<]*)\]/is';
        $json = [];
        if (preg_match($pattern, $content, $matches)) {
            $json = json_decode($matches[1], true);
            if (!is_array($json)) {
                return $this->info;
            }
        }

        if (array_key_exists('name', $json)) {
            $this->info['name'] = $json['name'];
        }

        if (array_key_exists('image', $json)) {
            $this->info['image'] = $json['image'];
        }

        $this->info['status'] = true;
        return $this->info;
    }
}
