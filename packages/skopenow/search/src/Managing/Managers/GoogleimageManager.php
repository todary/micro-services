<?php

namespace Skopenow\Search\Managing\Managers;

use Skopenow\Search\Fetching\FetcherInterface;
use Skopenow\Search\Managing\AbstractManager;
use Skopenow\Search\Models\SearchResultInterface;

class GoogleimageManager extends AbstractManager
{
    /**
     * @const string The selected account for search
     */
    const MAIN_SOURCE_NAME = 'googleimage';

    /**
     * @var FetcherInterface Source fetcher
     */
    protected $fetcher;

    protected function checkResult(SearchResultInterface $result)
    {
        return true;
    }
}
