<?php

/**
 * Foursquare search
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

class FoursquareFetcher extends AbstractFetcher
{
    public $availableProfileInfo = ["name", "location", "image", "insite_links"];
    /**
     * @const string The selected account for search
     */
    const MAIN_SOURCE_NAME = "foursquare";
    
    private $_apiKey;
    
    protected function prepareRequest() 
    {
        $apiAccount = getApiAccount("foursquare");
        $this->_apiKey = $apiAccount->password;
        $url = "https://api.foursquare.com/v2/users/search?name=" . urlencode($this->criteria->first_name . " " . $this->criteria->last_name) . "&oauth_token=".$apiAccount->password."&v=20160726";
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
        
        $getdata = [];
        if(!empty($response['body']))
        {
            $result = $response['body'];
            $resultarray = json_decode($result, true);
            $getdata = $resultarray['response']['results'];  
        }
        
        if(count($getdata) == 0) {
            return $list;
        }
        
        $resultsCount = 0;

        foreach ($getdata as $rs) {
            if ($resultsCount >= $this->maxResults) {
                break;
            }

            $url = "https://foursquare.com/user/" . $rs["id"];
            
            $result = new SearchResult($url);

            $result->orderInList = $resultsCount;
            $result->setIsProfile(true);
            $result->social_profile_id = $rs['id'];

            $profile_name = "";
            if(!empty($rs['firstName'])) {
                $profile_name .= $rs['firstName'];
            }
            
            if(!empty($rs['lastName'])) {
                $profile_name .= " ".$rs['lastName'];
            }
            
            if(!empty($profile_name)) {                
                $result->addName(Name::create(['full_name' => $profile_name], $result->mainSource));
            }
            
            if(!empty($rs['homeCity'])) {
                $result->addLocation(Address::create(['full_address' => $rs['homeCity']], $result->mainSource));
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

