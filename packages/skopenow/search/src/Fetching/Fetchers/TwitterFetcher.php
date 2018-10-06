<?php

/**
 * Twitter search
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

class TwitterFetcher extends AbstractFetcher
{

    public $availableProfileInfo = ["name", "location", "image", "insite_links"];

    /**
     * @const string The selected account for search
     */
    const MAIN_SOURCE_NAME = "twitter";

    protected function prepareRequest()
    {
        $query = urlencode($this->criteria->full_name);

        if(!empty($this->criteria->city) && !empty($this->criteria->state)) {
            $query .= urlencode(" near: " . $this->criteria->city." ".$this->criteria->state);
        } elseif (empty($this->criteria->city) && !empty($this->criteria->state)) {
            $query .= urlencode(" near: " . $this->criteria->state);
        } elseif (!empty($this->criteria->city) && empty($this->criteria->state)) {
            $query .= urlencode(" near: " . $this->criteria->city);
        }

        if(!empty($this->criteria->distance)) {
            $query .= urlencode(" within:".$this->criteria->distance."mi");
        }

        $url = "https://twitter.com/search?q=" . str_ireplace("%3A+", ":", $query) . "&src=typd&f=users";

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

        $pattern = '#\\\\u003ch1(.*)class=\\\["\']AdaptiveSearchTitle-title(.*)h1\\\\u003e#i';
        $contentBody = preg_replace($pattern, ' ', $response['body']);

        $content = json_decode($contentBody, true);

        if (is_array($content) && !isset($content['page'])) {
            return $list;
        }

        preg_match_all('/ProfileCard-bg js-nav" href\s*=\s*["\']([^"\']*)/', $content['page'], $profiles, PREG_SET_ORDER);

        $resultsCount = 0;
        foreach ($profiles as $rs) {
            if ($resultsCount >= $this->maxResults) {
                break;
            }

            $result = new SearchResult("http://twitter.com" . $rs[1], true);
            $result->username = str_replace("/", "", $rs[1]);
            $result->orderInList = $resultsCount;
            $result->resultsCount = count($profiles);

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
