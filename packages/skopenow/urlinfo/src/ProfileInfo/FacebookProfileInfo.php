<?php

namespace Skopenow\UrlInfo\ProfileInfo;

use Skopenow\UrlInfo\Interfaces\ProfileInfoInterface;
use Skopenow\UrlInfo\UrlInfo\{CURL, URLNormalizer};
use Skopenow\UrlInfo\HtmlDomParser;
use Skopenow\UrlInfo\ProfileInfo;

class FacebookProfileInfo implements ProfileInfoInterface
{
    protected $info;
    public function __construct(CURL $curl)
    {
        $this->curl = $curl;
        $this->info = (new ProfileInfo)->info;
    }

    public function getProfileInfo(string $url, array $htmlContent)
    {
        \Log::info('URLInfo: getProfileInfo from facebook URL: ' . $url);
        $url = $this->getFacebookAboutPage($url);

        $request_url = $url;
        //$sess_id = "automation_sessions_facebook";
        //$request_url = (strpos($url, '?') !== false) ? (str_replace('?', '?__sid=' . $sess_id . '&', $url)) : ($url . '?__sid=' . $sess_id);


        // check facebook page type : for posts and events and (first case for pages) ..
        $this->info['page_type'] = $this->facebookCheckPageType($url);
        if (
            $this->info['page_type']['type'] == 'posts' ||
            $this->info['page_type']['type'] == 'events' ||
            $this->info['page_type']['type'] == 'videos' ||
            $this->info['page_type']['type'] == 'pages'
        ){
            \Log::info('URLInfo: get facebook profileInfo .. not profile');
            return $this->info;
        }
        $profile = $htmlContent;

        $profile['body'] = array_key_exists('body', $htmlContent) ? $htmlContent['body'] : '';

        if (empty($htmlContent)) {
            $profile = $this->curl->curl_content(
                $request_url,
                [
                    'timeout' => 30,
                ]
            );
            if (is_string($profile)) {
                $text = $profile;
                $profile = [];
                $profile['body'] = $text;
            }
        }
        if ((isset($profile['error_no']) && $profile['error_no']) || empty($profile['body'])) {
            \Log::info('URLInfo: getProfileInfo ... 404 Profile not found');
            return array_merge(
                $this->info,
                array('status' => false, 'page_type' => ['type' => 404])
            );
        }

        // Return profile with this->info ..
        $this->info['profile'] = $profile;
        // check facebook page type : for pages and profile and 404  ..
        $this->info['page_type'] = $this->facebookCheckPageType($url, $profile['body']);

        if ($this->info['page_type']['type'] == 'pages' ||
            $this->info['page_type']['type'] == '404'
        ) {
            \Log::info('URLInfo: getProfileInfo ... 404 Profile not found');
            return $this->info;
        }

        // Get name from profile ..
        $this->info['name'] = html_entity_decode($this->getName($profile), ENT_QUOTES);

        // Get Location from profile ..
        $this->info['location'] = $this->getLocation($profile);

        // Get Profile URL from profile
        $this->info['profileUrl'] = $this->getProfileURL($profile);

        // Get education
        $this->info['school'] = $this->getSchool($profile);

        // Get experience
        $this->info['work'] = $this->getWork($profile);

        // get profile id
        $this->info['profile_id'] = $this->getProfileId($profile);

        // Get profile pic
        $this->info['image'] = $this->getImage($profile);

        $this->info['links'] = $this->getLinks($profile);

        $this->info['emails'] = $this->getEmails($profile);


        return $this->info;
    }

