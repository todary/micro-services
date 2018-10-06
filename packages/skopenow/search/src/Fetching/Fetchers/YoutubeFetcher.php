<?php

/**
 * Youtube search
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

class YoutubeFetcher extends AbstractFetcher
{

    /**
     * @const string The selected account for search
     */
    const MAIN_SOURCE_NAME = "youtube";

    protected function prepareRequest()
    {
        $url = "https://www.youtube.com/results?q=".urlencode($this->criteria->full_name);
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

        $html = $response['body'];
        $firstCasepattern = "/<ol[^\\>]+class=\"item-section\">(.*?)<\\/ol>/s";
        $secondCasePattern = "~window\[\"ytInitialData\"\]\s=\s(\{.*?\};)~m";

        // check spell correction
        $autoCorrected = preg_match('/class="spell-correction-corrected">Showing results for/', $html);
        if($autoCorrected) {
            return $list;
        }

        $resultsCount = 0;
        if(preg_match($firstCasepattern, $html, $match)) {
            $result = str_replace("</ol>","", $match[1]);
            $pattern = "/yt-lockup-thumbnail.*?href=[\\\"']([^\\\"']*)(.*?)class=[\\\"']yt-lockup-content[\\\"'].*?title=[\\\"']([^\\\"']+).*?yt-lockup-byline.*?href=[[\\\"']([^\\\"']+)/s";
            preg_match_all($pattern, $html, $match);

            if(!empty($match)) {
                $urls = $match[1];
                $images = $match[2];
                $titles = $match[3];


                foreach ($urls as $key => $value) {
                    if ($resultsCount >= $this->maxResults) {
                        break;
                    }

                    $videotype = explode('/', str_replace("?v=","/",$value));
                    $videotype[1] = preg_replace("#\?.*#", "", $videotype[1]);

                    $url = "https://www.youtube.com".$value;
                    $title = $titles[$key];
                    if(preg_match("/data-thumb=\"([^\"]+)\"/s", $images[$key], $matched)) {
                        $image = $matched[1];
                    }elseif(preg_match("/src=\"([^\"]+)\"/s", $images[$key], $matched)) {
                        $image = $matched[1];
                    }

                    $result = new SearchResult($url);
                    $result->title = $title;
                    $result->image = $image;
                    $result->orderInList = $resultsCount;

                    if ($this->onResultFound($result)) {
                        $list->addResult($result);
                    }

                    if ($this->onDataPointFound(new DataPoint())) {
                    }

                    $resultsCount++;
                }
            }
        } elseif(preg_match($secondCasePattern, $html, $match)) {
            $content = json_decode(str_replace(';', '', $match[1]));
            $items = @($content->contents->twoColumnSearchResultsRenderer->primaryContents->sectionListRenderer->contents[0]->itemSectionRenderer->contents);

            if($items) {
                foreach($items as $item) {
                    if ($resultsCount >= $this->maxResults) {
                        break;
                    }

                    $url = "";
                    $image = "";
                    $title = "";
                    $profileName = "";
                    $description = "";

                    if(isset($item->channelRenderer)) {
                        $item = $item->channelRenderer;
                        $urlPart = @$item->navigationEndpoint->webNavigationEndpointData->url;
                        $image = @$item->thumbnail->thumbnails[0]->url;
                        $title = @$item->title->simpleText;
                        $description = @$item->description->simpleText;
                        $url = "https://www.youtube.com".$urlPart;
                        $profileName = @$item->channelId;
                    } elseif(isset($item->videoRenderer)) {
                        $item = $item->videoRenderer;
                        $urlPart = @$item->navigationEndpoint->webNavigationEndpointData->url;
                        $image = @$item->thumbnail->thumbnails[0]->url;
                        $title = @$item->title->simpleText;
                        $description = @$item->description->simpleText;
                        $url = "https://www.youtube.com".$urlPart;
                        $profileName = @$item->ownerText->runs[0]->navigationEndpoint->browseEndpoint->browseId;
                    } else {
                        continue;
                    }

                    $result = new SearchResult($url);
                    $result->title = $title;
                    $result->description = $description;
                    $result->image = $image;
                    $result->username = $profileName;
                    $result->orderInList = $resultsCount;

                    if ($this->onResultFound($result)) {
                        $list->addResult($result);
                    }

                    if ($this->onDataPointFound(new DataPoint())) {
                    }

                    $resultsCount++;
                }
            }
        }
        return $list;
    }
}
