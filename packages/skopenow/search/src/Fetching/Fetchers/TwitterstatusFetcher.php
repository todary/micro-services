<?php

/**
 * Twitter status search
 * @author Wael Salah
 * @package Search
 * @subpackage Fetching
 */

namespace Skopenow\Search\Fetching\Fetchers;

use Skopenow\Search\Fetching\AbstractFetcher;
use Skopenow\Search\Models\SearchList;
use Skopenow\Search\Models\SearchListInterface;
use Skopenow\Search\Models\DataPoint;
use Skopenow\Search\Models\SearchResult;
use App\DataTypes\Name;

class TwitterstatusFetcher extends AbstractFetcher
{
    /**
     * @const string The selected account for search
     */
    const MAIN_SOURCE_NAME = "twitterstatus";

    public $availableProfileInfo = ['name'];

    protected function prepareRequest()
    {
        $query = $this->criteria->full_name;

        if(!empty($this->criteria->city) && !empty($this->criteria->state)) {
            $query .= ' near:"' . $this->criteria->city.", ".$this->criteria->state . '"';
        } elseif (empty($this->criteria->city) && !empty($this->criteria->state)) {
            $query .= ' near:"' . $this->criteria->state . '"';
        } elseif (!empty($this->criteria->city) && empty($this->criteria->state)) {
            $query .= ' near:"' . $this->criteria->city . '"';
        }

        if(!empty($this->criteria->distance)) {
            $query .= " within:".$this->criteria->distance."mi";
        }

        $url = "https://twitter.com/search?q=" . urlencode($query) . "&src=typd";
        $request = ['url'=>$url];
        return $request;
    }

    protected function makeRequest()
    {
        try {
            $entry = loadService('HttpRequestsService');
            $response = $entry->fetch($this->request['url'], 'GET');
            $response->getBody()->rewind();
            return ['body' => $response->getBody()->getContents()];
        } catch (\Exception $ex) {
            return ['body' => ''];
        }
    }

    protected function processResponse($response) : SearchListInterface
    {
        $list = new SearchList(self::MAIN_SOURCE_NAME);

        $list->setUrl($this->request['url']);

        if(!$response['body']) {
            return $list;
        }

        $content = json_decode($response['body'], true);

        if (is_array($content) && !isset($content['page'])) {
            return $list;
        }

        preg_match_all('/(?<=<a href=").*?(?=" class="tweet-timestamp js-permalink js-nav js-tooltip")/', $content['page'], $results, PREG_SET_ORDER);
        preg_match_all("/data-name=[\"']([^\"']*)[\"']/is", $content['page'], $names, PREG_SET_ORDER);
        $titlesPattern = '/<p class="TweetTextSize .*?">(.*?)<a .*?>(.*?)<\/a><\/p>/s';
        preg_match_all($titlesPattern, $content['page'], $titles, PREG_SET_ORDER) ;

        $resultsCount = 0;
        foreach($results as $k => $res) {
            if ($resultsCount >= $this->maxResults) {
                break;
            }

            $name = (isset($names[$k]) && !empty($names[$k])) ? $names[$k][01] : "";

            $result = new SearchResult("http://twitter.com" . $res[0]);
            $result->setIsProfile(false);
            $result->title = trim(strip_tags((!empty($titles[$k])) ? preg_replace("/<strong>|<\/strong>/", "", $titles[$k][1]) : ""));

            if($name && count(explode(" ", $name))>1) {
                $result->addName(Name::create(["full_name"=>$name], $result->mainSource));
            }

            $result->orderInList = $resultsCount;
            $result->resultsCount = count($results);

            if ($this->onResultFound($result)) {
                $list->addResult($result);
            }

            if ($this->onDataPointFound(new DataPoint())) {
            }

            $resultsCount++;

        }

        return $list;
    }
}
