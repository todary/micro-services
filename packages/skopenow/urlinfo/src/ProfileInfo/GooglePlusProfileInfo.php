<?php

namespace Skopenow\UrlInfo\ProfileInfo;

use Skopenow\UrlInfo\Interfaces\ProfileInfoInterface;
use Skopenow\UrlInfo\ProfileInfo;
use Skopenow\UrlInfo\UrlInfo\CURL;


class GooglePlusProfileInfo implements ProfileInfoInterface
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
        \Log::info('URLInfo: getProfileInfo from googleplus');
        $suburl = parse_url($url, PHP_URL_PATH);
        $user_id = trim($suburl, "/");

        $username = $user_id;

        if ($user_id) {
            if (empty($htmlContent)) {
                list($url, $method, $curl_options) = $this->getCURLOptions($url);
                if (empty($curl_options)) {
                    return $this->info;
                }
                $profile = [];
                try {
                    $entry = loadService('HttpRequestsService');
                    $response = $entry->fetch($url, $method, $curl_options);
                    $response->getBody()->rewind();
                    $profile['body'] = $response->getBody()->getContents();
                    $response->getBody()->rewind();
                } catch (\Exception $ex) {
                    $profile['body'] = "";
                }

            } else {
                $profile = $htmlContent;
            }


            $results = [];
            $content = $profile['body'];
            $re = '/({\\"64399324.+})/s';

            $is_match = preg_match($re, $profile['body'], $googleJSON);

            if (!$is_match) {
                return $this->info;
            }

            $googleJSON = json_decode($googleJSON[0], true);

            //get links
            $links = ["links" => [], "status" => false];

            $json_data = [];

            if (!isset($googleJSON[64399324])) {
                return $this->info;
            } elseif (isset($googleJSON[64399324]) && empty($googleJSON[64399324])) {
                return $this->info;
            }

            foreach ($googleJSON[64399324][2] as $key => $data ) {
                //get display name & image
                if (isset($data[16])) {
                    if(isset($data[16][1]) && !empty($data[16][1]))
                    {
                        $results['displayName'] = $data[16][1];
                    }

                    if(isset($data[16][2]) && !empty($data[16][2]))
                    {
                        $results['image']['url'] = $data[16][2];
                    }
                    continue;
                }


                // get contact data from json
                $contact = [];
                if (isset($data[17])) {
                    $contact = $data[17] ;

                    // get emails
                    if(isset($contact[2]) && !empty($contact[2])) {
                        foreach ($contact[2] as $email)
                        {
                            $results['emails'][] = $email[1];
                        }
                    }

                    //get phones
                    if (isset($contact[3]) && !empty($contact[3])) {
                        foreach ($contact[3] as $phone)
                        {
                            $results['phones'][] = $phone[1];
                        }
                    }

                    // get adresses
                    if (isset($contact[4]) && !empty($contact[4])) {
                        foreach ($contact[4] as $address)
                        {
                            $results['address'][] = $address[1];
                        }
                    }

                    continue;
                }

                // get work & education
                $work = [];
                if(isset($data[11])) {
                    $work = $data[11] ;

                    if (isset($work[4]) && !empty($work[4])) {
                        foreach ($work[4] as $work_data) {
                            $results['work'][] = [
                                'company' => trim($work_data[1]) ,
                                'position' => isset($work_data[3]) ?  trim($work_data[3])  : '' ,
                                'start_date' => isset($work_data[7]) && $work_data[7] !=  0 ? trim(date("Y", $work_data[7] / 1000 ))  : '' ,
                                'end_date' => isset($work_data[8]) && $work_data[8] != 0 ? trim(date("Y", $work_data[8] / 1000 )) : '' ,
                            ];
                        }
                    }

                    if (isset($work[5]) && !empty($work[5])) {
                        foreach ($work[5] as $education) {
                            $results['school'][] = [
                                'name' => trim($education[1]) ,
                                'degree' => isset($education[3]) ?  trim($education[3])  : '' ,
                                'start_date' => isset($education[7]) && $education[7] != 0 ? trim(date("Y", $education[7] / 1000 ))  : '' ,
                                'end_date' => isset($education[8]) && $education[8] != 0 ? trim(date("Y", $education[8] / 1000 )) : '' ,
                            ];
                        }
                    }
                    continue;
                }

                // get places lived in
                $placesLived = [];
                if (isset($data[12])) {
                    $placesLived = $data[12];
                    if(isset($placesLived[3]) && !empty($placesLived[3]))
                    {
                        foreach ($placesLived[3] as $location)
                        {
                            $results['placesLived'][] = $location[1];
                        }
                    }
                    continue;
                }


                if (isset($data[13])) {
                    $links_Temp = $data[13];
                    if(isset($links_Temp[3]) && !empty($links_Temp[3]))
                    {
                        $links['status'] = true;
                        foreach ($links_Temp[3] as $link)
                        {
                            $links['links'][] = $link[1];
                        }
                    }
                    continue;
                }
            }

            if (count($results)) {
                // Return profile with info ..
                $this->info['profile'] = $results;

                // Get name from profile
                if (isset($results["displayName"])) {
                    $this->info['name'] = $results["displayName"];
                }


                // Get Location from profile
                if (isset($results["placesLived"]) && count($results["placesLived"])) {
                    $placesFound=array();
                    foreach ($results["placesLived"] as $placeLived) {
                        $placesFound[] = $placeLived;
                    }
                    if (count($placesFound)) {
                        $this->info['location'] = $placesFound;
                    }
                }

                // Get Image
                $this->info['image'] = $results["image"]['url'];


                // Get All links
                if ($links['status']) {
                    $this->info['links'] = $links['links'];
                }
                if (!empty($profileId)) {
                    $this->info['links'][] = "https://picasaweb.google.com/" . $profileId ;
                } elseif (!empty($url)) {
                    $parsedUrl = parse_url($url,PHP_URL_PATH);
                    $profileId = trim($parsedUrl,"/");
                    if (!empty($profileId)) {
                        $this->info['links'][] = "https://picasaweb.google.com/" . $profileId ;
                    }
                }


                // get Emails
                if (!empty($results["emails"])) {
                    $this->info['emails'] = $results["emails"];
                }

                // get phones
                if (!empty($results["phones"])) {
                    $this->info['phones'] = $results["phones"];
                }

                // get education
                if (!empty($results["education"])) {
                    $this->info['school'] = $results["education"];
                }

                // get work
                if (
                    !empty($results["work"])) {
                    $this->info['work'] = $results["work"];
                }

                // get address
                if (!empty($results["address"])) {
                    $this->info['address'] = $results["address"];
                }
            }
        }
        return $this->info;
    }

    public function getCURLOptions(string $url) : array
    {
        $curl_options = [];
        preg_match('#google\.com\/[+]?((\w|\d|[\.\-_])+)[^\/\&]*$#i', $url, $match);

        if (isset($match[1])) {
            $username = !is_numeric($match[1]) ? '+' . $match[1] : $match[1];
        }

        $profileId = 0;

        if (!is_numeric($username)) {
            $url = "https://plus.google.com/{$username}?hl=en";
            $html = $this->curl->curl_content($url);
            if (isset($html['body'])) {
                $re = '/data-url=\\"\.*\/*(.+?)\\"/s';
                if (preg_match($re,$html['body'], $matches)) {
                    $profileId = $matches[1];
                } else {
                    return [];
                }
            }
        } else {
            $profileId = $username;
        }


        $post_data =
        [[[64399324,[(object)array("64399324"=>
        [null,null,[[null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,
        [[null,[2,null,$profileId]]]],[null,null,null,null,[[null,[2,null,$profileId]]]],
        [null,null,null,null,null,null,null,null,null,null,null,null,[[null,[2,null,$profileId]]]],
        [null,null,null,[[null,[2,null,$profileId]],5,null]],[null,null,null,null,null,null,null,null,
        null,null,null,null,null,null,null,null,[[null,[2,null,$profileId]]]],[null,null,null,null,
        null,null,null,null,null,null,[[null,[2,null,$profileId]]]],[null,null,null,null,null,null,
        null,null,null,null,null,[[null,[2,null,$profileId]]]],[null,null,null,null,null,null,null,null,
        [[null,[2,null,$profileId]]]],[null,[[null,[2,null,$profileId]],3]]],[null,[2,null,$profileId]],null]
        )],null,null,0]]];
        $curl_options['form_params'] = ["f.req" => json_encode($post_data)];
        $url = 'https://plus.google.com/_/PlusAppUi/data?username=' . $profileId;
        $method = 'POST';
        return [$url, $method, $curl_options];
    }
}
