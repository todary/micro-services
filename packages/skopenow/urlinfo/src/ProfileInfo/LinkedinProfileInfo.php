<?php

namespace Skopenow\UrlInfo\ProfileInfo;

use Skopenow\UrlInfo\UrlInfo\CURL;
use Skopenow\UrlInfo\Interfaces\ProfileInfoInterface;
use Skopenow\UrlInfo\ProfileInfo;

class LinkedinProfileInfo implements ProfileInfoInterface
{
    private $curl;
    private $info;

    public function __construct(CURL $curl)
    {
        $this->info = (new ProfileInfo)->info;
        $this->curl = $curl;
    }

    public function getProfileInfo(string $url, array $htmlContent)
    {
        \Log::info('URLInfo: getProfileInfo from Linkedin');
        $sess_id = "automation_sessions_linkedin";
        $publicIdentifier = false;
        $request_url = $url;

        $request_url = (strpos($request_url, '?') !== false) ? (str_replace('?', '?__sid=' . $sess_id . '&', $request_url)) : ($request_url . '?__sid=' . $sess_id);
        $req = $htmlContent;
        if (empty($htmlContent)) {
            $curl_options = [];
            $req = $this->curl->curl_content($request_url, $curl_options);
        }
        // dd($req);
        // linkedin new style json
        $pattern = '/(?={&quot;data&quot;:{&quot;patentView[^*]+profileView)(.+?[^<]+)/i';
        preg_match($pattern, $req['body'], $newStyle);
        if (
            isset($newStyle[1]) &&
            !empty($newStyle[1])
        ){
            // TODO
            // SearchApis::logData($person_id,"linkedin_infoProfile() get linkedInProfileJson .\n");
            $linkedProfileNewStyle = json_decode(html_entity_decode($newStyle[1]), true);
        }

        // TODO
        // get linked contact info [phoneNumber,email,twitter,facebook,websites]
        /*preg_match('/emailAddress&quot;:&quot;(.+?)&quot;/', $req['body'], $emailAddress);
        preg_match('/{&quot;number&quot;:&quot;(.+?)&quot/', $req['body'], $phoneNumber);
        preg_match_all('/},&quot;url&quot;:&quot;(.+?)&quot/', $req['body'], $webSites);
        preg_match('/],&quot;name&quot;:&quot;(.+?)&quot/', $req['body'], $twitterAccount);

        $this->info['phones'] = isset($phoneNumber[1]) ? [$phoneNumber[1]] : [];
        $this->info['emails'] = isset($emailAddress[1]) ? $emailAddress[1] : [];
        if (!empty($twitterAccount[1])) {
            $this->info['links'][] = 'http://twitter.com/' . $twitterAccount[1];
        }
        if (isset($webSites[1])) {
            foreach ($webSites[1] as $value){
                if(!empty($value))
                    $this->info['links'][] = $value;
            }
        }*/

        $mainProfileKey = "";
        $this->info['profileUrl'] = $url;
        // Get profileURL

        // TODO
        if (empty($req['header']['url'])) {
            $re = '/class="view-public-profile"\>(.*?)\<\//';
            if (preg_match($re, $req['body'], $match)) {
                $this->info['profileUrl'] = $match[1];
            } else {
                // for newLinkedin style
                if (isset($newStyle[1])) {
                    $publicIdentifier = explode('in/', $url);
                    $publicIdentifier = (!empty($publicIdentifier) && isset($publicIdentifier[1])) ? $publicIdentifier[1] : false;

                    if ($publicIdentifier) {
                        $this->info['profileUrl'] = $url;
                    } else {
                        $re = '/profiles\/(.*?)\/profileview","status"/i';
                        if (preg_match_all($re, $req['body'],$match)) {
                            $this->info['profileUrl'] = (!empty($match) && isset($match[1][1])) ? "http://linkedin.com/in/".$match[1][1] : '';
                        } else {
                            $this->info['profileUrl'] = '';
                            $publicIdentifier = false; //publicIdentifier in json
                        }
                    }
                }
            }
        }
        // dd($linkedProfileNewStyle);

        // $re = '/\"publicIdentifier\"\:\"(.*)\"\,\"picture\"/i';
        // preg_match_all($re, $linkedProfileNewStyle, $matches);
        // dd($matches);
        $re = '/https:\/\/www\.linkedin\.com\/in\/([\w\-\.]+)/';
        preg_match($re, $this->info['profileUrl'], $matches);
        $username = $matches[1]??'';
        $options = [
            'headers' => [
                'csrf-token' => '^!COOKIE!JSESSIONID^',
            ]
        ];
        $url = "https://www.linkedin.com/voyager/api/identity/profiles/{$username}/profileContactInfo?&t=".time();

        $content = $this->curl->curl_content($url, $options);

        $data = json_decode($content['body'], true);
        if (!empty($data['twitterHandles'])) {
            foreach ($data['twitterHandles'] as $twitter) {
                $this->info['links'][] = 'https://twitter.com/' . $twitter['name'];
            }
        }

        if (!empty($data['websites'])) {
            foreach ($data['websites'] as $website) {
                $this->info['links'][] = $website['url'];
            }
        }

        if (!empty($data['emailAddress'])) {
            $this->info['emails'] = $data['emailAddress']['id'];
        }

        if (!empty($data['phoneNumbers'])) {
            $this->info['phones'][] = $data['phoneNumbers']['number'];
        }

        $re = '/profileview","status":([^,]+)/si';
        if (preg_match($re, $req['body'], $match)) {
            $status_code = $match[1];
        }
        if (isset($status_code) && $status_code != 200) {
            // TODO
            // SearchApis::logData($person_id['id'],"Linkedin profile {$url} returnig (404) not found , return False.\n"  );
            return $this->info;
        }

        if (isset($req['error_no']) && $req['error_no']) {
            // || (isset($req['header']['http_code']) && $req['header']['http_code']= '404')
            return $this->info;
        }
        if (empty($req['body'])) {
            return $this->info;
        }

        // Get htmlProfile
        $this->info['status'] = true;

        // Get name ..
        $publicIdentifier = trim($publicIdentifier, "/");

        // $pattern = '/\"@graph\"\:\[{\"@type\":\"Person\",\"name\"\:\"(.*?)","/s';
        $pattern = '/({"data":{"patentView":"urn.*}]})/m';
        $replaced = str_replace('&quot;', '"', $req['body']);
        preg_match($pattern, $replaced, $match);
        if (isset($match[1])) {
            preg_match('/"firstName":\s*"(.*?)"/', $match[1], $firstName);
            $firstName = $firstName[1];
            preg_match('/"lastName":\s*"(.*?)"/', $match[1], $lastName);
            $lastName = $lastName[1];
            $this->info['name'] = html_entity_decode($firstName . ' ' . $lastName, ENT_QUOTES);
        } elseif (!empty($linkedProfileNewStyle['included'])) {
            // incase linkedin new style
            if ($publicIdentifier) {
                foreach ($linkedProfileNewStyle['included'] as $value) {
                    if (
                        isset($value['firstName'], $value['publicIdentifier']) &&
                        $value['publicIdentifier'] == urldecode($publicIdentifier)
                    ) {
                        $name = $value['firstName'] . ' ' . $value['lastName'];
                        $this->info['name'] = html_entity_decode($this->extractName($name), ENT_QUOTES);
                    } elseif (!empty($value['firstName']) &&
                        !empty($value['maidenName']) &&
                        !empty($value['lastName']) &&
                        isset($value['summary'])
                    ) {
                        $this->info['otherNames'][] = $this->extractName($value['firstName']." ".$value['maidenName']);
                        $this->info['otherNames'][] = $this->extractName($value['maidenName']." ".$value['lastName']);
                        $this->info['otherNames'][] = $this->extractName($value['firstName']." ".$value['maidenName']." " .$value['lastName']);

                    } else {
                        continue;
                    }
                }
                // TODO
                // SearchApis::logData($person_id['id'],"linkedInNewStyle==>linkedin_infoProfile() get name:{$this->info['name']}.\n"  );
            }
        }

        if (!empty($linkedProfileNewStyle['included'])) {
            foreach ($linkedProfileNewStyle['included'] as $data) {
                if (!array_key_exists('firstName', $data) && !array_key_exists('lastName', $data)) {
                    continue;
                }
                if (strpos($this->info['name'], $data['firstName']) !== false &&
                    strpos($this->info['name'], $data['lastName']) !== false &&
                    !empty($data['publicIdentifier'])
                ) {
                    $this->info['profileUrl'] = 'https://www.linkedin.com/in/' . $data['publicIdentifier'];
                }
            }
        }

        // Get Location ..
        $pattern = '#name\s*=\s*["\']location["\']\s*([^<]*)><?([^<]*)#i';
        preg_match($pattern, $req['body'], $match);
        if (isset($match[2])) {
            $splitHtmlChar = explode('>',$match[2]);
            $this->info['location'] = isset($splitHtmlChar[1]) ? $splitHtmlChar[1] : $splitHtmlChar[0];
            $this->info['location'] = [trim($this->info['location'])];
        } elseif (isset($newStyle[1])) {
            // incase linkedin new style
            $pattern = '#supportedLocales[^.]*?locationName&quot;:&quot;(.*?)&#i';
            preg_match($pattern, $newStyle[1], $location);
            $this->info['location'] = isset($location[1]) ? [$location[1]] : [];
            // TODO
            // SearchApis::logData(1,"linkedInNewStyle==>linkedin_infoProfile() get location:{$this->info['location']}.\n"  );
        } else {
            // TODO
            // SearchApis::logData(1,"linkedInNewStyle==>linkedin_infoProfile() Sorry, we can\'t get location.\n");
        }


        // Get profile picture ..
        $pattern = '#profile-picture(.*)src\s*=\s*["\'](.+?)["\']#i';
        preg_match($pattern, $req['body'], $picture);
        if ($picture && isset($picture[2])) {
            $this->info['image'] = $picture[2];
        } elseif(!empty($newStyle[1])) {
            // incase linkedin new style
            $pattern = '/croppedImage&quot;:&quot;(.*?)&quot;/i';
            preg_match($pattern, $newStyle[1], $matchImage);

            // $this->info['image'] = 'https://static.licdn.com/scds/common/u/images/themes/katy/ghosts/person/ghost_person_100x100_v1.png';
            if (isset($matchImage[1]) && !empty($matchImage[1])) {
                $this->info['image'] = 'https://media.licdn.com/mpr/mpr/shrinknp_400_400' . $matchImage[1];
            } /*elseif (preg_match('/&quot;(\/AAEAAQAAAAAAAA.*?.jpg)&quot/', $req['body'], $match)) {
                $this->info['image'] = 'https://media.licdn.com/media' . $match[1];
            }*/


            // TODO
            // SearchApis::logData($person_id,"linkedInNewStyle==>linkedin_infoProfile() get image: {$this->info['image']}.\n"  );
        }


        // Get Profile Link ..
        preg_match('#view-public-profile([^<])>([^<]*)#i', $req['body'], $linkedinProfile);

        if (isset($linkedinProfile[2])) {
            $url = $linkedinProfile[2];
        }
        if (!isset($linkedinProfile[2]) && stripos($url,'grp/home')) {
            return $this->info;
        }

        // TODO
        // SearchApis::logData($person_id['id'],"linkedInNewStyle==>linkedin_infoProfile() get profileURL by old way: {$this->info['linkProfile']}.\n"  );


        $this->info['work'] = $this->getPositions($req['body'], $newStyle);

        $educationOutput = $this->geEducationAndAge($req['body'], $newStyle);
        $this->info['school'] = $educationOutput['education'];
        $this->info['age'] = (int) $educationOutput['age'];

        $explodeUrl = explode('?', $url);

        if (!isset($linkedinProfile[2]) && !empty($explodeUrl[1])) {
            parse_str($explodeUrl[1], $output);
            if (!isset($output['id'])) {
                return $this->info;
            }
            $url = 'https://www.linkedin.com/profile/view?id=' . $output['id'];

            if (isset($output['authType']) && isset($output['authToken'])) {
                $url .= '&authType=' . $output['authType'] . '&authToken=' . $output['authToken'];
            }
        }

        return $this->info;

    }

