<?php

/**
 * Flickr search
 * @author Mostafa Ameen
 * @package Search
 * @subpackage Fetching
 */

namespace Skopenow\Search\Fetching\Fetchers;

use Skopenow\Search\Fetching\AbstractFetcher;
use Skopenow\Search\Models\SearchListInterface;
use Skopenow\Search\Models\SearchList;
use Skopenow\Search\Models\SearchResult;
use Skopenow\Search\Models\CriteriaInterface;
use App\DataTypes\Username;

class UsernamesFetcher extends AbstractFetcher
{
    /**
     * @const string The selected account for search
     */
    const MAIN_SOURCE_NAME = "usernames";

    public $availableProfileInfo = ['name'];

    protected $usernamesSources = [];

    public function __construct(CriteriaInterface $criteria)
    {
        parent::__construct($criteria);

        $this->usernamesSources = [
            [
                'main_source' => 'instagram',
                'request_url' => 'https://www.instagram.com/{username}/',
            ],
            [
                'main_source' => 'twitter',
                'request_url' => 'https://twitter.com/{username}',
            ],
            [
                'main_source' => 'pinterest',
                'request_url' => 'https://www.pinterest.com/{username}/',
                'request_allow_redirects' => true,
                'username_trials' => function ($username) {
                    $trials = array_unique([
                        $username,
                        str_replace('.', '_', $username),
                        str_replace('.', '', $username),
                    ]);

                    foreach ($trials as $trial) {
                        yield $trial;
                    }
                }, // Generator
            ],
            [
                'main_source' => 'myspace',
                'request_url' => 'https://myspace.com/{username}',
            ],
            [
                'main_source' => 'facebook',
                'request_url' => 'https://m.facebook.com/{username}/about',
                'request_allow_redirects' => true,
                'request_timeout' => 10, // seconds
                'request_connect_timeout' => 10, // seconds
                'request_allow_redirects' => false,
                'return_url' => 'https://www.facebook.com/{username}',
            ],
            [
                'main_source' => 'linkedin',
                'request_url' => 'https://www.linkedin.com/in/{username}/',
                'rejection_regex' => '/profileActions","status":403/',
                'request_allow_redirects' => true,
                'request_timeout' => 10, // seconds
                'request_connect_timeout' => 10, // seconds
            ],
            [
                'main_source' => 'youtube',
                'request_url' => 'https://www.youtube.com/user/{username}',
            ],
            [
                'main_source' => 'etsy',
                'request_url' => 'https://www.etsy.com/people/{username}',
            ],
            [
                'main_source' => 'disqus',
                'request_url' => 'https://disqus.com/by/{username}/',
            ],
            [
                'main_source' => 'imgur',
                'request_url' => 'http://imgur.com/user/{username}',
            ],
            [
                'main_source' => 'dribbble',
                'request_url' => 'https://dribbble.com/{username}',
            ],
            [
                'main_source' => 'foursquare',
                'request_url' => 'https://foursquare.com/{username}',
            ],
            [
                'main_source' => 'tripadvisor',
                'request_url' => 'https://www.tripadvisor.com/members/{username}',
            ],
            [
                'main_source' => 'vimeo',
                'request_url' => 'https://vimeo.com/{username}',
            ],
            [
                'main_source' => 'dailymotion',
                'request_url' => 'https://www.dailymotion.com/{username}',
            ],
            [
                'main_source' => 'soundcloud',
                'request_url' => 'https://soundcloud.com/{username}',
            ],
            [
                'main_source' => 'quora',
                'request_url' => 'https://www.quora.com/profile/{username}',
            ],
            [
                'main_source' => 'vine',
                'request_url' => 'https://vine.co/{username}',
            ],
            [
                'main_source' => 'medium',
                'request_url' => 'https://medium.com/@{username}',
            ],
            [
                'main_source' => 'behance',
                'request_url' => 'https://www.behance.net/{username}',
            ],
            [
                'main_source' => 'producthunt',
                'request_url' => 'https://www.producthunt.com/@{username}',
            ],
            [
                'main_source' => 'flipboard',
                'request_url' => 'https://flipboard.com/@{username}',
            ],
            [
                'main_source' => 'instructables',
                'request_url' => 'http://www.instructables.com/member/{username}/',
            ],
            [
                'main_source' => 'gravatar',
                'request_url' => 'http://en.gravatar.com/{username}',
            ],
            [
                'main_source' => 'keybase',
                'request_url' => 'https://keybase.io/{username}',
            ],
            [
                'main_source' => 'kongregate',
                'request_url' => 'http://www.kongregate.com/accounts/{username}',
            ],
            [
                'main_source' => 'scribd',
                'request_url' => 'https://www.scribd.com/{username}',
                'request_allow_redirects' => true,
            ],
            [
                'main_source' => '8tracks',
                'request_url' => 'https://8tracks.com/{username}',
                'rejection_regex' => "/This page has vanished/i",
            ],
            [
                'main_source' => '9gag',
                'request_url' => 'https://9gag.com/u/{username}',
            ],
            [
                'main_source' => 'drupal',
                'request_url' => 'https://www.drupal.org/u/{username}',
            ],
            [
                'main_source' => 'fiverr',
                'request_url' => 'https://www.fiverr.com/{username}',
            ],
            [
                'main_source' => 'wikipedia',
                'request_url' => 'https://en.wikipedia.org/wiki/{username}',
            ],
            [
                'main_source' => 'wired',
                'request_url' => 'http://insights.wired.com/profile/{username}',
            ],
            [
                'main_source' => 'wordpress',
                'request_url' => 'https://profiles.wordpress.org/{username}',
            ],
            [
                'main_source' => 'ustream.tv',
                'request_url' => 'http://www.ustream.tv/channel/{username}',
            ],
            [
                'main_source' => 'tunein',
                'request_url' => 'https://tunein.com/user/{username}/',
            ],
            [
                'main_source' => 'reddit',
                'request_url' => 'https://www.reddit.com/user/{username}',
                'rejection_regex' => "#\<p\s+id\s*=\s*['\"]noresults['\"]\s+class\s*=\s*['\"]error['\"]\>\s*there\s+doesn't\s+seem\s+to\s+be\s+anything\s+here\s*#is",
            ],
            [
                'main_source' => 'deviantart',
                'request_url' => 'https://{username}.deviantart.com',
            ],
            [
                'main_source' => 'PPC500px',
                'request_url' => 'https://500px.com/{username}',
            ],
            [
                'main_source' => 'github',
                'request_url' => 'https://github.com/{username}',
            ],
            [
                'main_source' => 'metacafe',
                'request_url' => 'http://www.metacafe.com/channels/{username}/',
                'rejection_regex' => "#is\s+temporarily\s+not\s+available\.\s+Please\s+come\s+back\s+later#is",
            ],
            [
                'main_source' => 'livejournal',
                'request_url' => 'https://{username}.livejournal.com',
            ],
            [
                'main_source' => 'tumblr',
                'request_url' => 'https://{username}.tumblr.com',
            ],
            [
                'main_source' => 'flickr',
                'request_url' => 'https://www.flickr.com/people/{username}',
            ],
            [
                'main_source' => 'steamcommunity',
                'request_url' => 'http://steamcommunity.com/id/{username}',
                'rejection_regex' => "#\<h3\>the\s+specified\s+profile\s+could\s+not\s+be\s+found\s*\.\<\/h3\>#i",
            ],
            [
                'main_source' => 'ebay',
                'request_url' => 'https://www.ebay.com/usr/{username}',
                'rejection_regex' => "#\<p\s+class\s*=\s*['\"]sm-md['\"]\>\s*the\s+user\s+id\s+you\s+entered\s+was\s+not\s+found\.\s+please\s+check\s+the\s+user\s+id\s+and\s+try\s+again\.#is",
            ],
            /* Timeout
            [
                'main_source' => 'xanga',
                'request_url' => 'http://{username}.xanga.com',
            ],
            */
            [
                'main_source' => 'about.me',
                'request_url' => 'https://about.me/{username}',
            ],
            [
                'main_source' => 'lifestream.aol',
                'request_url' => 'http://lifestream.aol.com/stream/{username}',
                'rejection_regex' => "#\<p\>\s*These\s+aren't\s+the\s+bunnies\s+you're\s+looking\s+for...\s*#is",
            ],
            [
                'main_source' => 'slideshare',
                'request_url' => 'https://www.slideshare.net/{username}',
            ],
            [
                'main_source' => 'hubpages',
                'request_url' => 'http://hubpages.com/@{username}',
            ],
            [
                'main_source' => 'twitch',
                'request_url' => 'https://api.twitch.tv/kraken/users/{username}?on_site=1',
                'rejection_regex' => "#\<p\>\s*The\s+page\s+could\s+not\s+be\s+found,\s+or\s+has\s+been\s+deleted\s+by\s+its\s+owner.\s*#is",
            ],
            [
                'main_source' => 'photobucket',
                'request_url' => 'http://s1257.photobucket.com/user/{username}/profile',
            ],
            [
                'main_source' => 'bitly',
                'request_url' => 'https://bitly.com/u/{username}',
                'rejection_regex' => "#\<p\>Uh\s+oh,\s+Bitly\s+couldn't\s+find\s+a\s+link\s+for\s+the\s+bitly\s+URL\s+you\s+clicked\s*\.\<\/p\>#i",
            ],
            [
                'main_source' => 'okcupid',
                'request_url' => 'http://www.okcupid.com/profile/{username}',
            ],
            [
                'main_source' => 'stumbleupon',
                'request_url' => 'https://www.stumbleupon.com/api/v2_0/user/{username}?version=2&f=',
                'return_url' => 'https://www.stumbleupon.com/stumbler/{username}',
            ],
            [
                'main_source' => 'last.fm',
                'request_url' => 'https://www.last.fm/user/{username}',
            ],
            [
                'main_source' => 'picsart',
                'request_url' => 'https://picsart.com/{username}',
                'rejection_regex' => "/<title>*?error*?<\/title>/i",
            ],
            [
                'main_source' => 'squarespace',
                'request_url' => 'http://{username}.squarespace.com',
            ],
            [
                'main_source' => 'yelp',
                'request_url' => 'http://{username}.yelp.com',
                'request_allow_redirects' => true,
                'acceptance_regex' => '/Profile Overview/'
            ],
            
            /* template
            [
                'source' => '',
                'main_source' => '',
                'type' => 'username({username})',
                'request_url' => 'https://{username}',
                'request_method' => 'GET',
                'request_post_data' => [],
                'request_headers' => [],
                'request_allow_redirects' => false,
                'request_timeout' => 5, // seconds
                'request_connect_timeout' => 5, // seconds
                'request_max_retries' => -1,
                'return_url' => 'https://{username}', // Omit to use request url
                'username_trials' => function ($username) {
                    yield $username;
                }, // Generator
                'username_regex' => null,
                'acceptance_regex' => null,
                'rejection_regex' => null,
            ],
            */
        ];
    }

