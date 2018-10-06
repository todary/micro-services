<?php

namespace Skopenow\Search\Fetching\Fetchers\Whois;

interface ReverseFetcherInterface
{
    public function makeRequest(string $url);

    public function processResponse(string $body, $criteria);
}
