<?php
namespace Skopenow\HttpRequests\Interfaces;

/**
 * URL Fetcher Interface
 */
interface URLFetcherInterface
{
    /**
     * Fill fetchables with response data
     * @param  array of type \Skope\FetchableInterface  $fetchables
     */
    public function fetch(array $fetchables);
}