    private function getName(array $profile)
    {
        \Log::info('URLInfo: getProfileInfo getting profile name');
        preg_match('/<title>([^]*[^<]*)<\/title/i', $profile["body"], $realname);
        if (count($realname) > 0){
            // TODO
            // SearchApis::logData($person['id'], "Checking name of FB profile $url \n", $comb);

            // to remove facebook title from name ex: Rob douglas | FACEBOOK
            $_name = explode('|', $realname[1]);
            // filter to remove notification count from title like -> (2) Rob douglas
            preg_match('/(\s*\([\s\d\s]*\)\s*)?([^\|]*)/i', $_name[0], $name);
            \Log::info('URLInfo: getProfileInfo got profile name: ' . $name[2]);
            return trim(htmlspecialchars_decode($name[2], ENT_QUOTES));
        }
        \Log::info('URLInfo: getProfileInfo getting no name found');
        return "";
    }

    private function getProfileId(array $profile)
    {
        \Log::info('URLInfo: getProfileInfo getting profile id');
        $pattern = '/entity_id[\'"]\s*:\s*[\'"]?(\d*)/i';
        preg_match($pattern, $profile['body'], $id);
        if (empty($id[1])) {
            $pattern = '/[\'"]token[\'"]\s*:\s*[\'"](\d*):/i';
            preg_match($pattern, $profile['body'], $id);
            return $id[1]??null;
        }
        return $id[1];
    }

    private function getLocation(array $profile)
    {
        \Log::info('URLInfo: getProfileInfo getting profile location');
        $result = [];
        $profilehtml = NULL;
        if (strripos($profile['body'], 'timelineBody') !== false) {
            $profilehtml = HtmlDomParser::str_get_html($profile['body']);
            $profilehtml = $profilehtml->find('div#timelineBody', 0);
        }

        if (count($profilehtml) > 0) {
            $location_html = $profilehtml->find('div#living', 0);
            if (count($location_html) > 0) {
                @$location_html = $location_html->innertext;
                $status = preg_match_all('/<h4([^>]*?)>([^<]*?)<\/h4><h4([^>]+)>(Current\s*City|Hometown|Home\s*Town)/s', $location_html, $extractLocations);
                if (!empty($extractLocations[2])) {
                    $countLocation = count($extractLocations);
                    \Log::info("URLInfo Found {$countLocation} location(s) match of FB profile");
                    @list($result['livesin'], $result['hometown']) = $extractLocations[2];
                    \Log::info("Home:{$result['hometown']}, Lives:{$result['livesin']} of FB profile");
                }
                $status = preg_match_all('/<h4([^>]*?)>([^<]*?)<\/h4><h4([^>]+)>(?!(Current\s*City|Hometown|Home\s*Town))/s', $location_html, $extractLocations);
                if (!empty($extractLocations[2])) {
                    $countLocation = count($extractLocations);
                    \Log::info("Found {$countLocation} other location(s) match of FB profile");

                    $result = array_merge($extractLocations[2], $result);
                }
            }
        }
        return $result;
    }

    private function getProfileURL(array $profile)
    {
        \Log::info('URLInfo: getProfileInfo getting profile url');
        $profilehtml = NULL;
        if (strripos($profile['body'], 'timelineBody') !== false) {
            $profilehtml = HtmlDomParser::str_get_html($profile['body']);
            $profilehtml = $profilehtml->find('div#timelineBody', 0);
        }

        if (count($profilehtml) > 0) {
            $location_html = $profilehtml->find('div#contact-info', 0);
            @$url_html = $location_html->innertext;
            $status = preg_match('/title\s*=[\'"]Facebook[^\/]*([^<]*).*?Facebook/i', $url_html,$match);
            if (!empty($match[1])) {
                return 'https://www.facebook.com' . trim($match[1]);
            }
        }
        return "";
    }

    private function getSchool(array $profile)
    {
        \Log::info('URLInfo: getProfileInfo getting profile schools');
        $educationDiv = $this->extractMainDiv($profile["body"] , 'education');
        return $this->extractEduExp($educationDiv, "edu");
    }

    private function getWork(array $profile)
    {
        \Log::info('URLInfo: getProfileInfo getting profile work');
        $experienceDiv = $this->extractMainDiv($profile["body"], 'work');
        return $this->extractEduExp($experienceDiv, "exp");
    }

