<?php

namespace Skopenow\Search\Fetching\Fetchers\Facebook;

use Skopenow\Search\Fetching\AbstractFetcher;
use Skopenow\Search\Fetching\FetcherInterface;
use Skopenow\Search\Models\SearchList;
use Skopenow\Search\Models\SearchListInterface;
use Skopenow\Search\Models\SearchResultInterface;
use Skopenow\Search\Models\DataPoint;
use Skopenow\Search\Models\SearchResult;

abstract class FacebookSearchFetcher extends AbstractFetcher
{
    
    /**
     * @const string The selected account for search
     */
    const MAIN_SOURCE_NAME = "facebook";
    /**
     * [$mainSearchUrl string of the search url main one.]
     * @var string
     */
    protected $mainSearchUrl = "https://www.facebook.com/search/str/";

    /**
     * [$alternativeSearchUrl string of the search url, the alternative one used when 
     *  main search url did not return results]
     * @var string
     */
    protected $alternativeSearchUrl = "https://www.facebook.com/search/people/?q=";

    /**
     * [$useAlternativeUrlwhether to use main url or not]
     * @var boolean
     */
    protected $useAlternativeUrl = false ;

    /**
     * constant to know of source allow retry or not.
     */
    const CanRetry = false;

    /**
     * [$availableProfileInfo available info should be exist]
     * @var [type]
     */
    public $availableProfileInfo =["name", "location","image","experiences","educations"];

    
    public function execute(): bool
    {
        $this->request = $this->prepareRequest();
        \Log::info("Start Request Data");
        $response = $this->makeRequest();

        \Log::info("Start processResponse");
        $searchList = $this->processResponse($response);

        if(!$searchList->getResults()->count() && static::CanRetry && !$this->useAlternativeUrl){
            $this->useAlternativeUrl = true;
            $this->execute();
        }

        $this->processProfilesInfo();
        
        $this->output = $searchList;

        return $this->output->getResults()->count() || !empty($this->output->getDataPoints());
    }

    protected function prepareRequest()
    {
        $criteria = $this->prepareUrlCriteria();
        $url = $this->createMainSearchUrl($criteria);
        
        if (static::CanRetry && $this->useAlternativeUrl) {
            $url = $this->createAlternativeSearchUrl($criteria);
        }
        $request = ["url" => $url];
        return $request;
    }

    abstract protected function prepareUrlCriteria(): string;

    abstract protected function createMainSearchUrl($criteria);

    abstract protected function createAlternativeSearchUrl($criteria);
    
    protected function makeRequest()
    {
        try{
            $entry = loadService('HttpRequestsService');
            $response = $entry->fetch($this->request['url'] , 'GET') ;
            $response->getBody()->rewind();
            return ['body'  =>  $response->getBody()->getContents()] ;
        } catch (\Exception $ex) {
            return ['body'  =>  ''];
        }
    }
    
    protected function processResponse($response): SearchListInterface
    {
        $matches1 = array();
        $matches2 = array();

        $list = new SearchList(self::MAIN_SOURCE_NAME);
        $list->setUrl($this->request['url']);

        if (! $this->checkForIssues($response)) {
            $pattern1 = "/fwb\\sfcg.*<a\\s*href=[\"']([^\"']*)[\"']/isU";
            $pattern2 = "/_gll.*<a\\s*href=[\"']([^\"']*)[\"']/iU";
            preg_match_all($pattern1, $response['body'], $matches1);
            preg_match_all($pattern2, $response['body'], $matches2);
            ## merge both matched results to get all profiles.
            $links = array_merge($matches1[1],$matches2[1]);
            ## get images 
            preg_match_all('/<img class="_1glk img" src="([^"]+)"/', $response['body'], $images);
            $images = $images[1]??[];
            
            $count=0;
            foreach ($links as $key => $link) {
                if($count >= $this->maxResults) break;

                if($this->checkLink($link)){
                    $result = $this->createResult($link,$key,count($links));
                    if(!empty($images[$key])) {
                        $result->image = html_entity_decode($images[$key]);
                    }
                    if($this->onResultFound($result)){
                        $list->addResult($result);
                        $count++;
                    }
                }
            }
        }

        return $list;       
    }

    protected function checkLink($link): bool
    {
        $status = false ;
        if(stripos($link,'facebook.com/') !== false)
            $status = true ;

        return $status ;
    }

    protected function createResult($link,$orderInList, $resultsCount)
    {
        $result = new SearchResult($link, true);
        $result->screenshotUrl = $link;
        $result->orderInList = $orderInList;
        $result->resultsCount = $resultsCount;
        $result->setIsRelative($this->criteria->is_relative??false);
        return $result;
    }

    public function setUseAlternativeUrl(bool $status = false): FetcherInterface
    {
        $this->useAlternativeUrl = $status;
        return $this;
    }

    public function getStateName(string $stateCode): string
    {
        if (empty($stateCode)) {
            return "";
        }

        $locationService = loadService("location");
        $stateName = $locationService->getStateName(new \ArrayIterator([$stateCode]));

        
        return $stateName[$stateCode];
    }

    private function checkForIssues($response)
    {
        $status = false;

        $re = '/Matches: <span><span><s>([^<]+)<\/s>/i';
        if (preg_match($re, $response['body'], $match)) {

            $status = true;
        }

        return $status;
    }
}
