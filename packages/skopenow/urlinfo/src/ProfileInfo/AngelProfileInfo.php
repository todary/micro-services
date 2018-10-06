<?php

namespace Skopenow\UrlInfo\ProfileInfo;

use Skopenow\UrlInfo\Interfaces\ProfileInfoInterface;
use Skopenow\UrlInfo\UrlInfo\{Profile, CURL};
use Skopenow\UrlInfo\ProfileInfo;

class AngelProfileInfo implements ProfileInfoInterface
{
    private $info;

    public function __construct(CURL $curl)
    {
        $this->curl = $curl;
        $this->info = (new ProfileInfo)->info;
    }

    public function getProfileInfo(string $url, array $htmlContent)
    {
        \Log::info('URLInfo: getProfileInfo from Angel, URL: ' . $url);
        if (empty($htmlContent)) {
            $htmlContent = $this->curl->curl_content($url);
        }

        $content = $htmlContent['body'];
        //$this->info['profile_url'] = $htmlContent['header']['url'];
        $this->info['profileUrl'] = $url;

        $this->info['profile'] = $htmlContent;

        $re = '/class=\'u-fontSize25 .*?>([^<]+)/';
        if (preg_match($re, $content, $match)) {
            \Log::info('URLInfo: getProfileInfo ... got Name: ' . trim($match[1]));
            $this->info['name'] = trim($match[1]);
        } else {
            \Log::info('URLInfo: getProfileInfo ... no name found in this profile');
        }

        $re = '/(?<=fontello-location icon\'>)(.*?)(?=class=\'s-vgRight0_5|class=\'u-inlineBlock\')/s';
        if (preg_match($re, $content, $match)) {
            $location = strip_tags($match[1]);
            \Log::info('URLInfo: getProfileInfo ... got location: ' . $location);
            $this->info['location'][] = trim($location);
        } else {
            \Log::info('URLInfo: getProfileInfo ... no Location found in this profile');
        }

        $re = '/class="js-avatar-img".*?src="([^"]+)"/s';
        if (preg_match($re, $content, $match)) {
            \Log::info('URLInfo: getProfileInfo ... got Image: ' . $match[1]);
            $this->info['image'] = $match[1];
        } else {
            \Log::info('URLInfo: getProfileInfo ... no Image found in this profile');
        }

        $re = '/<div data\-taggings="(.*?)" class="/';
        if (preg_match_all($re, $content, $match)) {
            $json = str_ireplace("&quot;", '"', $match[1]);
            $json = str_ireplace("&ququot;", '"', $json);
            $arr = json_decode($json[0], true);

            if (!empty($arr)) {
                foreach ($arr as $schoolData) {
                    if (isset($schoolData['new_tag']['display_name'])
                        && !empty($schoolData['new_tag']['display_name'])
                    ) {
                        $education = [
                            "image" => "",
                            "name" => "",
                            "end_date" => "",
                            "degree" => "",
                        ];

                        $school = trim($schoolData['new_tag']['display_name']);
                        $education["school"] = trim($school, ".");

                        if (isset($schoolData['new_tag']['logo_src'])
                            && !empty($schoolData['new_tag']['logo_src'])
                        ) {
                            $education["image"] = $schoolData['new_tag']['logo_src'];
                        }

                        if (isset($schoolData['new_tag']['graduation_year'])
                            && !empty($schoolData['new_tag']['graduation_year'])
                        ) {
                            $education["end_date"] = $schoolData['new_tag']['graduation_year'];
                        }

                        if (isset($schoolData['new_tag']['degree_name'])
                            && !empty($schoolData['new_tag']['degree_name'])
                        ) {
                            $education["degree"] = $schoolData['new_tag']['degree_name'];
                        }
                    }

                    $this->info["school"][] = $education;
                }
                \Log::info('URLInfo: getProfileInfo ... got Schools: ', $this->info["school"]);
            }
        } else {
            \Log::info('URLInfo: getProfileInfo ... no Schools found in this profile');
        }

        $re = "/<div class='section profile-module'><div data-roles=\"(.*?)\" data-source=/";
        if (preg_match_all($re, $content, $match)) {
            $json = str_ireplace("&quot;", '"', $match[1]);
            $json = str_ireplace("&ququot;", '"', $json);
            $arr = json_decode($json[0], true);
            //dd($arr);
            if (!empty($arr)) {
                foreach ($arr as $workExp) {
                    $work = [
                        "type" => "",
                        "company" => "",
                        "image" => '',
                        "title" => "",
                        "start_date" => "",
                        "end_date" => ""
                    ];

                    if (isset($workExp["title"])) {
                        $work["title"] = $workExp["title"];
                    }

                    if (isset($workExp["startup_company_name"])) {
                        $work["company"] = $workExp["startup_company_name"];
                    }

                    if (isset($workExp["startup_avatar"])) {
                        $work["image"] = $workExp["startup_avatar"];
                    }

                    if (isset($workExp["dates_for_select"]["started_at"])) {
                        $work["start_date"] = $workExp["dates_for_select"]["started_at"]["year"];
                    }

                    if (isset($workExp["dates_for_select"]["ended_at"])) {
                        $work["end_date"] = $workExp["dates_for_select"]["ended_at"]["year"];
                    }

                    $this->info["work"][] = $work ;
                }
                \Log::info('URLInfo: getProfileInfo ... got work: ', $this->info["work"]);
            }
        } else {
            \Log::info('URLInfo: getProfileInfo ... no Work found in this profile');
        }

        $re = '/<script type="application\/ld\+json">(.*?)<\/script>/';
        if (preg_match($re, $htmlContent['body'], $matches)) {
            $json = str_ireplace("&quot;", '"', $matches[1]);
            $arr = json_decode($json, true);
            if (isset($arr["sameAs"])) {
                $this->info['links'][] = $arr["sameAs"];
            }

            if (!empty($arr["url"])) {
                $this->info['links'][] = $arr["url"];
            }
        }
        return $this->info;
    }
}
