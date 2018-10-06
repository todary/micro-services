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

class GoogleimageFetcher extends AbstractFetcher
{
    /**
     * @const string The selected account for search
     */
    const MAIN_SOURCE_NAME = 'googleimage';
    const NO_RESULTS_NEEDLE = 'Your search did not match any documents.';
    const RESULTS_START_STR = 'Pages that include matching images';
    const RESULTS_FILTER = [
        'http://webcache',
        'https://webcache',
        'https://translate',
    ];

    public $maxRequests = 3;

    public $availableProfileInfo = ['name'];

    protected function prepareRequest()
    {
        $url = 'https://www.google.com/searchbyimage?';
        $parameters = [
            'image_url' => $this->criteria->url,
        ];
        $request = ['url' => $url . http_build_query($parameters)];
        return $request;
    }

    protected function makeRequest()
    {
        try {
            $entry = loadService('HttpRequestsService');
            $content = [];
            for ($i = 0; $i < $this->maxRequests; $i++) {
                $url = $this->request['url'] . '&start=' . $i * 10;
                $response = $entry->fetch($url, 'GET');
                $response->getBody()->rewind();
                $body = $response->getBody()->getContents();
                if (stripos($body, self::NO_RESULTS_NEEDLE)) {
                    break;
                }

                //remove suggested urls at the first page
                $content[] = $i == 0 ? strstr($body, self::RESULTS_START_STR) : $body;
            }

            return ['body' => $content];
        } catch (\Exception $ex) {
            return ['body' => []];
        }
    }

    private function isValidUrl($div): bool
    {
        switch ($div->href) {
            case '#':
            case 'javascript:;':
                return false;
                break;
        }

        if (filter_var($div->href, FILTER_VALIDATE_URL, FILTER_FLAG_PATH_REQUIRED) == false) {
            return false;
        }

        foreach (self::RESULTS_FILTER as $filter) {
            if (strpos($div->href, $filter) !== false) {
                return false;
            }
        }

        return true;
    }

    protected function processResponse($response): SearchListInterface
    {
        $list = new SearchList(self::MAIN_SOURCE_NAME);

        $list->setUrl($this->request['url']);

        if (empty($response['body'])) {
            return $list;
        }

        $res = implode("\n\n", $response['body']);

        $html = str_get_html($res);
        $divs = $html->find('div[class=srg] div[class=g] div[class=rc] a');
        $resultsCount = 0;

        foreach ($divs as $key => $div) {
            if ($resultsCount >= $this->maxResults) {
                break;
            }

            if ($this->isValidUrl($div)) {
                $results[] = $div->href;

                $div = $div->href;

                $result = new SearchResult($div);
                $result->orderInList = $resultsCount;

                if ($this->onResultFound($result)) {
                    $list->addResult($result);
                }

                $resultsCount++;
            }
        }

        return $list;
    }
}
