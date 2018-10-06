<?php

/**
 * Pinterest search
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

class PinterestFetcher extends AbstractFetcher
{
    public $availableProfileInfo = ["name", "location", "image", "insite_links"];
    /**
     * @const string The selected account for search
     */
    const MAIN_SOURCE_NAME = "pinterest";
    
    protected function prepareRequest() 
    {
        $url = "http://www.pinterest.com/search/people/?q=" . urlencode($this->criteria->first_name . " " . $this->criteria->last_name);
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
        
        if(!isset($content['resource_data_cache'][0]['data']['results'])) {
            return $list;
        }
        
        $results = $content['resource_data_cache'][0]['data']['results'];
        
        $resultsCount = 0;
        foreach($results as $res) {
            if ($resultsCount >= $this->maxResults) {
                break;
            }
            
            $username = $res['username'];
            $url = "http://pinterest.com/".$username;
            
            $result = new SearchResult($url);
            $result->username = $username;
            $result->image = $res['image_xlarge_url'];
            
            if($res['full_name']) {                
                $result->addName(Name::create(['full_name' => $res['full_name']], $result->mainSource));
            }      

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