    private function extractName($name)
    {
        $delimiters = [',', '-'];
        $ready = str_replace($delimiters, $delimiters[0], $name);
        $names = explode($delimiters[0], $ready);
        if (isset($t[0])) {
            return $t[0];
        }
        return $name;
    }

    private function getPositions($html, $newStyle)
    {
        if (!empty($newStyle)) {
            return $this->getPositionsNewStyle($newStyle);
        }

        $positions = [];

        preg_match_all("/\"experience-\\d+?-view[\"'].*?h4><a[^>]*>([^<]*)</is", $html, $position);
        preg_match_all("/\"experience-\\d+?-view\".*?<h5>.*?<a[^>]*>([^<]*)</is", $html, $company);
        preg_match_all("/\"experience-date-locale\">[^<]*?<time>([^<]*)<\\/time>[^<]*(<time>([^<]*)<\\/time>)?/is", $html, $startAndEnd);

        $count = count($position[1]);
        foreach ($positions as $position) {
            $positions[$i] = ['position'=>'', 'company'=>'', 'start_date'=>'', 'end_date'=>''];
            if (!empty($position[1][$i])) {
                $positions[$i]['position'] = htmlspecialchars_decode($position[1][$i], ENT_QUOTES);
            }
            if (!empty($company[1][$i])) {
                $positions[$i]['company'] = htmlspecialchars_decode($company[1][$i], ENT_QUOTES);
            }
            if (!empty($startAndEnd[1][$i])) {
                $positions[$i]['start_date'] = $startAndEnd[1][$i];
            }
            $positions[$i]['end_date'] = "Present";
            if (!empty($startAndEnd[3][$i])) {
                $positions[$i]['end_date'] = $startAndEnd[3][$i];
            }
        }
        return $positions;
    }