    private function getFacebookAboutPage(string $profile)
    {
        if (preg_match('/facebook.com\/(\d*)(\/|$)/i', $profile, $match)) {
            if (!empty($match[1])) {
                $profile = 'https://www.facebook.com/profile.php?id=' . $match[1];
            }
        }
        $url = $profile;
        if (strpos($profile, "www")) {
            $url = str_replace("/www", "/m", $profile);
        } elseif (!strpos($profile, "m.facebook")) {
            $url = str_replace("facebook", "m.facebook", $profile);
        }
        if (!strpos($url, "about") && strpos($url,"profile.php")===false) {
            $url .= "/about";
        }
        if (!strpos($url, "//")) {
            str_replace('//', '/', $url);
        }

        if (strpos($url,"profile.php")) {
            $url = str_replace ("profile.php?", "profile.php?v=info&", $url);
        }
        return $url;
    }

    private function facebookCheckPageType($url, $contentPage = null)
    {
        $page_type = [
            'results_page_type_id'=>null,
            'type' => null,
        ];
        $page_type_profile = 'profile';
        $page_type_posts = 'posts';
        $page_type_events = 'events';
        $page_type_pages = 'pages';
        $page_type_photo = 'photo';
        $page_type_videos = 'videos';
        $page_type_about = 'about';
        $page_type_friends = 'friends';
        $page_type_likes = 'likes';
        $page_type_404 = '404';

        /* results_page_type_id for all types via such as following :
          -facebook profile => 1
          -facebook pages => 2
          -facebook posts => 3
          -facebook events => 4
          -facebook videos => 5
          -facebook about => 6
          -facebook friends => 7
          -facebook photo => 8
        */

        if (
            preg_match("/people\/[^\/]*\/(\d+)($|\/)/i", $url) ||
            stripos($url, 'profile.php') !== false
        ){
            $page_type['type'] = $page_type_profile;
            $page_type['results_page_type_id'] = 1;
        } elseif (stripos($url, '/' . $page_type_posts)) {
            $page_type['type'] = $page_type_posts;
            $page_type['results_page_type_id'] = 3;
        } elseif (stripos($url, '/' . $page_type_events)) {
            $page_type['type'] = $page_type_events;
            $page_type['results_page_type_id'] = 4;
        } elseif (stripos($url, '/' . $page_type_pages)) {
            $page_type['type'] = $page_type_pages;
            $page_type['results_page_type_id'] = 2;
        } elseif (stripos($url, '/' . $page_type_photo)) {
            $page_type['type'] = $page_type_photo;
            $page_type['results_page_type_id'] = 8;
        } elseif (stripos($url, '/' . $page_type_videos)) {
            $page_type['type'] = $page_type_videos;
            $page_type['results_page_type_id'] = 5;
        } elseif(stripos($url, '/' . $page_type_about)) {
            $page_type['type'] = $page_type_about;
            $page_type['results_page_type_id'] = 6;
        } elseif (stripos($url, '/' . $page_type_friends)) {
            $page_type['type'] = $page_type_friends;
            $page_type['results_page_type_id'] = 7;
        } elseif (stripos($url, '/' . $page_type_likes)) {
            $page_type['type'] = $page_type_likes;
            $page_type['results_page_type_id'] = 9;
        }

        // check on content feacebook page ..
        if ($contentPage) {
            // profile ..
            if (strpos($contentPage,'fb-timeline-cover-name')) {
                $page_type['type'] = $page_type_profile;
                $page_type['results_page_type_id'] = 1 ;
            }

            if (strpos($contentPage,'profile-card')) {
                $page_type['type'] = $page_type_profile;
                $page_type['results_page_type_id'] = 1 ;
            }
            // if page not found on facebook ..
            if (strpos($contentPage,'help/?ref=404')) {
                $page_type['type'] = $page_type_404;
                $page_type['results_page_type_id'] = 5 ;
            }

            // last Case for fan page facebook ..
            // [TIMELINE_PAGES, pagesTimelineLayout]
            // [Alternative] --> if($x = strpos($contentPage,'TIMELINE_PAGES')){
            if (
                strpos($contentPage, 'pagesTimelineLayout') &&
                strpos($contentPage, 'fb://page/?id=') !== false
            ) {
                $page_type['type'] = $page_type_pages;
                $page_type['results_page_type_id'] = 2 ;
            }
            // Mobile version / about page
            if (strpos($contentPage, 'msite-pages-header-contents') !== false) {

                $page_type['type'] = $page_type_pages;
                $page_type['results_page_type_id'] = 2 ;
            }
        }
        return $page_type;
    }

