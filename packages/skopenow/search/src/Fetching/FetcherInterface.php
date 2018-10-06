<?php

namespace Skopenow\Search\Fetching;

use Skopenow\Search\Models\CriteriaInterface;
use Skopenow\Search\Models\SearchListInterface;
use Skopenow\Search\Models\SearchResultInterface;

interface FetcherInterface
{
    /**
     * @const string The selected account for search
     */
    const MAIN_SOURCE_NAME = "";

    public function __construct(CriteriaInterface $criteria);
    public function execute(): bool;
    public function getOutput(): SearchListInterface;
    public function loadProfileInfo(SearchResultInterface $result, string $html = '');
}