    private function getPositionsNewStyle($newStyle)
    {
        $positions = [];
        $pattern = "/companyName&quot;:&quot;(.*?)&quot;[^*]*?timePeriod&quot;:&quot;(.*?)&quot;[^*]*?title&quot;:&quot;(.*?)&/";

        preg_match_all($pattern, $newStyle[1], $companyPeriodPosition);
        array_shift($companyPeriodPosition);
        $countPositions = count($companyPeriodPosition[1]);
        if ($countPositions) {
            $countPositionsNum = 0;

            foreach ($companyPeriodPosition as $value) {
                for ($i = 0; $i < $countPositions; $i++)
                {
                    if (!empty($companyPeriodPosition[1][$countPositionsNum])) {
                        //to catch start&End Date for position
                        $startDateInPosition = $this->getStartEndPositionDate($companyPeriodPosition[1][$countPositionsNum],'startDate',$newStyle[1]);
                        $endDateInPosition = $this->getStartEndPositionDate($companyPeriodPosition[1][$countPositionsNum], 'endDate', $newStyle[1]);
                    }
                    $positions[] = [
                         'company' => (!empty($companyPeriodPosition[0][$countPositionsNum]))? htmlspecialchars_decode($companyPeriodPosition[0][$countPositionsNum], ENT_QUOTES) : '',
                         'position' => (!empty($companyPeriodPosition[2][$countPositionsNum]))? htmlspecialchars_decode($companyPeriodPosition[2][$countPositionsNum], ENT_QUOTES) : '',
                         'start_date' => $startDateInPosition,
                         'end_date' => $endDateInPosition
                    ];
                    $countPositionsNum++;
                }
                break;
            }
        }

        if (!empty($positions[0]['company'])) {
            // TODO
            // SearchApis::logData($person_id,"getPositions()=>.".print_r($positions,true)."\n"  );
        }
        return $positions;
    }

