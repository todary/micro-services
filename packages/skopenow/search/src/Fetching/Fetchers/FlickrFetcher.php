<?php

/**
 * Flickr search
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

class FlickrFetcher extends AbstractFetcher
{
    
    /**
     * @const string The selected account for search
     */
    const MAIN_SOURCE_NAME = "flickr";
    
    protected function prepareRequest() 
    {
        $url = "https://www.flickr.com/search/people/?username=" . urlencode($this->criteria->first_name . " " . $this->criteria->last_name);
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
        
        $searchdata = [];
        $pattern = "/modelExport:\\s*(.*),/";
         
        if(preg_match($pattern, $response['body'], $match))
        {
            $result = html_entity_decode(($match[1]));  //// to convert any special character
            $resultarray = json_decode($result, true);
            
            if(!empty($resultarray['search-contacts-models']))
            {
                $data = $resultarray['search-contacts-models'];
                foreach ($data as $key => $value) {
                    if(!empty($data[$key]['contacts']['_data']))
                    {
                        $searchdata = $data[$key]['contacts']['_data'];
                        break;
                    }
                }
            }
        }
        
        if(!$searchdata) {
            return $list;
        }
        
        $resultsCount = 0;
        foreach ($searchdata as $key => $rs) {
            if ($resultsCount >= $this->maxResults) {
                break;
            }
            
            $url = '';
            $image = '';
            $id = '';
            
            if(!empty($searchdata[$key]['id']))
            {
                $id = $searchdata[$key]['id'];
                $url = "https://www.flickr.com/photos/".$id;
            }
            if(!empty($searchdata[$key]['pathAlias'])) {
                $url = "https://www.flickr.com/photos/".$searchdata[$key]['pathAlias'];
            }
            $result = new SearchResult($url, true);
            $result->username = $rs['username'];
            
            
            if (!empty($searchdata[$key]['iconfarm'])) {
                $iconfarm = $searchdata[$key]['iconfarm'];
                $iconserver = $searchdata[$key]['iconserver'];
                $image = "https://farm".$iconfarm.".staticflickr.com/".$iconserver."/buddyicons/".$id.".jpg";
            } else {
                $image = "https://www.flickr.com/images/buddyicon08.png";
            }
            
            $result->screenshotUrl = $url;
            $result->orderInList = $resultsCount;
            
            $result->image = $image;
            
            if (!empty($rs['realname'])) {
                $result->addName(Name::create(['full_name' => $rs['realname']], $result->mainSource));
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