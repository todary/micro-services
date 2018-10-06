<?php

/**
 * Foursquare search
 * @author Wael Salah
 * @package Search
 * @subpackage Fetching
 */

namespace Skopenow\Search\Fetching\Fetchers;

use App\DataTypes\Name;
use Skopenow\Search\Fetching\AbstractFetcher;
use Skopenow\Search\Models\DataPoint;
use Skopenow\Search\Models\SearchList;
use Skopenow\Search\Models\SearchListInterface;
use Skopenow\Search\Models\SearchResult;

class InstagramFetcher extends AbstractFetcher
{
    /**
     * @const string The selected account for search
     */
    const MAIN_SOURCE_NAME = 'instagram';

    protected function prepareRequest()
    {
        $url = 'https://www.instagram.com/web/search/topsearch/?';
        $parameters = [
            'context' => 'blended',
            'query' => $this->criteria->full_name,
        ];
        $request = ['url' => $url . http_build_query($parameters)];
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

    protected function processResponse($response): SearchListInterface
    {
        $list = new SearchList(self::MAIN_SOURCE_NAME);

        $list->setUrl($this->request['url']);

        if (!$response['body']) {
            return $list;
        }

        $res = json_decode($response['body'], true);

        if (empty($res['users'])) {
            return $list;
        }

        $resultsCount = 0;

        foreach ($res['users'] as $key => $ins) {
            // dd($ins['user']);
            if ($resultsCount >= $this->maxResults) {
                break;
            }
            $ins = $ins['user'];

            $result = new SearchResult('http://instagram.com/' . $ins['username'], true);
            $result->orderInList = $resultsCount;
            $result->username = $ins['username'];
            if (!empty($ins['full_name'])) {
                $result->addName(Name::create(['full_name' => $ins['full_name']], $result->mainSource));
            }
            $result->image = $ins['profile_pic_url'];

            if ($this->onResultFound($result)) {
                $list->addResult($result);
            }

            $resultsCount++;
        }

        return $list;
    }
}