    private function getStartEndPositionDate($dateToken, $type, $newStyle)
    {
        $newStyle = str_replace('&quot;', '"', $newStyle);
        $json = json_decode($newStyle, true);
        $position = ['month' => '', 'year' => ''];
        if (is_array($json['included'])) {
            foreach ($json['included'] as $data) {
                if (array_key_exists('$id', $data) && $data['$id'] == $dateToken . ',' . $type) {
                    $position['month'] = $data['month']??'';
                    $position['year'] = $data['year'];
                }
            }
        }

        $pattern = "/{[^}]+" . str_replace("/","\\/", preg_quote($dateToken . "," . $type)) . "[^}]+}/";
        preg_match($pattern, $newStyle, $date);
        if ($type == 'endDate' && empty($position['year']) && empty($position['month'])) {
            return $DateInPosition = 'Present';
        }
        $PositionDate = ($date) ? json_decode(html_entity_decode($date[0]), true) : '';
        $month = isset($PositionDate['month']) ? date("F", mktime(0, 0, 0, $PositionDate['month'], 17)) : '';

        if (!empty($month)) {
            $months = array(
                    'jan'=>'january',
                    'feb'=>'february',
                    'mar'=>'march',
                    'apr'=>'april',
                    'may'=>'may',
                    'jun'=>'june',
                    'jul'=>'july',
                    'aug'=>'august',
                    'sep'=>'september',
                    'oct'=>'october',
                    'nov'=>'november',
                    'dec'=>'december'
                );
            $month = strtolower($month);
            if (in_array($month, $months)) {
                $key = array_search($month, $months);
                $month = $key;
                if ($key === false) {
                    $month = '';
                }
            }
        }
        if (!empty($month) && !empty($PositionDate['year'])) {
            return $month . ' ' . $PositionDate['year'];
        }

        return !empty($PositionDate['year']) ? $PositionDate['year'] : '';
    }

