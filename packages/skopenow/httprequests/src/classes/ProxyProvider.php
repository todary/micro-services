<?php
namespace Skopenow\HttpRequests;

use GuzzleHttp\Exception\RequestException;

use Skopenow\HttpRequests\Interfaces\ProxyProviderInterface;

use Skopenow\HttpRequests\ProxyData;

/**
* Proxy provider return data for proxy for given host
*/
class ProxyProvider implements ProxyProviderInterface
{
    protected $client;
    protected $providerURL;

    public function __construct($providerURL, \GuzzleHttp\Client $httpClient)
    {
        $this->providerURL = $providerURL;
        $this->client = $httpClient;
    }

    /**
     * return proxy data
     * @param  string      $host               host to get proxy for
     * @param  array       $escapeThisAccounts proxies to ignore
     * @return ProxyDataInterface
     */
    public function getProxy(
        string $host,
        string $for = "",
        array $escapeThisAccounts = []
    ) {
        $query = [
            'host' => urlencode($host),
            'delay' => 5,
            'for' => $for,
            'person_id' => 0
        ];

        if (!empty($escapeThisAccounts)) {
            $query['except'] = rawurlencode(implode(',', $escapeThisAccounts));
        }
        
        $response = $this->client->request(
                'GET',
                $this->providerURL,
                [ 'query' => $query ]
            );


        $response->getBody()->rewind();
        $proxyData = json_decode($response->getBody()->getContents(), true);

        if (!$proxyData || !isset($proxyData['status']) || !$proxyData['status']) {
            return false;
        }

        $proxyData = new ProxyData($proxyData);
        return $proxyData;

    }
}
