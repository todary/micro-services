<?php

/**
 * Slideshare search
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

class SlideshareFetcher extends AbstractFetcher
{
    /**
     * @const string The selected account for search
     */
    const MAIN_SOURCE_NAME = "slideshare";
    
    protected function prepareRequest() 
    {
        $url = "http://www.slideshare.net/search/?q=".urlencode($this->criteria->first_name . " " . $this->criteria->last_name);
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
        
        $detailsPattern = '/<div[^>]*?class=[\'"][^\'"\s]*?\W*details\W*[^\'"\s]*?[\'"][^>]*?>(.*?<\/a>.*?)<\/div>/s';
        $countDetails = preg_match_all($detailsPattern, $response['body'], $results, PREG_SET_ORDER, 0);
        
        if(!$countDetails) {
            return $list;
        }

        
        $resultsCount = 0;
        foreach($results as $detail) {
            if ($resultsCount >= $this->maxResults) {
                break;
            }
            
            
            $anchorPattern = '/<a .*?href\s*=\s*[\'"]\s*\/([^\'"\/]+)/s';
            preg_match($anchorPattern, $detail[0], $anchorMatch, PREG_OFFSET_CAPTURE, 0);
            $username = "";
            if(isset($anchorMatch[1][0])) {
                $username = $anchorMatch[1][0];
            }
            $url = "http://www.slideshare.net/" . $username;
            
            $fullname = "";
            $namePattern = '/<div[^>]*?class=[\'"][^\'"\s]*?\W*author\W*[^\'"\s]*?[\'"][^>]*?>\s*(.*?)\s*<\/div>/s';
            preg_match($namePattern, $detail[0], $nameMatch, PREG_OFFSET_CAPTURE, 0);
            if(isset($nameMatch[1][0])) {
                $fullname = $nameMatch[1][0];
            }
            
            $result = new SearchResult($url);
            $result->orderInList = $resultsCount;
            $result->username = $username;
            
            if(!empty($fullname) && count(explode(" ", $fullname))>1) {
                $result->addName(Name::create(["full_name"=>$fullname],$result->mainSource));
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