<?php

namespace Skopenow\Search\Fetching\Fetchers\Facebook;

use Skopenow\Search\Fetching\AbstractFetcher;
use Skopenow\Search\Fetching\FetcherInterface;
use Skopenow\Search\Models\SearchList;
use Skopenow\Search\Models\SearchListInterface;
use Skopenow\Search\Models\DataPoint;
use Skopenow\Search\Models\SearchResult;
use App\DataTypes\Name;

class FacebookInFriends extends AbstractFetcher
{

    /**
     * @const string The selected account for search
     */
    const MAIN_SOURCE_NAME = "facebook";

    /**
     * [$availableProfileInfo available info should be exist]
     * @var [type]
     */
    public $availableProfileInfo =["name", "location","image"];

    protected $mainResult;


    protected function prepareRequest()
    {
        $url = "https://www.facebook.com/ajax/typeahead/profile_browser/friends/query.php?";
        $query = [
            'viewer'    =>  '_-DATA-_',
            'neighbor'  =>  $this->criteria->social_profile_id,
            '__user'    =>  '_-DATA-_',
            '__a'       =>  '1',
            'dpr'       =>  '1',
            'context'   =>  'friends_profile_browser_lp',
            '__af'      =>  'o',
            '__req'     =>  't',
            '__be'      =>  '-1',
            '__pc'      =>  'PHASED:DEFAULT',
            '__rev'     =>  '2586932',
        ];

        if (!empty($this->criteria->first_name)) {
            $query['value'] = $this->criteria->first_name. ' ' . $this->criteria->last_name;
        } else {
            $query['value'] = $this->criteria->last_name;
        }
        $query = http_build_query($query);
        $url .= $query;

        $request = ["url" => $url];
        return $request;
    }

    protected function makeRequest()
    {
        try {
            $entry = loadService('HttpRequestsService');
            $response = $entry->fetch($this->request['url'], 'GET') ;
            $response->getBody()->rewind();
            return ['body'  =>  $response->getBody()->getContents()] ;
        } catch (\Exception $ex) {
            return ['body'  =>  ''];
        }
    }

    protected function processResponse($response): SearchListInterface
    {
        $matches1 = array();
        $matches2 = array();

        $pattern1 = "/for \(;;\);(.*)/";
        preg_match($pattern1, $response['body'], $matches1);
        ## merge both matched results to get all profiles.
        $friendsList = @json_decode($matches1[1], true)['payload']['entries'] ?? [];
        $list = new SearchList(self::MAIN_SOURCE_NAME);
        $list->setUrl($this->request['url']);
        $resultsCount=0;
        $this->mainResult = $this->getMainResult();
        foreach ($friendsList as $key => $friend) {
            if ($resultsCount >= $this->maxResults) {
                break;
            }

            if ($this->checkLink($friend['path'])) {
                $result = $this->createResult($friend['path'], $key);
                $result->addName(Name::create(['full_name' => $friend['names'][0]], static::MAIN_SOURCE_NAME));
                $result->image = $friend['photo'];
                $result->setIsRelative(true);
                $this->addMainProfileRelation($result);
                if ($this->onResultFound($result)) {
                    $list->addResult($result);
                    $resultsCount++;
                }
            }
        }
        return $list;
    }

    protected function checkLink($link): bool
    {
        $status = false ;
        if (stripos($link, 'facebook.com/') !== false) {
            $status = true ;
        }

        return $status ;
    }

    protected function createResult($link, $orderInList)
    {
        $result = new SearchResult($link, true);
        $result->screenshotUrl = $link;
        $result->orderInList = $orderInList;
        return $result;
    }

    protected function addMainProfileRelation($result)
    {
        if (empty($this->mainResult)) {
            return false;
        }
        $relationsFlags = loadData('relationsFlags');

        if (!$this->mainResult->is_relative) {
            $identityShouldHave = 'rltvWithMain';
        } else {
            $identityShouldHave = 'rltvWithRltv';
        }
        
        $result->addLink([
            "id" => $this->criteria->result_id,
            'url' => $this->mainResult->unique_content??"",
            'reason' => $relationsFlags['relative']['value'],
            'is_relative' => $this->mainResult->is_relative,
            'is_profile' => true,
            'identitiesShouldHave' => [$identityShouldHave]
        ]);
        
        $result->addIdentityShouldHave($identityShouldHave);
    }

    protected function getMainResult()
    {
        $resultService = loadService('result');
        $mainResult = $resultService->getResult($this->criteria->result_id);
        return $mainResult;
    }
}
