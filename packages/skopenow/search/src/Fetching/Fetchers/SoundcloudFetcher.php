<?php

/**
 * Soundcloud search
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
use App\DataTypes\Address;

class SoundcloudFetcher extends AbstractFetcher
{
    
    /**
     * @const string The selected account for search
     */
    const MAIN_SOURCE_NAME = "soundcloud";
    
    private $_query;
    
    protected function prepareRequest() 
    {
        $url = "https://m.soundcloud.com/";
        $entry = loadService('HttpRequestsService');
        $options = [ 
                'headers' => ['user-agent' => 'Mozilla/5.0 (MeeGo; NokiaN9) AppleWebKit/534.13 (KHTML, like Gecko) NokiaBrowser/8.5.0 Mobile Safari/534.13']
        ];
        $response = $entry->fetch($url, 'GET', $options);
        $response->getBody()->rewind();
        $content = $response->getBody()->getContents();
        
        preg_match("/client_id:[\\\"'](.+?)[\\\"']/", $content, $matches);
        
        if(!isset($matches[1])){
            return ['url' => ''];
        }
        
        $client_id = $matches[1];
        
        $names = [];
        $names[] = $this->criteria->first_name;
        if(!empty($this->criteria->middle_name)) {
            $names[] = $this->criteria->middle_name;
        }
        $names[] = $this->criteria->last_name;
        
        
        $location = [];
        if(!empty($this->criteria->state)) {
            $location[] = $this->criteria->state;
        } elseif(!empty($this->criteria->city)) {
            $location[] = $this->criteria->city;
        }
        
        $query = "q=" .urlencode(implode(" ", $names)).(!empty($location)?"&filter.place=".urlencode(trim(implode("", $location))):"");
        
        $this->_query = $query;
        
        $url = "https://api-mobi.soundcloud.com/search/users?".$query."&client_id=".$client_id."&format=json&app_version=1464963889";
        $request = ['url' => $url];
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
        if(empty($this->request['url'])) {
            return $list;
        }
        
        $list->setUrl('https://soundcloud.com/search/people?'.$this->_query);
        
        $collection = json_decode($response['body'], true);
                
        if(!is_array($collection) || empty($collection) || (isset($collection['collection']) && empty($collection['collection'])) || $collection['total_results'] <= 0) {
            return $list;
        }
                
        $resultsCount = 0;
        $results = $collection['collection'];
        
        foreach($results as $k => $rs) {
            if ($resultsCount >= $this->maxResults) {
                break;
            }
            
            $result = new SearchResult($rs['permalink_url']);
            $result->username = $rs['username'];
            if($rs['kind'] == 'user') {
                $result->setIsProfile(true);
            }
            $result->screenshotUrl = $rs['permalink_url'];
            $result->orderInList = $resultsCount;
            $result->image = str_replace("http://", "https://", $rs['avatar_url']);
            
            if(!empty($rs['full_name'])){
                $result->addName(Name::create(["full_name"=>$rs['full_name']],$result->mainSource));
            }
            
            if(!empty($rs['city'])) {
                $address = $rs['city'].(!empty($rs['country_code'])?", ".$rs['country_code']:"");
                $result->addLocation(Address::create(["full_address"=>$address],$result->mainSource));
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