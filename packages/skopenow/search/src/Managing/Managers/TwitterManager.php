<?php

namespace Skopenow\Search\Managing\Managers;

use Skopenow\Search\Managing\AbstractManager;
use Skopenow\Search\Fetching\FetcherInterface;
use Skopenow\Search\Models\DataPointInterface;
use Skopenow\Search\Models\SearchResultInterface;

class TwitterManager extends AbstractManager
{
    /**
     * @const string The selected account for search
     */
    const MAIN_SOURCE_NAME = "twitter";

    /**
     * @const bool decide to run On Result Save event.
     */
    const Run_Main_Result_Event = true;

    /**
     * @var FetcherInterface Source fetcher
     */
    protected $fetcher;
}
