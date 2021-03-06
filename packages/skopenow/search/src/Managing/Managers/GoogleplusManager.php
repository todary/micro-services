<?php

namespace Skopenow\Search\Managing\Managers;

use Skopenow\Search\Managing\AbstractManager;
use Skopenow\Search\Fetching\FetcherInterface;
use Skopenow\Search\Models\DataPointInterface;
use Skopenow\Search\Models\SearchResultInterface;

class GoogleplusManager extends AbstractManager
{
    /**
     * @const string The selected account for search
     */
    const MAIN_SOURCE_NAME = "googleplus";
    
    /**
     * @var FetcherInterface Source fetcher
     */
    protected $fetcher;
}
