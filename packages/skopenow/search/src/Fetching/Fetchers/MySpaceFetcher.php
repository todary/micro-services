<?php
/**
 * Myspace search
 * @author Mostafa Ameen
 * @package Search
 * @subpackage Fetching
 */

namespace Skopenow\Search\Fetching\Fetchers;

use Skopenow\Search\Fetching\AbstractFetcher;
use Skopenow\Search\Models\CriteriaInterface;
use Skopenow\Search\Models\SearchList;
use Skopenow\Search\Models\SearchListInterface;
use Skopenow\Search\Models\DataPoint;
use Skopenow\Search\Models\SearchResult;

class MySpaceFetcher extends AbstractFetcher
{
    /**
     * @const string The selected account for search
     */
    const MAIN_SOURCE_NAME = "myspace";

    protected function prepareRequest()
    {
        $url = "https://myspace.com/search/people?q=" . $this->criteria->first_name . "+" . $this->criteria->last_name;
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

    /**
     * Parse the content of the search response
     * @param string $response The output of the HTTP request of the search URL
     * @return SearchListInterface
     */
    protected function processResponse($response): SearchListInterface
    {
        $list = new SearchList(self::MAIN_SOURCE_NAME);
        $list->setUrl($this->request['url']);
        $dom = str_get_html($response['body']);
        if (!$dom) {
            return $list;
        }

        $results = $dom->find("ul.people li");

        if (!$results) {
            return $list;
        }

        $resultsCount = 0;
        foreach ($results as $k => $rs) {
            if ($resultsCount >= $this->maxResults) {
                break;
            }

            $linkObject = $rs->find("a", 0);
            if (!$linkObject) {
                continue;
            }

            $link = $linkObject->href;

            $url = "https://myspace.com" . $link;

            $result = new SearchResult($url, true);
            $result->username = trim($link, '/');
            $result->screenshotUrl = $url;
            $result->orderInList = $resultsCount;

            $divData = $linkObject->find("div.connectButton", 0);
            if ($divData) {
                $attributes = $divData->getAllAttributes();

                if (!empty($attributes['data-title'])) {
                    $result->addName(\App\DataTypes\Name::create(["full_name"=>$attributes['data-title']],$result->mainSource));
                }
                if (!empty($attributes['data-image-url'])) {
                    $result->image = $attributes['data-image-url'];
                }
            }

            if ($this->onResultFound($result)) {
                $list->addResult($result);
            }

            $resultsCount++;
        }

        return $list;
    }
}
