<?php

namespace Skopenow\Search\Fetching\Fetchers\Whois;

interface DomainFetcherInterface
{
    public function makeRequest(string $url);

    public function processResponse(string $body, $criteria);
}