    private function extractMainDiv($html, $key)
    {
        $pattern = '#<div\b[^>]*?\bid\s*+=\s*+([\'"]?+)\b' .$key. '\b(?(1)\1)[^>]*+>((?:[^<]++| <(?!\/?div\b| !--)| <!--.*?-->| <div\b[^>]*+>(?2)<\/div\s*>)*+)<\/div\s*>#isx';
        $matchCount = preg_match($pattern, $html, $match);
        if(isset($match[0])){
            return $match[0];
        }
        return "";
    }

    private function extractEduExp($mainDiv, $type)
    {
        $pattern = '/<div\b[^>]*?\bclass\s*+=\s*+([\'"]?+)_5cds\s*+_2lcw(?(1)\1)[^>]*?>((?:[^<]++| <(?!\/?div\b| !--)| <!--.*?-->| <div\b[^>]*+>(?2)<\/div\s*>)*+)<\/div>/isx';
        $matchCounts = preg_match_all($pattern , $mainDiv, $matches);
        if (isset($matches[2])) {
            $divs = $matches[2];
            $education = [];
            foreach ($divs as $key => $value) {
               $div = $value;
               $index = [];

               if ($type == "edu") {
                    $index['school'] = "";
                    $index['degree'] = "";
               } else {
                    $index['position'] = "";
                    $index['company'] = "";
               }

               $index["start_date"] = "";
               $index["end_date"] = "";

               $index["image"] = $this->getImage(['body' => $div]);
               $div = str_replace('<span aria-hidden="true"> · </span>', ' - ', $div);
               $pattern = '/<div\b[^>]*?\bclass\s*+=\s*+([\'"]*+)_2pir\s*+c(?(1)\1)[^>]*?>((?:[^<]++| <(?!\/?div\b| !--)| <!--.*?-->| <div\b[^>]*+>(?2)<\/div\s*>)*+)<\/div>/isx';
               preg_match($pattern, $div, $matches);
               if (isset($matches[2])) {
                    $pattern = '/<span\b[^>]*>((?:[^<]++| <(?!\/?span\b| !--)| <!--.*?-->| <([span|a])\b[^>]*+>(.*?)<\/(?(1)\1)\s*>)*+)<\/span>/isx';
                    $counts = preg_match_all($pattern, $matches[2], $values, PREG_SET_ORDER);
                    foreach ($values as $i => $val) {
                        $anchor = (isset($val[2])) ? $val[2] : $val[1];
                        if ($i == 0) {
                            $val=  preg_match('/<a\b[^>]*?>(.*?)<\/a>/', $anchor, $x);
                            if (isset($x[1])) {
                                $s = ($type == "edu") ? "school" :"company";
                                $index[$s] = trim(htmlspecialchars_decode($x[1], ENT_QUOTES));
                            }
                        } elseif ($i == 1 && !stripos($val[0], '_52j9')) {
                            if (preg_match('/(\d{4})/ism', $anchor)) {
                                $date = explode("-", $anchor);
                                if (count($date) == 2) {
                                    $start_date = $this->normalizeDate($date[0]);
                                    $end_date = $this->normalizeDate($date[1]);
                                    if ($start_date || $end_date) {
                                        $index["start_date"] = $start_date;
                                        $index["end_date"] = $end_date;
                                    }
                                }
                            } else {
                                $s = ($type == "edu") ? "degree" : "position";
                                $index[$s] = html_entity_decode($anchor);
                            }
                        } elseif ($i == 2 || stripos($val[0], '_52j9')) {
                            $date = explode("-", $anchor);
                            if (count($date) == 2) {
                                $start_date = $this->normalizeDate($date[0]);
                                $end_date = $this->normalizeDate($date[1]);
                                if ($start_date || $end_date) {
                                    $index["start_date"] = $start_date;
                                    $index["end_date"] = $end_date;
                                }                            } elseif (count($date) == 1) {
                                preg_match('/([\d]*)$/', $date[0], $match);
                                $end_date = $this->normalizeDate($match[1]);
                                if ($end_date) {
                                    $index["end_date"] = $end_date;
                                }
                            }
                        }
                    }
                }
                $index["start_date"] = preg_replace('#(.*\D)?(\d+)$#', '\\2', $index["start_date"]);
                $index["end_date"] = preg_replace('#(.*\D)?(\d+)$#', '\\2', $index["end_date"]);
                if ($type == 'edu') {
                    if ($index['school'] !== '') {
                        array_push($education, $index);
                    }
                } elseif ($type == 'exp') {
                    if ($index['company'] !== '') {
                        array_push($education, $index);
                    }
                }
            }
        }
        return $education;
    }

