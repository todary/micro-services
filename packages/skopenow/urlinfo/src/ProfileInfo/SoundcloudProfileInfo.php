<?php
namespace Skopenow\UrlInfo\ProfileInfo;

use Skopenow\UrlInfo\Interfaces\ProfileInfoInterface;

class SoundcloudProfileInfo extends SocialProfileInfoAbstraction implements ProfileInfoInterface
{
    private $source = [
        'name' => "/<h1 itemprop=\"name\"><a.*\">([^<]*)<\/a><\/h1>/is",
        'image' => "/avatar_url\":\"([^\"]*)\"/i",
        'location' => '/locality.*"([^<]*)">.*"og:country-name" content="([^<]*)">/is',
        'locationSort' => [2, 1]
    ];

    private $person;
    private $comb;

    public function getProfileInfo(string $url, array $htmlContent)
    {
        \Log::info('URLInfo: getProfileInfo from Soundcloud');
        $content = parent::getProfileInfo($url, $htmlContent);
        if ($content == false) {
            return $this->info;
        }

        $this->person = ['id' => config('state.report_id')];
        $this->comb = [
            'id' => config('state.combination_id'),
            'person_id' => config('state.user_id')
        ];

        $this->info['name'] = $this->getName($this->source, $content);
        $this->info['image'] = $this->getImage($this->source, $content);
        $this->info['location'][] = $this->getLocation($this->source, $content);
        $this->info['profile'] = $content;
        $this->info['status'] = true;

        return $this->info;
    }

    protected function getLocation(array $source, string $content)
    {
        $location = "";
        $locationPatterns = $source['location'];
        if (!is_array($source['location'])) {
            $locationPatterns = [$source['location']];
        }

        $locationArray = [];
        foreach ($locationPatterns as $key => $locationPattern) {
            preg_match($locationPattern, $content, $locationArray);
            if (count($locationArray) >= 2) {
                break;
            }
        }

        if (count($locationArray) >= 2) {
            $location = stripslashes($locationArray[1]);

            $location = stripslashes($locationArray[$source['locationSort'][0]]);
            if(array_key_exists(2, $locationArray)) {
                $location .= " , " . stripslashes($locationArray[$source['locationSort'][1]]);
            }
        }
        if ($location) {
            if (count($this->person) && count($this->comb)) {
                // TODO
                // SearchApis::logData($this->person['id'], "(social) Found search location to check ".$source." profile $url", $this->comb);
            }
            $location = (trim($location));
        } elseif (count($this->person) && count($this->comb)) {
            // TODO
            // SearchApis::logData($this->person['id'],"(social) Not found search location for profile $url", $this->comb);
        }
        return $location;
    }
}
