<?php
namespace Skopenow\HttpRequests\Interfaces;

/**
*  Proxy provider
*/
interface ProxyProviderInterface
{
    /**
     * return proxy data
     * @param  string      $host               host to get proxy for
     * @param  array       $escapeThisAccounts proxies to ignore
     * @return ProxyDataInterface
     */
    public function getProxy(
        string $host,
        string $for,
        array $escapeThisAccounts
    );
}