    protected $responses = [];

    protected function prepareRequest()
    {
        $username = $this->criteria->username;

        $defaultInfo = [
            'source' => 'username',
            'main_source' => '',
            'type' => 'username({username})',
            'request_url' => 'https://{username}',
            'request_method' => 'GET',
            'request_post_data' => [],
            'request_headers' => [],
            'request_allow_redirects' => false,
            'request_timeout' => 5, // seconds
            'request_connect_timeout' => 5, // seconds
            'request_max_retries' => -1,
            'return_url' => '', // Omit to use request url
            'username_trials' => function ($username) {
                yield $username;
            }, // Generator
            'username_regex' => null,
            'acceptance_regex' => null,
            'rejection_regex' => null,
        ];

        //echo "Start : " . time() . "\n";
        $request = loadService('HttpRequestsService');
        foreach ($this->usernamesSources as $originalSourceInfo) {
            $originalSourceInfo += $defaultInfo;

            foreach ($originalSourceInfo['username_trials']($username) as $current_username) {
                $sourceInfo = $originalSourceInfo;
                $sourceInfo['request_url'] = str_replace('{username}', $current_username, $sourceInfo['request_url']);

                if (empty($sourceInfo['return_url'])) {
                    $sourceInfo['return_url'] = $sourceInfo['request_url'];
                } else {
                    $sourceInfo['return_url'] = str_replace('{username}', $current_username, $sourceInfo['return_url']);
                }

                /*
                $sourceInfo['request_url'] = 'http://slowwly.robertomurray.co.uk/delay/4000/url/' . $sourceInfo['request_url'] . '?' . time();
                $sourceInfo['request_allow_redirects'] = true;
                */

                if (!empty($sourceInfo['username_regex'])) {
                    if (!preg_match($info['username_regex'], $current_username)) {
                        continue;
                    }
                }

                $options = [];

                if (!empty($sourceInfo['request_headers'])) {
                    $options['headers'] = $sourceInfo['request_headers'] + $options['headers'];
                }


                $options['max_retries'] = $sourceInfo['request_max_retries'];
                $options['allow_redirects'] = $sourceInfo['request_allow_redirects'];
                $options['timeout'] = $sourceInfo['request_timeout'];
                $options['connect_timeout'] = $sourceInfo['request_connect_timeout'];
                if (!empty($sourceInfo['request_post_data'])) {
                    $options['form_params'] = $sourceInfo['request_post_data'];
                }
                $this->makeSourceRequest($request, $sourceInfo, $options);
            }
        }
        return $request;
    }

