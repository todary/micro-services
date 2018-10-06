<?php
namespace Skopenow\Matching\Check;

use Skopenow\Matching\Interfaces\CheckInterface;
use Skopenow\Matching\{Status, RunAnalyzer};
use Skopenow\Matching\Match\LocationMatch;

class InstagramCheck implements CheckInterface
{
    private $info;
    private $entry;
    private $data;
    private $url;
    private $person;
    private $combination;
    private $htmlContent = [];

    public function __construct(
        string $url,
        array $info,
        $combination,
        ReportService $report
    )
    {
        $this->entry = loadService('urlInfo');
        $status = new Status;
        $this->data = $status->matchingData;
        $this->combination = $combination;
        $this->person = $report->getReport();
        $this->url = $url;
        $this->info = $info;
        $this->report = $report;
    }

    public function check()
    {
        $profile_url = $this->url;
        $person = $this->person;
        $combination = $this->combination;
        $this->data['name']['status'] = true;
        $runNameAnalyzer = new RunAnalyzer($this->report);
        $combination['urlProfile'] = $profile_url;
        $status = [];
        $check = [];
        $status["name"] = 1;
        $status["location"] = 1;

        if (isset($combination['htmlContent']) && is_array($combination['htmlContent'])) {
            $this->htmlContent = $combination['htmlContent'];
            unset($combination['htmlContent']);
        }

        $url = $profile_url;
        $comb = $combination;
        $uname = $this->entry->getUsername($url);

        // Check if name and location is empty,set log and return $status .
        if (empty($person['first_name']) && empty($person['city'])) {
            $check['name'] = "empty first_name && city";
            $check['location'] = "empty first_name && city";
            $checkArray = [];
            $checkArray['result_url'] = $profile_url;
            $checkArray['check'] = $check;
            // TODO
            // Yii::app()->reportLog->resultCheck($checkArray, $person, $combination);
            unset($checkArray);
            return $this->data;
        }

        // TODO
        // SearchApis::logData($person['id'], "Checking instagram profile $profile_url", $combination);

        // $info = $this->entry->getProfileInfo($url, 'instagram', $this->htmlContent);
        $info = $this->info;
        $name = $info['name'];
        $image = $info['image'];

        if (strlen($name) && !empty($name)) {
            $status['nameDetails'] = [];
            // TODO
            // SearchApis::logData($person['id'], "Found name ({$name}) to check instagram profile $profile_url", $combination);
            $this->data['name']['matchWith'] = $status['nameDetails']['matchWith']??'';
            $this->data['name']['identities']['input_name'] = $status['nameDetails']['input_name']??false;

            if (in_array('fn', $status['nameDetails'])) {
                $this->data['name']['identities']['fn'] = true;
            }

            if (in_array('mn', $status['nameDetails'])) {
                $this->data['name']['identities']['mn'] = true;
            }
            if (in_array('IN', $status['nameDetails'])) {
                $this->data['name']['identities']['input_name'] = true;
            }

            if (in_array('ln', $status['nameDetails'])) {
                $this->data['name']['identities']['ln'] = true;
            }

            if (in_array('unq_name', $status['nameDetails'])) {
                $this->data['name']['identities']['unq_name'] = true;
            }
            if (in_array('fzn', $status['nameDetails'])) {
                $this->data['name']['identities']['fzn'] = true;
            }

        } else {
            $check['name'] = "Not Found";
            // TODO
            // SearchApis::logData($person['id'],"Not found name for instagram profile $profile_url", $combination);
        }

        if (!empty($image)) {
            $status["image"] = stripslashes($image);
        }

        if (
            empty($name) &&
            $uname &&
            isset($person['usernames']) &&
            stripos(",{$person['usernames']},", ",{$uname},") !== false
        ) {
            // TODO
            // SearchApis::logData($person['id'], "This profile $url comes from a manual/reverse username search, passed.", $comb);
            $status['name'] = true ;
            $status['location'] = true ;
        }

        return $this->data;
    }