    private function geEducationAndAge($html, $newStyle)
    {
        if (!empty($newStyle[1])) {
            return $this->getEducationNewStyle($newStyle);
        }

        $return = ['education' => [], 'age' => ''];

        preg_match_all("/\"education-\\d+?-view[\"'].*?h4.*?<a[^>]*>([^<]*)</is", $html, $school);
        preg_match_all("/\"education-\\d+?-view\".*?<h5>.*?(<span[^>]*>([^<]*)<\\/span>)?(<span.*?<a[^>]*>([^<]*)<\\/a>)?([^<]*?<a[^>]*>([^<]*)<)?/is", $html, $degree,PREG_PATTERN_ORDER);
        preg_match_all("/\"education-date\">(<time>([^<]*)<\\/time>(<time> [^\\s]* ([^<]*)<\\/time>)?)?/is", $html, $startAndEnd);

        $count = count($school[1]);
        $age = 0;
        $earliestStartDate = 100000;
        for ($i=0; $i < $count; $i++) {
            $return['education'][$i] = [
                'school'=>'',
                'degree'=>'',
                'start_date'=>'',
                'end_date'=>'',
            ];
            $return['education'][$i]['degree'] = "";
            $return['education'][$i]['school'] = isset($school[1][$i]) ? $school[1][$i] : '';
            if (isset($degree[2]) && !empty($degree[2]) && isset($degree[2][$i])) {
                $return['education'][$i]['degree'] .= isset($degree[2][$i]) ? $degree[2][$i] : '';
            }
            if (isset($degree[2]) && !empty($degree[4]) && isset($degree[4][$i])) {
                $return['education'][$i]['degree'] .= isset($degree[4][$i]) ? $degree[4][$i] : '';
            }
            if (isset($degree[2]) && !empty($degree[6]) && isset($degree[6][$i])) {
                $return['education'][$i]['degree'] .= ", ".isset($degree[6][$i]) ? $degree[6][$i] : '';
            }
            $return['education'][$i]['degree'] = str_replace('&#39;', "'", trim($return['education'][$i]['degree'],","));

            if (!empty($startAndEnd[2]) && !empty($startAndEnd[2][$i])) {
                $return['education'][$i]['start_date'] .= $startAndEnd[2][$i];
            }
            if (!empty($startAndEnd[4]) && !empty($startAndEnd[4][$i])) {
                $return['education'][$i]['end_date'] .= $startAndEnd[4][$i];
            }

            if (
                !empty($return['education'][$i]['school']) &&
                !empty($return['education'][$i]['start_date']) &&
                stripos($return['education'][$i]['school'], 'high school') === false
            ) {
                $schoolDate = (int) $return['education'][$i]['start_date'];
                if($schoolDate < $earliestStartDate){
                    $earliestStartDate = $schoolDate;
                }
           }
        }

        if ($earliestStartDate != 0 && $earliestStartDate != 100000) {
            $return['age'] = (date('Y') - $earliestStartDate) + 18;
       }
        return $return;
    }

    private function getEducationNewStyle($profileNewStyleEncoded)
    {
        preg_match_all("/{[^}]+fieldOfStudy[^}]+}/", $profileNewStyleEncoded[1], $educations);
        $countEducations = count($educations[0]);

        $profileEducations = ['education' => [], 'age' => ''];
        if ($countEducations) {
            $countPositionsNum = 0;
            $lastEducatinYear  = [];

            foreach ($educations[0] as $value) {
                $educationsData = json_decode(html_entity_decode($value),true);

                if(!empty($educationsData['timePeriod'])) {
                    //to catch start&End Date for education
                    $startDate = $this->getStartEndPositionDate($educationsData['timePeriod'],'start_date',$profileNewStyleEncoded[1]);
                    $endDate = $this->getStartEndPositionDate($educationsData['timePeriod'], 'end_date', $profileNewStyleEncoded[1]);
                    if (!empty((int) $startDate)) {
                        $lastEducatinYear [] = $startDate;
                    }
                }
                $profileEducations['education'][] = [
                    'school' => isset($educationsData['schoolName']) ? $educationsData['schoolName'] : '',
                    'degree' => isset($educationsData['degreeName']) ? $educationsData['degreeName'] : '',
                    'start_date' => isset($startDate) ? $startDate : '',
                    'end_date' => isset($endDate) ? $endDate : ''
                ];
            }
            $maxLastEduacations = (count($lastEducatinYear) > 0) ? min($lastEducatinYear) : '';
            if (!empty($maxLastEduacations)) {
                $profileEducations['age'] = (date('Y') - $maxLastEduacations) + 18;
            }

            if (!empty($positions['education'][0])) {
                // TODO
                // SearchApis::logData($person_id,"getLinkedinEducation()=>.".print_r($positions['education'],true)."\n"  );
            }
        }
        return $profileEducations;
    }
}

