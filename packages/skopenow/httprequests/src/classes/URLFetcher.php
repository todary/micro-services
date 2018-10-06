<?php
namespace Skopenow\HttpRequests;

use GuzzleHttp\Pool;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;

use Skopenow\HttpRequests\Interfaces\URLFetcherInterface;
use Cache;

/**
* URL Fetcher responsible for fetching concurrent fetchables
*/
class URLFetcher implements URLFetcherInterface
{
    protected $client;
    protected $proxyProvider;
    protected $config;
    protected $logger;
    
    const MAX_CONCURRENCY = 20;

    public function __construct(\GuzzleHttp\Client $client, \Monolog\Logger $logger = null)
    {
        $this->client = $client;
        $this->logger = $logger;
    }

    /**
     * Fetch given fetchables
     * @param  array  $fetchables array of fetchables must implement \Skope\FetchableInterface
     */
    public function fetch(array $fetchables)
    {
        $requests = [];
        $responses = [];

        $fn = function ($client, $fetchable) {
            return function () use ($client, $fetchable) {
                return $client->sendAsync($fetchable->getRequest(), $fetchable->getRequestOptions());
            };
        };
        
        $urls = [];
        $cachedPromises = [];
        $requestFetchables = [];
        foreach ($fetchables as $fetchable) {
            $options = $fetchable->getRequestOptions();
            if (($response = $this->getMocks($fetchable))) {
                $fetchable->onFetchSuccess($response);

                if ($this->logger) {
                    $this->logger->info(
                        "Request ". $this->getRequestURL($fetchable->getRequest()) ." fetched from mock"
                    );
                }
                \GuzzleHttp\Promise\queue()->run();
            } else if (($response = $this->getCached($fetchable)) && !isset($options['no_cache'])) {
                if (env('APP_ENV') == "testing") {
                    throw new \Exception($fetchable->getRequestURL());
                    echo "\n\n\033[33mWarning: requesting " . $fetchable->getRequestURL() . " directly in testing environment!\033[0m\n";
                    debug_print_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
                }

                $fetchable->onFetchSuccess($response);

                if ($this->logger) {
                    $this->logger->info(
                        "Request ". $this->getRequestURL($fetchable->getRequest()) ." fetched from cache"
                    );
                }
                \GuzzleHttp\Promise\queue()->run();
            } else {
                if (env('APP_ENV') == "testing") {
                    throw new \Exception($fetchable->getRequestURL());
                    echo "\n\n\033[33mWarning: requesting " . $fetchable->getRequestURL() . " directly in testing environment!\033[0m\n";
                    debug_print_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
                }

                $requests[] = $fn($this->client, $fetchable);
                $requestFetchables[] = $fetchable;
                $urls[] = $fetchable->getRequest()->getUri()->__toString();
            }
        }


        if (empty($requests)) {
            return;
        }

        if ($this->logger) {
            $this->logger->info(
                "Start proccessing requests",
                array('urls' => $urls)
            );
        }
        
        $self = $this;
        $pool = new Pool($this->client, $requests, [
            'concurrency' => $this->getMaxConcurreny(),
            'fulfilled' => function ($response, $index) use (&$requestFetchables, $self) {
                // this is delivered each successful response
                $requestFetchables[$index]->onFetchSuccess($response);
                
                //store in cache
                $self->storeInCache($requestFetchables[$index]);

                if ($self->logger) {
                    $self->logger->info(
                        "Request ". $self->getRequestURL($requestFetchables[$index]->getRequest()) ." succeeded"
                    );
                }
            },
            'rejected' => function ($reason, $index) use (&$requestFetchables, $self) {
                // this is delivered each failed request
                $requestFetchables[$index]->onFetchFailure($reason);
                
                if ($self->logger) {
                    $self->logger->info(
                        "Request ". $self->getRequestURL($requestFetchables[$index]->getRequest()) ." failed",
                        array('reason' => $reason->getMessage())
                    );
                }
            },
        ]);

        // Initiate the transfers and create a promise
        $promise = $pool->promise();

        // Force the pool of requests to complete.
        $promise->wait();
    }


    protected function getMaxConcurreny()
    {
        return self::MAX_CONCURRENCY;
    }
    
    /**
     * Return url for given request
     * @param  string $url url string  for example http://www.example.com/example?p=10
     * @return string $host host without subdomain
     */
    protected function getRequestURL($request) :string
    {
        return $request->getUri()->__toString();
    }

    /**
     * check if fetchable is cached
     * @param  Fetchable  $fetchable
     * @return boolean
     */
    protected function getCached($fetchable)
    {
        $options = $fetchable->getRequestOptions();
        
        if (isset($options['no_cache'])) {
            return null;
        }

        $cacheKey = $fetchable->getCacheKey();
        if ($response = Cache::get($cacheKey)) {
            return \GuzzleHttp\Psr7\parse_response($response);
        }

        return null;
    }

    /**
     * store fetchable response in cache
     * @param  Fetchable $fetchable
     */
    protected function storeInCache($fetchable)
    {
        $cacheKey = $fetchable->getCacheKey();
        $response = $fetchable->getResponse();
        if (!$response->getBody()->isSeekable()) {
            $response->getBody()->rewind();
            $response = $response->withBody(
                \GuzzleHttp\Psr7\stream_for($response->getBody()->getContents())
            );
            $response->getBody()->rewind();
        }
        $options = $fetchable->getRequestOptions();
       
        if (isset($options['cache_time'])) {
            $cacheTime = $options['cache_time'];
        } else if (env('APP_ENV') == "local") {
            $cacheTime = 60 * 24;
        } else {
            $cacheTime = 10;
        }

        Cache::put($cacheKey, \GuzzleHttp\Psr7\str($response), $cacheTime);
    }

    /**
     * check if fetchable is mocked
     * @param  Fetchable  $fetchable
     * @return boolean
     */
    protected function getMocks($fetchable)
    {
        $url = $fetchable->getRequestURL();
        $url = rtrim($url, '/?&');
        $key = "HTTPRequests.mock.".md5(str_replace("https://", "http://", $url));
        if ($mocked = config($key)) {
            $response = implode("\n", $mocked['headers']) . "\n\n" . $mocked['contents'];
            return \GuzzleHttp\Psr7\parse_response($response);
        }

        return null;
    }
}
