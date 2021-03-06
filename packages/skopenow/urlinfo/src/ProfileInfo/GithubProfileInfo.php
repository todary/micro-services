<?php

namespace Skopenow\UrlInfo\ProfileInfo;

use Skopenow\UrlInfo\UrlInfo\CURL;
use Skopenow\UrlInfo\ProfileInfo;
use Skopenow\UrlInfo\Interfaces\ProfileInfoInterface;

class GithubProfileInfo extends SocialProfileInfoAbstraction implements ProfileInfoInterface
{
    private $source = [
        'name' => '/<span\s*class=["\'].*vcard-fullname[^.]*?"name">(.*(?=<))/i',
        'image' => '/<img.*?class="avatar.*?src=\"(.+?)\".*?\/>/i',
        'location' => '/aria-label=\"Home location\".*?title=\".*?<span class="p-label">(.+?)<\/span>/s',
    ];

    public function getProfileInfo(string $url, array $htmlContent)
    {
        \Log::info('URLInfo: getProfileInfo from github');
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
