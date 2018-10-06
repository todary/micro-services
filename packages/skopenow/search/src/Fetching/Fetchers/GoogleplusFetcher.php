<?php

/**
 * Googleplus search
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

class GoogleplusFetcher extends AbstractFetcher
{
    public $availableProfileInfo = ["name", "location", "image", "insite_links"];
    /**
     * @const string The selected account for search
     */
    const MAIN_SOURCE_NAME = "googleplus";


    protected function prepareRequest()
    {
        $query = [];
        if ($this->criteria->full_name) {
            $query []= $this->criteria->full_name;
        }
        if ($this->criteria->email) {
            $query []= $this->criteria->email;
        }

        $queryString = implode(" ", $query);
        $obj = (object)array("100711766" => [$queryString, $this->maxResults]);
        $formData =  [[[100711766,[$obj],null,null,0]]] ;
        $post = ['f.req' => json_encode($formData)];
        $url = "https://plus.google.com/_/PlusAppUi/data?hash=" . md5(json_encode($post));
        $request = ['url' => $url, 'post' => $post];
        return $request;
    }

    protected function makeRequest()
    {
        try {
            $entry = loadService('HttpRequestsService');
            $response = $entry->fetch($this->request['url'], 'POST', ['form_params'=>$this->request['post']]);
            $response->getBody()->rewind();
            return ['body' => $response->getBody()->getContents()];
        } catch (\Exception $ex) {
            // throw $ex;
            return ['body' => ''];
        }
    }

    protected function processResponse($response) : SearchListInterface
    {
        $list = new SearchList(self::MAIN_SOURCE_NAME);

        $list->setUrl('https://plus.google.com/s/'. rawurlencode($this->criteria->full_name) .'/people');

        if (!$response['body']) {
            return $list;
        }

        $html = $response['body'];

        preg_match('/({\\"100711766.+})/s', $html, $jsonObject);

        if (!array_key_exists(0, $jsonObject)) {
            return $list;
        }

        $jsonObject = json_decode($jsonObject[0], true);

        if (!is_array($jsonObject) or
                !array_key_exists('100711766', $jsonObject) or
                !array_key_exists(1, $jsonObject['100711766']) or
                !array_key_exists(0, $jsonObject['100711766'][1])) {
            return $list;
        }

        $results = $jsonObject['100711766'][1];

        $resultsCount = 0;

        foreach ($results as $res) {
            if (!is_array($res) or !array_key_exists(0, $res) or !array_key_exists(1, $res)) {
                continue;
            }

            if ($resultsCount >= $this->maxResults) {
                break;
            }

            $image = "";
            $profileName = "";
            $url = $res[0];

            if (!empty($res[4][2][0][0])) {
                $image = $res[4][2][0][0];
            }

            if (isset($res[0])) {
                $profileName = $res[0];
            }

            $username = $res[4][0];
            if (!$username) {
                continue;
            }

            $url = !is_numeric($username)?"https://plus.google.com/+$username":"https://plus.google.com/$username";

            $result = new SearchResult($url, true);
            $result->username = $username;
            $result->orderInList = $resultsCount;
            $result->image = $image;

            if ($profileName) {
                $result->addName(Name::create(['full_name' => $profileName], $result->mainSource));
            }

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
