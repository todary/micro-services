<?php

/**
 * GoogleGCSE search
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

class GoogleGCSEFetcher extends AbstractFetcher
{
    /**
     * @const string The selected account for search
     */
    const MAIN_SOURCE_NAME = "googleGCSE";

    public $availableProfileInfo = ['name'];

    protected function prepareRequest()
    {
        $googleDeveloperKey = getDBSetting("google_developer_key");
        $googleCustomSearchKey = getDBSetting("google_custom_search_key");

        $query = [];
        if ($this->criteria->full_name) {
            $query []= '"'.$this->criteria->full_name.'"';
        }
        if ($this->criteria->phone) {
            $query []= $this->criteria->phone;
        }
        if ($this->criteria->email) {
            $query []= $this->criteria->email;
        }
        if ($this->criteria->address) {
            $query []= '"'.$this->criteria->address.'"';
        }
        if ($this->criteria->school) {
            $query []= $this->criteria->school;
        }
        if ($this->criteria->company) {
            $query []= $this->criteria->company;
        }
        if ($this->criteria->site) {
            $query []= "site:".$this->criteria->site;
        }

        $queryString = http_build_query([
            'key'=>$googleDeveloperKey->value,
            'cx'=>$googleCustomSearchKey->value,
            'fields'=>'items(link,title),queries,searchInformation',
            'q'=>implode(" ", $query),
        ]);

        $url = "https://www.googleapis.com/customsearch/v1?".$queryString;
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

        if (!$response['body']) {
            return $list;
        }

        $content = json_decode($response['body'], true);

        if (is_null($content) || !array_key_exists("items", $content)) {
            return $list;
        }

        $results = $content['items'];

        $resultsCount = 0;
        foreach($results as $res) {
            if ($resultsCount >= $this->maxResults) {
                break;
            }


            $result = new SearchResult($res['link']);
            $result->title = $res['title'];
            $result->orderInList = $resultsCount;


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
