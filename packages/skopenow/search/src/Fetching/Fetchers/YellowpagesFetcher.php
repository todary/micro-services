<?php

/**
 * Yellowpages search
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

class YellowpagesFetcher extends AbstractFetcher
{
    /**
     * @const string The selected account for search
     */
    const MAIN_SOURCE_NAME = "yellowpages";
    
    protected function prepareRequest() 
    {
        $url = "http://people.yellowpages.com/reversephonelookup?phone=".$this->criteria->phone;
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
        
        $yellowPagesData = $response['body'];
        
        $yellowPagesData = @substr($yellowPagesData, strpos($yellowPagesData, "<"));
        
        $html = str_get_html($yellowPagesData);
        
        if (!$html) {
            return $list;
        }

        $results = $html->find("#result-container .result-left");
        
        if (!$results) {
            return $list;
        }
        
        
        $resultsCount = 0;
        foreach ($results as $res) {
            if ($resultsCount >= $this->maxResults) {
                break;
            }
            
            $address = explode(",", $res->find(".address", 0)->innertext);
            
            if (count($address) >= 3) {
                $addressStreet = explode(" ", trim($address[2]), 3);
                $city = trim($address[1]) . ", " . trim($addressStreet[0]);
                
                $url = $res->find("a", 0)->href;
                
                if ($url) {
                    $url = "http://people.yellowpages.com" . $url;
                }
                
                $result = new SearchResult($url);
                $result->orderInList = $resultsCount;
                $result->setIsProfile(false);
                
                $fullName = $res->find("a", 0)->innertext;
                
                if($fullName) {
                    $result->addName(Name::create(["full_name"=>$fullName], $result->mainSource));
                }
                
                if($city) {
                    $result->addLocation(Address::create(["full_address"=>$city], $result->mainSource));
                }
                
                if ($this->onResultFound($result)) {
                    $list->addResult($result);
                }

                if ($this->onDataPointFound(new DataPoint())) {
                }
            
                $resultsCount++;
            }
        }
        
        return $list;
    }
}