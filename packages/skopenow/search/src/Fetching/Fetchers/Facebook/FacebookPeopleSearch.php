<?php

namespace Skopenow\Search\Fetching\Fetchers\Facebook;

use Skopenow\Search\Fetching\Fetchers\Facebook\FacebookSearchFetcher;

class FacebookPeopleSearch extends FacebookSearchFetcher 
{

    /**
     * constant CanRetry to know of source allow retry or not.
     */
    const CanRetry = false ;

    /**
     * [$mainSearchUrl string of the search url main one.]
     * @var string
     */
    protected $mainSearchUrl = "https://www.facebook.com/search/people/?q=";

    protected function prepareUrlCriteria(): string
    {
        $criteria = array(
            $this->criteria->first_name ,
            $this->criteria->middle_name ,
            $this->criteria->last_name ,
            $this->criteria->city,
            $this->getStateName($this->criteria->state),
            $this->criteria->company ,
            $this->criteria->school ,
            $this->criteria->email ,
            $this->criteria->phone ,
        );

        return implode(" ", array_filter($criteria));
    }

    protected function createMainSearchUrl($criteria) 
    {
        return $this->mainSearchUrl.rawurlencode($criteria);
    }

    protected function createAlternativeSearchUrl($criteria)
    {
        //
    }

    protected function afterResultSave(SearchResultInterface $result)
    {
        
    }
    
    
}