    public function checkPosts($person, $htmlContent)
    {
        if (
            empty($htmlContent) ||
            !is_array($htmlContent) ||
            !array_key_exists('body', $htmlContent)
        ) {
            return false;
        }

        $re = "/window._sharedData = {(.*)}/";
        if (preg_match($re, $htmlContent['body'], $match)) {
            $json = $match[1];
            $shareData = json_decode("{" . $json . "}", true);
            if (!empty($shareData)) {
                $title = "";
                $comments = [];
                if (!empty($shareData['entry_data']['PostPage'][0]['media'])) {
                    $data = $shareData['entry_data']['PostPage'][0]['media'];
                    if (!empty($data['caption'])) {
                        $title=strtolower($data['caption']);
                    }
                    if (!empty($data['comments'])) {
                        $comments=$data['comments'];
                    }

                    $searched_names = explode(",", $person['searched_names']);

                    foreach ($searched_names as $name) {
                        $real_name=strtolower($name);
                        if (stripos($title, $name) !== false) {
                            // searchApis::logData($person['id'],"Matching : https://www.instagram.com/p/{$data['code']} Found {$name} in post title ($title)\nMatched");
                            return true;
                        }

                        foreach ($comments['nodes'] as $comment) {
                            if (stripos($comment['text'], $name ) !== false) {
                                // TODO
                                // searchApis::logData($person['id'],"Matching : https://www.instagram.com/p/{$data['code']} Found {$name} in comment ({$comment['text']})\nMatched");

                                return true;
                            }
                        }
                    }
                }
            }
        }
        return false;
    }

    public function checkLocation($username, $person, $combination)
    {
        //$username = 'romado12187';
        $parameters = array('node/search_apis/index.js', 'instagram', $username);
        // TODO: run node after fixed it.
        //$data = SearchApis::runNodeCommand($person['id'],$parameters);
        $data = false;

        $match = new LocationMatch;

        if (
            is_array($data) &&
            count($data) &&
            count($data['user']) &&
            count($data['user']['cities']) &&
            count($data['user']['states'])
        ) {
            $cities = array_unique($data['user']['cities']);
            $states = array_unique($data['user']['states']);
            foreach ($cities as $city) {
                foreach ($states as $state) {
                    $locationDetails = [];
                    $locationProfile = $city . ',' . $state;
                    $locexist = $match->isLocationMatch(
                        $person,
                        $combination,
                        $person['city'],
                        $person['city'],
                        $locationProfile,
                        $locationProfile,
                        $locationDetails
                    );
                    if ($locexist) {
                        return $locationProfile;
                    }
                }
            }
        } elseif (
            is_array($data) &&
            count($data) &&
            count($data['followers']) &&
            count($data['followers']['cities']) &&
            count($data['followers']['states'])
        ) {
            $cities = array_unique($data['followers']['cities']);
            $states = array_unique($data['followers']['states']);
            foreach ($cities as $city) {
                foreach ($states as $state) {
                    $locationDetails = array();
                    $locationProfile = $city.','.$state;
                    $locexist = $match->isLocationMatch(
                        $person,
                        $combination,
                        $person['city'],
                        $person['city'],
                        $locationProfile,
                        $locationProfile,
                        $locationDetails
                    );
                    if ($locexist) {
                        return $locationProfile;
                    }
                }
            }
        } elseif (
            is_array($data) &&
            count($data) &&
            count($data['follows']) &&
            count($data['follows']['cities']) &&
            count($data['follows']['states'])
        ) {
            $cities = array_unique($data['follows']['cities']);
            $states = array_unique($data['follows']['states']);
            foreach ($cities as $city) {
                foreach ($states as $state) {
                    $locationDetails = array();
                    $locationProfile = $city . ',' . $state;
                    $locexist = $match->isLocationMatch(
                        $person,
                        $combination,
                        $person['city'],
                        $person['city'],
                        $locationProfile,
                        $locationProfile,
                        $locationDetails
                    );
                    if ($locexist) {
                        return $locationProfile;
                    }
                }
            }
        }
        return false;
    }

    public function matchInstagramLastNameWithUserName($lastName, $userName, $personId)
    {
        $lastNames = explode(',', $lastName);
        if (is_array($lastNames)) {
            foreach ($lastNames as $name) {
                if (!$name) {
                    continue;
                }
                $position = stripos($userName, $name);
                if ($position !== false) {
                    $position = $position + strlen($name);
                    $restWord = substr($userName, $position);
                    $restWord = preg_replace('/([^\D]*)/', '', $restWord);

                    if (!$restWord ||
                        is_numeric($restWord) ||
                        isWord($restWord,$personId)
                    ) {
                        return true;
                    }
                }
            }
        }
        return false;
    }
}
