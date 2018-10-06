<?php
namespace Skopenow\UrlInfo\ProfileInfo;

use Skopenow\UrlInfo\UrlInfo\CURL;
use Skopenow\UrlInfo\ProfileInfo;

abstract class SocialProfileInfoAbstraction
{
    protected $info;
    private $curl;
    private $url;

    public function __construct(CURL $curl)
    {
        $this->curl = $curl;
        $this->info = (new ProfileInfo)->info;
    }

    public function getProfileInfo(string $url, array $htmlContent)
    {
        $this->url = $url;
        \Log::info('URLInfo: getProfileInfo ... url:  ' . $this->url);
        $profile =  $htmlContent;
        if (empty($htmlContent)) {
            $curl_options = [];
            if (strpos($url, 'behance')) {
                $curl_options['headers'] = [
                    'Connection' => 'close',
                    'X-Requested-With' => 'XMLHttpRequest',
                    'X-Push-State-Request' => 'true',
                ];
            }
            $profile = $this->curl->curl_content($url, $curl_options);
        }

        if ((isset($profile['error_no']) && $profile['error_no']) || empty($profile['body'])) {
            \Log::info('URLInfo: getProfileInfo ... 404 profile not found URL: ' . $this->url);
            $this->info['status'] = false;
            return false;
        }
        $content = '';
        if (isset($profile['body']) && $profile['body']) {
            $content = $profile['body'];
        }

        if ($content) {
            // Return profile with info ..
            $this->info['profile'] = $profile;

        }
        return $content;
    }

    protected function getName(array $source, string $content)
    {
        \Log::info('URLInfo: getProfileInfo getting name:  ' . $this->url);
        $this->info['status'] = true;
        $name = "";
        preg_match($source['name'], $content, $nameArray);
        if (count($nameArray) >= 2) {
            $name = stripslashes($nameArray[1]);
            if (array_key_exists(2, $nameArray)) {
                $name .= " " . $nameArray[2];
            }
        }
        if ($name) {
            $name = strip_tags($name);
            $name = $this->filterName(trim($name));
        } else {
            \Log::info('URLInfo: getProfileInfo ... no name found in this profile');
        }
        return $name;
    }

    protected function getImage(array $source, string $content)
    {
        \Log::info('URLInfo: getProfileInfo getting Image:  ' . $this->url);
        $image = "";
        preg_match($source['image'], $content, $imageArray);
        if (count($imageArray) >= 2) {
            $image = stripslashes($imageArray[1]);
        }

        if ($image) {
            \Log::info('URLInfo: getProfileInfo ... got Image: ' . $image);
            $image = trim($image);
        } else {
            \Log::info('URLInfo: getProfileInfo ... no Image found in this profile');
        }
        return $image;
    }

    protected function getLocation(array $source, string $content)
    {
        \Log::info('URLInfo: getProfileInfo getting location:  ' . $this->url);
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
            if (array_key_exists(2, $locationArray)) {
                $location .= " , " . stripslashes($locationArray[2]);
            }
        }
        if ($location) {
            \Log::info('URLInfo: getProfileInfo ... got location: ' . $location);
            $location = trim($location);
        } else {
            \Log::info('URLInfo: getProfileInfo ... no Image found in this profile');
        }
        return $location;
    }

    protected function filterName(string $name)
    {
        $ignoredNames = [
            "\ud83c\udf38" => "",
            "ud83cudf38"   => "",
            "\ud83d\uddfc" => "",
            "ud83duddfc"   => "",
        ];

        // convert \u00e9 into é related to Task#11250 .
        $name = $this->Utf8_ansi($name);
        $name = strtr($name, $ignoredNames);

        // Remove all unicode
        $name = preg_replace("/\\\\u\\d{4}/i", "", $name);

        $name = preg_replace_callback("/(&#[0-9]+;)/", function($m) {
            return mb_convert_encoding($m[1], "UTF-8", "HTML-ENTITIES");
        }, $name);

        $name = preg_replace("#[^\w\s\-]#u", "", $name);
        $name = preg_replace("#u(\d+)#u", "", $name);


        return trim($name);
    }

    /**
     * Utf8_ansi convert utf8 html into ansi
     * @param string $valor [description]
     */
    public static function Utf8_ansi($valor = '')
    {
        $utf8_ansi2 = [
            "\u00c0" =>"À",
            "\u00c1" =>"Á",
            "\u00c2" =>"Â",
            "\u00c3" =>"Ã",
            "\u00c4" =>"Ä",
            "\u00c5" =>"Å",
            "\u00c6" =>"Æ",
            "\u00c7" =>"Ç",
            "\u00c8" =>"È",
            "\u00c9" =>"É",
            "\u00ca" =>"Ê",
            "\u00cb" =>"Ë",
            "\u00cc" =>"Ì",
            "\u00cd" =>"Í",
            "\u00ce" =>"Î",
            "\u00cf" =>"Ï",
            "\u00d1" =>"Ñ",
            "\u00d2" =>"Ò",
            "\u00d3" =>"Ó",
            "\u00d4" =>"Ô",
            "\u00d5" =>"Õ",
            "\u00d6" =>"Ö",
            "\u00d8" =>"Ø",
            "\u00d9" =>"Ù",
            "\u00da" =>"Ú",
            "\u00db" =>"Û",
            "\u00dc" =>"Ü",
            "\u00dd" =>"Ý",
            "\u00df" =>"ß",
            "\u00e0" =>"à",
            "\u00e1" =>"á",
            "\u00e2" =>"â",
            "\u00e3" =>"ã",
            "\u00e4" =>"ä",
            "\u00e5" =>"å",
            "\u00e6" =>"æ",
            "\u00e7" =>"ç",
            "\u00e8" =>"è",
            "\u00e9" =>"é",
            "\u00ea" =>"ê",
            "\u00eb" =>"ë",
            "\u00ec" =>"ì",
            "\u00ed" =>"í",
            "\u00ee" =>"î",
            "\u00ef" =>"ï",
            "\u00f0" =>"ð",
            "\u00f1" =>"ñ",
            "\u00f2" =>"ò",
            "\u00f3" =>"ó",
            "\u00f4" =>"ô",
            "\u00f5" =>"õ",
            "\u00f6" =>"ö",
            "\u00f8" =>"ø",
            "\u00f9" =>"ù",
            "\u00fa" =>"ú",
            "\u00fb" =>"û",
            "\u00fc" =>"ü",
            "\u00fd" =>"ý",
            "\u00ff" =>"ÿ"
        ];
        return strtr($valor, $utf8_ansi2);
    }
}
