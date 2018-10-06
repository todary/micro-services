<?php

/**
 * google search
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

class GoogleFetcher extends AbstractFetcher
{
    /**
     * @const string The selected account for search
     */
    const MAIN_SOURCE_NAME = "google";

    public $availableProfileInfo = ['name'];

    public $maxResults = 10;

    protected function prepareRequest()
    {
        $query = [];
        if ($this->criteria->full_name) {
            $query []= '"'.$this->criteria->full_name.'"';
        }
        if ($this->criteria->phone) {
            $query []= '"'.$this->criteria->phone.'"';
        }
        if ($this->criteria->email) {
            $query []= '"'.$this->criteria->email.'"';
        }
        if ($this->criteria->address) {
            $query []= '"'.$this->criteria->address.'"';
        } elseif ($this->criteria->city || $this->criteria->state) {
            $address = [];

            if ($this->criteria->city) {
                $address []= $this->criteria->city;
            }
            if ($this->criteria->state) {
                $address []= $this->criteria->state;
            }
            if ($this->criteria->country && $this->criteria->country != "US") {
                $address []= $this->criteria->country;
            }
            $query []= '"'.implode(", ", $address).'"';
        }
        if ($this->criteria->school) {
            $query []= '"'.$this->criteria->school.'"';
        }
        if ($this->criteria->company) {
            $query []= '"'.$this->criteria->company.'"';
        }
        if ($this->criteria->site) {
            $query []= "site:".$this->criteria->site;
        }
        if ($this->criteria->username) {
            if ($this->criteria->search_type == "popularity") {
                $query []= '"'.$this->criteria->username .'"';
            } else {
                $query []= 'inurl:"'.$this->criteria->username .'"';
            }
        }

        $queryString = http_build_query([
            'client'=>'ubuntu',
            'channel'=>'fs',
            'q'=>implode(" ", $query),
            'ie'=>'utf-8',
            'oe'=>'utf-8',
        ]);

        $url = 'https://www.google.com/search?' . $queryString;
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
        if (empty($response['body'])) {
            return $list;
        }

        $content = $response['body'];
        $content = @substr($content, strpos($content, "<"));

        \Log::info("Start parsing html");
        $html = str_get_html($content);
        \Log::info("End parsing html");

        $noResults = count($html->find('div#topstuff div.e'));
        if ($noResults) {
            return $list;
        }

        $re = '/<div[^>]+id="resultStats"[^>]*>\D+([\d,]+)/';
        preg_match($re, $content, $matches);
        $availableResultsCount = null;
        if (!empty($matches[1])) {
            $availableResultsCount = (int)str_replace([',',' '], '', $matches[1]);
            $list->setAvailableResultsCount($availableResultsCount);
        }

        if ($this->criteria->search_type == "popularity") {
            return $list;
        }

        \Log::info("Google results count: " . $availableResultsCount);

        $resultsCount = 0;
        foreach ($html->find('li.g,div.g') as $element) {
            if ($resultsCount >= $this->maxResults) {
                break;
            }

            $url = "";
            $title = "";
            $description = "";

            $link = $element->find("a");
            if (isset($link[0])) {
                preg_match("#\?q\=(.+?)\&#i", $link[0]->href, $linkMatch);
                if (isset($linkMatch[1])) {
                    $url = urldecode($linkMatch[1]);
                    $pattern = "/".preg_quote($url, "/").".*?>(.+?)<\\/a>/is";
                    $reDesc = "/".preg_quote($url, "/").".*?class=\"st\">(.*?)<\\/div>/is";
                    preg_match($pattern, $content, $match);

                    if (isset($match[1])) {
                        $title = strip_tags($match[1]);
                    } else {
                        $pattern = "/".preg_quote($linkMatch[1], "/").".*?>(.+?)<\\/a>/is";
                        preg_match($pattern, $content, $match);
                        if (isset($match[1])) {
                            $title = strip_tags($match[1]);
                        }
                    }

                    if (stripos($url, "instagram") !== false) {
                        preg_match('#(.*)\s*\(@.*\)#', $title, $matches);
                        if (isset($matches[1])) {
                            $title = $matches[1];
                        }
                    }

                    preg_match($reDesc, $content, $match);

                    if (isset($match[1])) {
                        $description = strip_tags($match[1]);
                    } else {
                        $reDesc = "/".preg_quote($linkMatch[1], "/").".*?class=\"st\">(.*?)<\\/div>/is";
                        preg_match($reDesc, $content, $match);
                        if (isset($match[1])) {
                            $description = strip_tags($match[1]);
                        }
                    }
                }

                $description = html_entity_decode($description);
                if (!filter_var($url, FILTER_VALIDATE_URL)) {
                    continue;
                }
            }

            $result = new SearchResult($url);
            $result->orderInList = $resultsCount;
            $result->title = $title;
            $result->description = $description;

            if ($this->onResultFound($result)) {
                $list->addResult($result);
            }

            $resultsCount++;
        }

        return $list;
    }

    protected function checkIsProfile(string $url): bool
    {
        $urlInfo = loadService('urlInfo');

        return $urlInfo->isProfile($url);
    }
}