    private function getImage(array $profile)
    {
        \Log::info('URLInfo: getProfileInfo getting profile image');
        if (!$this->info['profile_id']) {
            return;
        }

        $url = "https://graph.facebook.com/{$this->info['profile_id']}/picture?type=large";
        try {
            $entry = loadService('HttpRequestsService');
            $response = $entry->fetch($url, 'GET', ['allow_redirects' => false]);
            return $response->getHeader('location')[0];
        } catch (\Exception $ex) {
            return;
        }
        // $pattern = '#<i\b[^>]*?\bstyle\s*+=\s*+([\'"]?+)\s*+\b[^>]*?\bbackground\s*+:\s*+\b[^>]*?\burl\(&quot;(.*?)&quot;\)\s*+\b[^>]*(?(1)\1)><\/i>#isx';
        $image = preg_match($pattern, $profile['body'], $matches);
        if(isset($matches[2])){
            $image = html_entity_decode($matches[2]);
            $image = str_ireplace(array('\3a ', '\3d ', '\26 '), array(':','=','&'), $image);
        }
        return $image;
    }

    private function getLinks(array $profile)
    {
        \Log::info('URLInfo: getProfileInfo getting profile links');
        $links = [];
        $content = $profile['body'];

        $re = '/title="Instagram"><table.*?class="dm">(.*?)<\/div>/i';
        if (preg_match($re, $content, $match)) {
            $links[] = 'https://www.instagram.com/'.$match[1];
        }
        $re = '/title="Instagram">.*?<div class="_5cdv r">([^<]+)<\/div>/i';
        if (preg_match($re, $content, $match)) {
            $links[] = 'https://www.instagram.com/'.$match[1];
        }

        return $links;
    }

    public  function getEmails($profile)
    {
        \Log::info('URLInfo: getProfileInfo getting profile emails');
        $emails = [];

        $content = $profile['body'];

        $re = '/title="Email".*?href="mailto:([^"]+)/i';
        if (preg_match($re, $content, $match)) {
            $emails[] = urldecode($match[1]);
        }

        return $emails;
    }

    protected function normalizeDate($date)
    {
        $date = trim($date);

        $dateParts = explode("-", $date);
        if (count($dateParts)<2) {
            $date = preg_replace('#(.*\D)?(\d+)$#', '\\2', $date);
        } else {
            $date = $dateParts[0];
        }

        if (!is_numeric($date) || $date<1900 || $date>2050) {
            $date = "";
        }

        return $date;
    }
}