    protected function makeSourceRequest($request, array $sourceInfo, array $options)
    {
        $request->createRequest($sourceInfo['request_url'], null, $sourceInfo['request_method'], $options, function ($response) use ($sourceInfo) {

            \Log::info("Request succeeded: " . $sourceInfo['request_url']);

            //dump($sourceInfo['return_url'], $response, $response->getResponse()->getBody()->getContents());

            $this->responses []= [
                'info' => $sourceInfo,
                'fetchable' => $response,
            ];
        }, function ($err) use ($sourceInfo) {
            \Log::info("Request failed: " . $sourceInfo['request_url']);
        });
    }

    protected function makeRequest()
    {
        try {
            $this->request->processRequests();
            return $this->responses;
        } catch (\Exception $ex) {
            return [];
        }
    }

    /**
     * Parse the content of the search response
     * @param string $response The output of the HTTP request of the search URL
     * @return SearchListInterface
     */
    protected function processResponse($responses): SearchListInterface
    {
        $list = new SearchList(self::MAIN_SOURCE_NAME);
        $username = $this->criteria->username;

        foreach ($responses as $response) {
            $fetchable = $response['fetchable'];
            $sourceInfo = $response['info'];

            $response = $fetchable->getResponse();
            $statusCode = $response->getStatusCode();
            if ($statusCode<200 || $statusCode>=300) {
                //dd("Status", $sourceInfo['request_url'], $statusCode);
                continue;
            }

            $response->getBody()->rewind();
            $body = $response->getBody()->getContents();

            if (!empty($sourceInfo['rejection_regex'])) {
                if (preg_match($sourceInfo['rejection_regex'], $body)) {
                    //dd("rejection_regex");
                    continue;
                }
            }

            if (!empty($sourceInfo['acceptance_regex'])) {
                if (!preg_match($sourceInfo['acceptance_regex'], $body)) {
                    //dd("acceptance_regex");
                    continue;
                }
            }

            $url = $sourceInfo['return_url'];

            $result = new SearchResult($url);

            if ($body) {
                $entryPoint = loadService('UrlInfo');
                $info = $entryPoint->getProfileInfo($result->url, $result->mainSource, ['body'=>$body]);
                unset($info['profile']);

                if (!empty($info['name']) || !empty($info['username_as_name'])) {
                    $result->setProfileInfo($info);
                    if ($result->isProfile) {
                        $result->setUsername(Username::create(['username' => $this->criteria->username], $result->mainSource));
                    }
                    $list->addResult($result);
                }
            }

            // if ($this->onResultFound($result)) {
            // }
        }

        return $list;
    }
}
/*
        
        'https://www.facebook.com/'=>'facebook',
        'https://www.pinterest.com/'=>'pinterest',
        'https://myspace.com/'=>'myspace',
        'https://www.youtube.com/user/'=>'youtube',
        'https://www.etsy.com/people/'=>'etsy',
        'https://disqus.com/by/'=>'disqus',
        'http://imgur.com/user/'=>'imgur',
        'https://dribbble.com/'=>'dribbble',
        'https://foursquare.com/'=>'foursquare',
        'http://twitpic.com/photos/'=>'twitpic',
        'https://www.tripadvisor.com/members/'=>'tripadvisor',
        'https://vimeo.com/'=>'vimeo',
        'https://www.dailymotion.com/'=>'dailymotion',
        'https://soundcloud.com/'=>'soundcloud',
        //'http://picassaweb.com/'=>'picassaweb',
        'https://www.quora.com/'=>'quora',
        'https://vine.co/'=>'vine',
        'https://medium.com/@'=>'medium',
        'https://www.behance.net/'=>'behance',
        'https://www.producthunt.com/@'=>'producthunt',
        'https://flipboard.com/@'=>'flipboard',
        'http://www.instructables.com/member/'=>'instructables',
        'https://en.gravatar.com/'=>'gravatar',
        'https://keybase.io/'=>'keybase',
        'http://www.kongregate.com/accounts/'=>'kongregate',
        'https://www.scribd.com/'=>'scribd',
        'http://8tracks.com/'=>'8tracks',
        'http://9gag.com/u/'=>'9gag',
        'https://www.drupal.org/u/'=>'drupal',
        'https://www.fiverr.com/'=>'fiverr',
        'https://www.linkedin.com/in/'=>'linkedin',
        'https://path.com/i/'=>'path',
        'https://get.google.com/albumarchive/+'=>'picasaweb',
        'https://en.wikipedia.org/wiki/'=>'wikipedia',
        'http://insights.wired.com/profile/'=>'wired',
        'https://profiles.wordpress.org/'=>'wordpress',
        'https://ustream.tv/channel/'=>'ustream.tv',

        googleplus_query

                if ((($comb['additional'] && $comb['additional'] == "tunein") || !$comb['additional']) && $camefromSource != 'tunein' ) {
                    // Squarespace
                    $url = "http://tunein.com/user/{$username}";
                    $res_array = array(
                        "source" => "username",
                        "main_source" => "tunein",
                        "type" => 'username(' . $username . ')',
                        "content" => $url,
                        'combination_id' => $comb['id'],
                    );
                    Yii::app()->reportLog->resultFound($res_array, $person, $comb);
                    unset($res_array);
                    //$this->profile_exist($url, $username, $person, $comb, "tunein");
                    $profilesCheck[] = array($url, $username, $person, $comb, "tunein");
                }
                if ((($comb['additional'] && $comb['additional'] == "reddit") || !$comb['additional']) && $camefromSource != 'reddit') {
                    // reddit
                    $url = "https://www.reddit.com/user/{$username}";
                    $pattern = "#\<p\s+id\s*=\s*['\"]noresults['\"]\s+class\s*=\s*['\"]error['\"]\>\s*there\s+doesn't\s+seem\s+to\s+be\s+anything\s+here\s*#is";
                    $res_array = array(
                        "source" => "username",
                        "main_source" => "reddit",
                        "type" => 'username(' . $username . ')',
                        "content" => $url,
                        'combination_id' => $comb['id'],
                    );
                    Yii::app()->reportLog->resultFound($res_array, $person, $comb);
                    unset($res_array);
                    $profilesCheck[] = array($url, $username, $person, $comb, "reddit",$pattern);
                    //$this->profile_exist($url, $username, $person, $comb, "reddit",$pattern);
                }
                if ((($comb['additional'] && $comb['additional'] == "deviantart") || !$comb['additional']) && $camefromSource != 'deviantart') {
                    // deviantart
                    $url = "https://{$usernameForDomain}.deviantart.com";
                     $res_array = array(
                        "source" => "username",
                        "main_source" => "deviantart",
                        "type" => 'username(' . $usernameForDomain . ')',
                        "content" => $url,
                        'combination_id' => $comb['id'],
                    );
                    Yii::app()->reportLog->resultFound($res_array, $person, $comb);
                    unset($res_array);
                    $profilesCheck[] = array($url, $usernameForDomain, $person, $comb, "deviantart");
                   //$this->profile_exist($url, $usernameForDomain, $person, $comb, "deviantart");
                }
                if ((($comb['additional'] && $comb['additional'] == "500px") || !$comb['additional']) && $camefromSource != '500px') {
                    // 500px
                    $url = "https://500px.com/{$username}";
                     $res_array = array(
                        "source" => "username",
                        "main_source" => "PPC500px",
                        "type" => 'username(' . $username . ')',
                        "content" => $url,
                        'combination_id' => $comb['id'],
                    );
                    Yii::app()->reportLog->resultFound($res_array, $person, $comb);
                    unset($res_array);
                    $profilesCheck[] = array($url, $username, $person, $comb, "PPC500px");
                   // $this->profile_exist($url, $username, $person, $comb, "PPC500px");
                }
                if ((($comb['additional'] && $comb['additional'] == "github") || !$comb['additional'])&& $camefromSource != 'github') {
                    // github
                    $url = "https://github.com/{$username}";
                     $res_array = array(
                        "source" => "username",
                        "main_source" => "github",
                        "type" => 'username(' . $username . ')',
                        "content" => $url,
                        'combination_id' => $comb['id'],
                    );
                    Yii::app()->reportLog->resultFound($res_array, $person, $comb);
                    unset($res_array);
                    //$this->profile_exist($url, $username, $person, $comb, "github");
                    $status = $this->check_username_lookup($person, $comb, $url, $username, "github");
                    if ($status["name"] != 0 && $status["location"] != 0){
                        $profilesCheck[] = array($url, $username, $person, $comb, "github",false,false,array(),$status["image"]);
                        //$this->profile_exist($url, $username, $person, $comb, "github",false,false,array(),$status["image"]);
                    }else{
                        $deletedArray=array();
                        $deletedArray['is_deleted']="Yes";
                        $deletedArray['deleted_reason']="Not Matched";
                        $deletedArray['result_url']=$url;
                        Yii::app()->reportLog->resultDeleted($deletedArray, $person, $comb);
                        unset($deletedArray);
                    }
                }
                if ((($comb['additional'] && $comb['additional'] == "metacafe") || !$comb['additional'])&& $camefromSource != 'metacafe') {
                    // metacafe
                    $url = "http://www.metacafe.com/channels/{$username}";
                    //$pattern = "#\<p\>\s*We're\s+sorry,\s+but\s+the\s+requested\s+page\s+was\s+not\s+found.\s*#is";
                    $pattern = "#is\s+temporarily\s+not\s+available\.\s+Please\s+come\s+back\s+later#is";
                    $res_array = array(
                        "source" => "username",
                        "main_source" => "metacafe",
                        "type" => 'username(' . $username . ')',
                        "content" => $url,
                        'combination_id' => $comb['id'],
                    );
                    Yii::app()->reportLog->resultFound($res_array, $person, $comb);
                    unset($res_array);
                    $profilesCheck[] = array($url, $username, $person, $comb, "metacafe",$pattern);
                    //$this->profile_exist($url, $username, $person, $comb, "metacafe",$pattern);
                }
                if ((($comb['additional'] && $comb['additional'] == "livejournal") || !$comb['additional'])&& $camefromSource != 'livejournal') {
                    // livejournal
                    $url = "http://{$usernameForDomain}.livejournal.com/";
                    $res_array = array(
                        "source" => "username",
                        "main_source" => "livejournal",
                        "type" => 'username(' . $usernameForDomain . ')',
                        "content" => $url,
                        'combination_id' => $comb['id'],
                    );
                    Yii::app()->reportLog->resultFound($res_array, $person, $comb);
                    unset($res_array);
                    $profilesCheck[] = array($url, $usernameForDomain, $person, $comb, "livejournal");
                    //$this->profile_exist($url, $usernameForDomain, $person, $comb, "livejournal");
                }
                if ((($comb['additional'] && $comb['additional'] == "tumblr") || !$comb['additional'])&& $camefromSource != 'tumblr') {
                    // tumblr
                    $url = "http://{$usernameForDomain}.tumblr.com";
                    $res_array = array(
                        "source" => "username",
                        "main_source" => "tumblr",
                        "type" => 'username(' . $usernameForDomain . ')',
                        "content" => $url,
                        'combination_id' => $comb['id'],
                    );
                    Yii::app()->reportLog->resultFound($res_array, $person, $comb);
                    unset($res_array);
                    $profilesCheck[] = array($url, $usernameForDomain, $person, $comb, "tumblr");
                    //$this->profile_exist($url, $usernameForDomain, $person, $comb, "tumblr");
                }
                if ((($comb['additional'] && $comb['additional'] == "flickr") || !$comb['additional'])&& $camefromSource != 'flickr') {
                    // flickr
                    $url = "https://www.flickr.com/people/{$username}";
                    $res_array = array(
                        "source" => "username",
                        "main_source" => "flickr",
                        "type" => 'username(' . $username . ')',
                        "content" => $url,
                        'combination_id' => $comb['id'],
                    );
                    Yii::app()->reportLog->resultFound($res_array, $person, $comb);
                    unset($res_array);
                    //$this->profile_exist($url, $username, $person, $comb, "flickr");
                    $status = $this->check_username_lookup($person, $comb, $url, $username, "flickr");
                    if ($status["name"] != 0 && $status["location"] != 0){
                        $profilesCheck[] = array($url, $username, $person, $comb, "flickr",false,false,array(),$status["image"]);
                        //$this->profile_exist($url, $username, $person, $comb, "flickr",false,false,array(),$status["image"]);
                    }else{
                        $deletedArray=array();
                        $deletedArray['is_deleted']="Yes";
                        $deletedArray['deleted_reason']="Not Matched";
                        $deletedArray['result_url']=$url;
                        Yii::app()->reportLog->resultDeleted($deletedArray, $person, $comb);
                        unset($deletedArray);
                    }

                    ## add combination to search flickr by username .
                    ## Due to Task #10345 .
                    $t=microtime(true);
                    if (get_class(Yii::app())=="CConsoleApplication" and Yii::app()->params['runCommandLog']){global $_start_time; echo number_format(microtime(true)-$_start_time,3) . " " . ' 1 : Class : '.__CLASS__.', Function : '.__METHOD__.', Mission : pending: '. "adding combination flickr_by_username : ".$username." in person :\n". print_r($person,true).' reportLog->resultFound'.' Time : '.number_format(microtime(true)-$t,3).', File : '.__FILE__.', Line : '.__LINE__."<br>\n";debug_print_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);echo "\n\n";};
                    $t=microtime(true);
                    $search_url = "https://www.flickr.com/search/people/?username=".$username;
                    $comb_fields=array();
                    $comb_fields[1]=array("un" => $username);
                    SearchApis::store_combination(null,null,"flickr_by_username",$search_url,null,null,$comb_fields,$person);

                    if (get_class(Yii::app())=="CConsoleApplication" and Yii::app()->params['runCommandLog']){global $_start_time; echo number_format(microtime(true)-$_start_time,3) . " " . ' 1 : Class : '.__CLASS__.', Function : '.__METHOD__.', Mission : pending: '.print_r($comb_fields).' reportLog->resultFound'.' Time : '.number_format(microtime(true)-$t,3).', File : '.__FILE__.', Line : '.__LINE__."<br>\n";debug_print_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);echo "\n\n";};
                }
                if ((($comb['additional'] && $comb['additional'] == "steamcommunity") || !$comb['additional'])&& $camefromSource != 'steamcommunity') {
                    // steamcommunity
                    $url = "http://steamcommunity.com/id/{$username}";
                    $pattern = "#\<h3\>the\s+specified\s+profile\s+could\s+not\s+be\s+found\s*\.\<\/h3\>#i";
                    $res_array = array(
                        "source" => "username",
                        "main_source" => "steamcommunity",
                        "type" => 'username(' . $username . ')',
                        "content" => $url,
                        'combination_id' => $comb['id'],
                    );
                    Yii::app()->reportLog->resultFound($res_array, $person, $comb);
                    unset($res_array);
                    $profilesCheck[] = array($url, $username, $person, $comb, "steamcommunity", $pattern);
                    //$this->profile_exist($url, $username, $person, $comb, "steamcommunity", $pattern);
                }
                if ((($comb['additional'] && $comb['additional'] == "ebay") || !$comb['additional'])&& $camefromSource != 'ebay') {
                    // ebay
                    $url = "http://www.ebay.com/usr/{$username}";
                    $pattern = "#\<p\s+class\s*=\s*['\"]sm-md['\"]\>\s*the\s+user\s+id\s+you\s+entered\s+was\s+not\s+found\.\s+please\s+check\s+the\s+user\s+id\s+and\s+try\s+again\.#is";
                    $res_array = array(
                        "source" => "username",
                        "main_source" => "ebay",
                        "type" => 'username(' . $username . ')',
                        "content" => $url,
                        'combination_id' => $comb['id'],
                    );
                    Yii::app()->reportLog->resultFound($res_array, $person, $comb);
                    unset($res_array);
                    //$this->profile_exist($url, $username, $person, $comb, "ebay",$pattern);
                    $status = $this->check_username_lookup($person, $comb, $url, $username, "ebay");
                    if ($status["name"] != 0 && $status["location"] != 0){
                        $profilesCheck[] = array($url, $username, $person, $comb, "ebay",$pattern,false,array(),$status["image"]);
                        //$this->profile_exist($url, $username, $person, $comb, "ebay",$pattern,false,array(),$status["image"]);
                    }else{
                        $deletedArray=array();
                        $deletedArray['is_deleted']="Yes";
                        $deletedArray['deleted_reason']="Not Matched";
                        $deletedArray['result_url']=$url;
                        Yii::app()->reportLog->resultDeleted($deletedArray, $person, $comb);
                        unset($deletedArray);
                    }
                }
                if ((($comb['additional'] && $comb['additional'] == "xanga") || !$comb['additional'])&& $camefromSource != 'xanga') {
                    // xanga
                    $url = "http://{$usernameForDomain}.xanga.com";
                    $res_array = array(
                        "source" => "username",
                        "main_source" => "xanga",
                        "type" => 'username(' . $usernameForDomain . ')',
                        "content" => $url,
                        'combination_id' => $comb['id'],
                    );
                    Yii::app()->reportLog->resultFound($res_array, $person, $comb);
                    unset($res_array);
                    $profilesCheck[] = array($url, $usernameForDomain, $person, $comb, "xanga");
                    //$this->profile_exist($url, $usernameForDomain, $person, $comb, "xanga");
                }
                ## Disable becase he take more time and did'n open
                if ( false && (($comb['additional'] && $comb['additional'] == "plancast") || !$comb['additional']) && $camefromSource != 'plancast') {

                    // plancast
                    $url = "http://plancast.com/{$username}";
                    $pattern = "#\<h1\>\s*there's\s+no\s+plancast\s+user\s+with\s+the\s+username\s*#is";
                    $res_array = array(
                        "source" => "username",
                        "main_source" => "plancast",
                        "type" => 'username(' . $username . ')',
                        "content" => $url,
                        'combination_id' => $comb['id'],
                    );
                    Yii::app()->reportLog->resultFound($res_array, $person, $comb);
                    unset($res_array);
                    $profilesCheck[] = array($url, $username, $person, $comb, "plancast", $pattern);
                    //$this->profile_exist($url, $username, $person, $comb, "plancast", $pattern);
                }
                if ((($comb['additional'] && $comb['additional'] == "about") || !$comb['additional'])&& $camefromSource != 'about') {
                    // about
                    $url = "https://about.me/{$username}";
                    $res_array = array(
                        "source" => "username",
                        "main_source" => "about.me",
                        "type" => 'username(' . $username . ')',
                        "content" => $url,
                        'combination_id' => $comb['id'],
                    );
                    Yii::app()->reportLog->resultFound($res_array, $person, $comb);
                    unset($res_array);
                    $profilesCheck[] = array($url, $username, $person, $comb, "about.me");
                    //$this->profile_exist($url, $username, $person, $comb, "aboutme");
                }
                if (false && (($comb['additional'] && $comb['additional'] == "lifestream") || !$comb['additional'])&& $camefromSource != 'lifestream') {
                    // lifestream
                    $url="http://lifestream.aol.com/stream/{$username}";
                    //$pattern = "/<div class=\"rr_profile\">/";
                    $pattern = "#\<p\>\s*These\s+aren't\s+the\s+bunnies\s+you're\s+looking\s+for...\s*#is";
                    $res_array = array(
                        "source" => "username",
                        "main_source" => "lifestream.aol",
                        "type" => 'username(' . $username . ')',
                        "content" => $url,
                        'combination_id' => $comb['id'],
                    );
                    Yii::app()->reportLog->resultFound($res_array, $person, $comb);
                    unset($res_array);
                    //$this->profile_exist($url, $username, $person, $comb, "lifestream",$pattern);
                    $profilesCheck[] = array($url, $username, $person, $comb, "lifestream.aol",$pattern);
                }
                if ((($comb['additional'] && $comb['additional'] == "slideshare") || !$comb['additional'])&& $camefromSource != 'slideshare') {
                    // slideshare
                    $url = "http://www.slideshare.net/{$username}";
                    $res_array = array(
                        "source" => "username",
                        "main_source" => "slideshare",
                        "type" => 'username(' . $username . ')',
                        "content" => $url,
                        'combination_id' => $comb['id'],
                    );
                    Yii::app()->reportLog->resultFound($res_array, $person, $comb);
                    unset($res_array);
                    //$this->profile_exist($url, $username, $person, $comb, "slideshare");
                    $status = $this->check_username_lookup($person, $comb, $url, $username, "slideshare");
                    if ($status["name"] != 0 && $status["location"] != 0){
                        //$this->profile_exist($url, $username, $person, $comb, "slideshare",false,false,array(),$status["image"]);
                        $profilesCheck[] = array($url, $username, $person, $comb, "slideshare",false,false,array(),$status["image"]);
                    }else{
                        $deletedArray=array();
                        $deletedArray['is_deleted']="Yes";
                        $deletedArray['deleted_reason']="Not Matched";
                        $deletedArray['result_url']=$url;
                        Yii::app()->reportLog->resultDeleted($deletedArray, $person, $comb);
                        unset($deletedArray);
                    }
                }
                if ((($comb['additional'] && $comb['additional'] == "hubpages") || !$comb['additional'])&& $camefromSource != 'hubpages') {
                    // hubpages
                    $url = "http://hubpages.com/@{$username}";
                    $res_array = array(
                        "source" => "username",
                        "main_source" => "hubpages",
                        "type" => 'username(' . $usernameForDomain . ')',
                        "content" => $url,
                        'combination_id' => $comb['id'],
                    );
                    Yii::app()->reportLog->resultFound($res_array, $person, $comb);
                    unset($res_array);
                    $profilesCheck[] = array($url, $usernameForDomain, $person, $comb, "hubpages");
                    // $this->profile_exist($url, $usernameForDomain, $person, $comb, "hubpages");
                }
                if ((($comb['additional'] && $comb['additional'] == "twitch") || !$comb['additional'])&& $camefromSource != 'twitch') {
                    // Twitch
                    $url="https://api.twitch.tv/kraken/users/{$username}?on_site=1";
                    $pattern = "#\<p\>\s*The\s+page\s+could\s+not\s+be\s+found,\s+or\s+has\s+been\s+deleted\s+by\s+its\s+owner.\s*#is";
                    $res_array = array(
                        "source" => "username",
                        "main_source" => "twitch",
                        "type" => 'username(' . $username . ')',
                        "content" => $url,
                        'combination_id' => $comb['id'],
                    );
                    Yii::app()->reportLog->resultFound($res_array, $person, $comb);
                    unset($res_array);
                    //$this->profile_exist($url, $username, $person, $comb, "twitch");
                    $profilesCheck[] = array($url, $username, $person, $comb, "twitch");
                }
                if ((($comb['additional'] && $comb['additional'] == "photobucket") || !$comb['additional'])&& $camefromSource != 'photobucket') {
                    // Photobucket
                    $url = "http://www.photobucket.com/user/{$username}/profile";
                    $res_array = array(
                        "source" => "username",
                        "main_source" => "photobucket",
                        "type" => 'username(' . $username . ')',
                        "content" => $url,
                        'combination_id' => $comb['id'],
                    );
                    Yii::app()->reportLog->resultFound($res_array, $person, $comb);
                    unset($res_array);

                    //$this->profile_exist($url, $username, $person, $comb, "photobucket");

                    $profilesCheck[] = array($url, $username, $person, $comb, "photobucket");
                }
                if ((($comb['additional'] && $comb['additional'] == "kik") || !$comb['additional'])&& $camefromSource != 'kik') {
                    // Kik
                    $url = "https://kik.me/{$username}";
                    //$pattern = "#\<h2\>Oops,\s+there\s+is\s+no\s+Kik\s+user\s+with\s+that\s+username\s*\.\<\/h2\>#i";
                    $pattern = "/(<img\\s[^>]*?id=[\"']pic[\"'][^>]*?src=[\"']([^\"']*)[\"']).*?(<h1\\s[^>]*?class=[\"']display-name[^>]*>([^<]*)<\\/h1>)/is";
                    $res_array = array(
                        "source" => "username",
                        "main_source" => "kik.me",
                        "type" => 'username(' . $username . ')',
                        "content" => $url,
                        'combination_id' => $comb['id'],
                    );
                    Yii::app()->reportLog->resultFound($res_array, $person, $comb);
                    unset($res_array);
                    //$this->profile_exist($url, $username, $person, $comb, "kik",$pattern);
                    $profilesCheck[] = array($url, $username, $person, $comb, "kik.me",$pattern);
                }
                if ((($comb['additional'] && $comb['additional'] == "bitly") || !$comb['additional'])&& $camefromSource != 'bitly') {
                    // Bitly
                    $url = "https://bitly.com/u/{$username}";
                    $pattern ="#\<p\>Uh\s+oh,\s+Bitly\s+couldn't\s+find\s+a\s+link\s+for\s+the\s+bitly\s+URL\s+you\s+clicked\s*\.\<\/p\>#i";
                    $res_array = array(
                        "source" => "username",
                        "main_source" => "bitly",
                        "type" => 'username(' . $username . ')',
                        "content" => $url,
                        'combination_id' => $comb['id'],
                    );
                    Yii::app()->reportLog->resultFound($res_array, $person, $comb);
                    unset($res_array);
                    //$this->profile_exist($url, $username, $person, $comb, "bitly",$pattern);
                    $profilesCheck[] = array($url, $username, $person, $comb, "bitly",$pattern);
                }
                if ((($comb['additional'] && $comb['additional'] == "okCupid") || !$comb['additional'])&& $camefromSource != 'okCupid') {
                    // OkCupid
                    $url = "http://www.okcupid.com/profile/{$username}";
                    //$pattern ="#\<p\>Uh\s+oh,\s+Bitly\s+couldn't\s+find\s+a\s+link\s+for\s+the\s+bitly\s+URL\s+you\s+clicked\s*\.\<\/p\>#i";
                    $res_array = array(
                        "source" => "username",
                        "main_source" => "okcupid",
                        "type" => 'username(' . $username . ')',
                        "content" => $url,
                        'combination_id' => $comb['id'],
                    );
                    Yii::app()->reportLog->resultFound($res_array, $person, $comb);
                    unset($res_array);
                    //$this->profile_exist($url, $username, $person, $comb, "okcupid");
                    $profilesCheck[] = array($url, $username, $person, $comb, "okcupid");
                }
                //not work in egypt
                if (0&&(($comb['additional'] && $comb['additional'] == "rdio") || !$comb['additional'])&& $camefromSource != 'rdio') {
                    // Rdio
                    $url = "http://www.rdio.com/people/{$username}";
                    $res_array = array(
                        "source" => "username",
                        "main_source" => "rdio",
                        "type" => 'username(' . $username . ')',
                        "content" => $url,
                        'combination_id' => $comb['id'],
                    );
                    Yii::app()->reportLog->resultFound($res_array, $person, $comb);
                    unset($res_array);
                    //$this->profile_exist($url, $username, $person, $comb, "rdio");
                    $profilesCheck[] = array($url, $username, $person, $comb, "rdio");
                }
                if ((($comb['additional'] && $comb['additional'] == "stumbleupon") || !$comb['additional']) && $camefromSource != 'stumbleupon')
                {
                    // StumbleUpon
                    $url = "http://www.stumbleupon.com/stumbler/{$username}/likes?_nospa=true";
                    //$url = "http://www.stumbleupon.com/stumbler/{$username}?_nospa=true";
                    $pattern = "/error-404/";
                    $res_array = array(
                        "source" => "username",
                        "main_source" => "stumbleupon",
                        "type" => 'username(' . $username . ')',
                        "content" => $url,
                        'combination_id' => $comb['id'],
                    );
                    Yii::app()->reportLog->resultFound($res_array, $person, $comb);
                    unset($res_array);
                    //$this->profile_exist($url, $username, $person, $comb, "stumbleupon");
                    $profilesCheck[] = array($url, $username, $person, $comb, "stumbleupon",$pattern);
                }
                //not work in egypt
                if (!$is_local &&(($comb['additional'] && $comb['additional'] == "pandora") || !$comb['additional']) && $camefromSource != 'pandora') {
                    // Pandora
                    $url = "http://www.pandora.com/profile/{$username}";
                    $pattern = "/<meta property=\"og:type\" content=\"website\"\/>/";
                    $res_array = array(
                        "source" => "username",
                        "main_source" => "pandora",
                        "type" => 'username(' . $username . ')',
                        "content" => $url,
                        'combination_id' => $comb['id'],
                    );
                    Yii::app()->reportLog->resultFound($res_array, $person, $comb);
                    unset($res_array);
                    //$this->profile_exist($url, $username, $person, $comb, "pandora",$pattern);
                    $profilesCheck[] = array($url, $username, $person, $comb, "pandora",$pattern);

                }
                if ((($comb['additional'] && $comb['additional'] == "delicious") || !$comb['additional']) && $camefromSource != 'delicious') {
                    // Delicious
                    $url = "http://delicious.com/{$username}";
                    $res_array = array(
                        "source" => "username",
                        "main_source" => "delicious",
                        "type" => 'username(' . $username . ')',
                        "content" => $url,
                        'combination_id' => $comb['id'],
                        "checkUrl"=>"https://avosapi.delicious.com/api/v1/account/public/profile/{$username}"
                    );
                    Yii::app()->reportLog->resultFound($res_array, $person, $comb);
                    unset($res_array);
                    //$this->profile_exist($url, $username, $person, $comb, "delicious");
                    $profilesCheck[] = array($url, $username, $person, $comb, "delicious");
                }
                if ((($comb['additional'] && $comb['additional'] == "lastfm") || !$comb['additional']) && $camefromSource != 'lastfm') {
                    // lastfm
                    $url = "http://www.last.fm/user/{$username}";
                    $res_array = array(
                        "source" => "username",
                        "main_source" => "last.fm",
                        "type" => 'username(' . $username . ')',
                        "content" => $url,
                        'combination_id' => $comb['id'],
                    );
                    Yii::app()->reportLog->resultFound($res_array, $person, $comb);
                    unset($res_array);
                    //$this->profile_exist($url, $username, $person, $comb, "lastfm");
                    $profilesCheck[] = array($url, $username, $person, $comb, "last.fm");
                }
                if ((($comb['additional'] && $comb['additional'] == "picsart") || !$comb['additional']) && $camefromSource != 'picsart') {
                    // Picsart
                    $url = "https://picsart.com/{$username}";
                    $pattern = "/<title>*?error*?<\/title>/i";
                    $res_array = array(
                        "source" => "username",
                        "main_source" => "picsart",
                        "type" => 'username(' . $usernameForDomain . ')',
                        "content" => $url,
                        'combination_id' => $comb['id'],
                    );
                    Yii::app()->reportLog->resultFound($res_array, $person, $comb);
                    unset($res_array);
                    //$this->profile_exist($url, $usernameForDomain, $person, $comb, "picsart",$pattern);
                    $profilesCheck[] = array($url, $usernameForDomain, $person, $comb, "picsart",$pattern);
                }
                if ((($comb['additional'] && $comb['additional'] == "squarespace") || !$comb['additional']) && $camefromSource != 'squarespace') {
                    // Squarespace
                    $url = "http://{$usernameForDomain}.squarespace.com/";
                    $res_array = array(
                        "source" => "username",
                        "main_source" => "squarespace",
                        "type" => 'username(' . $usernameForDomain . ')',
                        "content" => $url,
                        'combination_id' => $comb['id'],
                    );
                    Yii::app()->reportLog->resultFound($res_array, $person, $comb);
                    unset($res_array);
                    //$this->profile_exist($url, $usernameForDomain, $person, $comb, "squarespace");
                    $profilesCheck[] = array($url, $usernameForDomain, $person, $comb, "squarespace");
                }
                if ((($comb['additional'] && $comb['additional'] == "yelp") || !$comb['additional']) && $camefromSource != 'yelp') {

                    $url = "https://{$usernameForDomain}.yelp.com";
                    $res_array = array(
                        "source" => "username",
                        "main_source" => "yelp",
                        "type" => 'username(' . $usernameForDomain . ')',
                        "content" => $url,
                        'combination_id' => $comb['id'],
                    );
                    Yii::app()->reportLog->resultFound($res_array, $person, $comb);
                    unset($res_array);
                    $profilesCheck[] = array($url, $usernameForDomain, $person, $comb, "yelp");
                    //$this->profile_exist($url, $usernameForDomain, $person, $comb, "yelp");
                }


        $options['customOptions']['addToheaders'] =['Cookie: ilo0=true;'];
        $_result['checkNameMatch'] = $checkNameMatch ;
        $options['tmp'] = $_result;

        $options['customOptions']['timeout'] = 5;
        $options['customOptions']['disableRetry'] = true;
        if(strpos($url, 'delicious.com')!==false){
            $url="https://avosapi.delicious.com/api/v1/account/public/profile/{$username}?_=1442841452831";
        }

        public function sourceNeedFllowRedirect($url)
        {
            $sources  = [
                'scribd',
                'photobucket',
                'disqus',
                'facebook',
                'picsart',
                'picasaweb',
                'instagram',
                'gravatar',
                'ustream',
                'wikipedia',
                "pinterest"
            ];
*/
