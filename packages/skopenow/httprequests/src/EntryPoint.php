<?php
/**
 * Http Requests client code
 *
 * PHP version 7.0
 *
 * @category Micro_Services-phase_1
 * @package  Validation
 * @author   Mahmoud Mgady <mahmoud.magdy@queentecksolutions.com>
 * @license  QueenTechSolutions, Inc.
 * @link     http://www.queentecksolutions.com
 **/
namespace Skopenow\HttpRequestsService;

use GuzzleHttp\Psr7\Request;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

use GuzzleHttp\HandlerStack;
use GuzzleHttp\Client;

use Skopenow\HttpRequests\RequestProcessHandler;
use Skopenow\HttpRequests\RetryMiddleware;
use Skopenow\HttpRequests\URLFetcher;
use Skopenow\HttpRequests\Fetchable;

use Kevinrob\GuzzleCache\CacheMiddleware;

/**
 * Http Requests client code
 *
 * @category Micro_Services-phase_1
 * @package  Http Requests
 * @author   Mahmoud Mgady <mahmoud.magdy@queentecksolutions.com>
 * @license  QueenTechSolutions, Inc.
 * @link     http://www.queentecksolutions.com
 **/
class EntryPoint
{
   
    protected $fetchables = [];
    
    /**
     * [$urlFetcher description]
     * @var [type]
     */
    protected $urlFetcher;

    /**
     * EntryPoint constructor
     *
     * @return void
     */
    public function __construct()
    {
        $this->initializeURlFetcher();
    }

    /**
     * Initialize url fetcher
     */
    protected function initializeURlFetcher()
    {
        $providerURL = "http://ec2-52-26-121-126.us-west-2.compute.amazonaws.com:8080/pickup.php";
        $proxyProvider = new \Skopenow\HttpRequests\ProxyProvider($providerURL, new \GuzzleHttp\Client(['timeout' => 10]));

        if (env('APP_ENV') == 'local' || env('APP_ENV') == 'testing') {
            $logger = new Logger('skope');

            // $formatter = new \Monolog\Formatter\JsonFormatter();
            
            $streamHandler = new StreamHandler(__DIR__.'/logs/my.log', Logger::DEBUG);
            // $streamHandler->setFormatter($formatter);
            $logger->pushHandler($streamHandler);
        } else {
            $logger = null;
        }

        $requestProcessHandler = new RequestProcessHandler($proxyProvider, $logger);
        $requestProcessHandler->setDefaultRetryDelay(0);
        $requestProcessHandler->setDefaultNumberOfRetries(10);

        $options = [
            'facebook'  => [
                'connection_timeout' => 4,
                'timeout' => 4,
            ],
            'linkedin'  => [
                'ignore_auto_select_ip' => true,
                'connection_timeout' => 2,
                'timeout' => 2,
            ],
            'twitter'   => [
                'headers' => [
                    "Connection" => "close",
                    "X-Requested-With" => "XMLHttpRequest",
                    "X-Push-State-Request"=>"true"
                ]
            ],
            'pinterest' => [
                'headers' => [
                    "Connection" => "close",
                    "X-Requested-With" => "XMLHttpRequest",
                    "X-Push-State-Request" => "true"
                ]
            ],
            'pandora' => [
                'headers' => [
                    "Accept" => "text/plain"
                ]
            ],
            'angel' => [
                'connection_timeout' => 2,
                'timeout' => 2,
            ],
        ];

        $requestProcessHandler->addSourceConfig('linkedin.com', 2, 20, RequestProcessHandler::USE_PROXY_ALWAYS, $options['linkedin']);

        $requestProcessHandler->addSourceConfig('facebook.com', 2, 20, RequestProcessHandler::USE_PROXY_ALWAYS, $options['facebook']);

        $requestProcessHandler->addSourceConfig('twitter.com', 2, 20, RequestProcessHandler::USE_PROXY_NEVER, $options['twitter']);

        $requestProcessHandler->addSourceConfig('pinterest.com', 2, 20, RequestProcessHandler::USE_PROXY_NEVER, $options['pinterest']);

        $requestProcessHandler->addSourceConfig('pandora.com', 2, 20, RequestProcessHandler::USE_PROXY_NEVER, $options['pandora']);

        $requestProcessHandler->addSourceConfig('plus.google.com', 2, 20, RequestProcessHandler::USE_PROXY_ALWAYS);
        $requestProcessHandler->addSourceConfig('youtube.com', 2, 20, RequestProcessHandler::USE_PROXY_ALWAYS);
        $requestProcessHandler->addSourceConfig('pipl.com', 1, 20, RequestProcessHandler::USE_PROXY_ALWAYS);
        $requestProcessHandler->addSourceConfig('api.pipl.com', 1, 2000, RequestProcessHandler::USE_PROXY_AFTER_FAILURE);
        $requestProcessHandler->addSourceConfig('google.com', 2, 20, RequestProcessHandler::USE_PROXY_ALWAYS);
        $requestProcessHandler->addSourceConfig('411locate.com', 2, 20, RequestProcessHandler::USE_PROXY_ALWAYS);
        $requestProcessHandler->addSourceConfig('peoplebyname.com', 2, 20, RequestProcessHandler::USE_PROXY_ALWAYS);
        $requestProcessHandler->addSourceConfig('slideshare.com', 1, 20, RequestProcessHandler::USE_PROXY_ALWAYS);
        $requestProcessHandler->addSourceConfig('slideshare.net', 1, 20, RequestProcessHandler::USE_PROXY_ALWAYS);
        $requestProcessHandler->addSourceConfig('angel.co', 1, 20, RequestProcessHandler::USE_PROXY_ALWAYS, $options['angel']);
        $requestProcessHandler->addSourceConfig('angel.co.skpaccount', 1, 20, RequestProcessHandler::USE_PROXY_ALWAYS, $options['angel']);
        $requestProcessHandler->addSourceConfig('angel.co.skpblocked', 1, 20, RequestProcessHandler::USE_PROXY_ALWAYS, $options['angel']);
        $requestProcessHandler->addSourceConfig('findthecompany.com', 2, 20, RequestProcessHandler::USE_PROXY_ALWAYS);
        $requestProcessHandler->addSourceConfig('fastcompany.com', 2, 20, RequestProcessHandler::USE_PROXY_ALWAYS);

        $retryMiddleware = new RetryMiddleware($requestProcessHandler);


        $stack = HandlerStack::create();

        // $stack->push(new CacheMiddleware(
        //     new PrivateCacheStrategy(
        //       new LaravelCacheStorage(
        //         Cache::store('file')
        //       )
        //     )
        // ), 'cache');

        if ($logger) {
            $messageFormat = "{method} \n {req_headers} \n {uri} \n  HTTP/{version} \n {req_body}";
            // 'RESPONSE: {code} - {res_body}';
            $loggerMiddleware = \GuzzleHttp\Middleware::log(
                    $logger,
                    new \GuzzleHttp\MessageFormatter($messageFormat)
                );
            $stack->unshift($loggerMiddleware);
        }

        $stack->unshift($retryMiddleware->getGuzzleMiddleware());

        $guzzleClient = new Client(['handler' => $stack]);
        $this->urlFetcher = new URLFetcher($guzzleClient, $logger);
    }

    public function createRequests($requests)
    {
        //
        
    }

    /**
     * fetch single url
     * @param  string $url     
     * @param  string $method  http method GET | POST | ...
     * @param  array  $options guzzle http options
     * @return GuzzleHttp\Psr7\Response|null          
     */
    public function fetch($url, $method = 'GET', $options = [])
    {
        $fetchable = new Fetchable(new request($method, $url), $options['body']??null, $options);
        $this->urlFetcher->fetch([$fetchable]);
        if ($fetchable->isFullfiled()) {
            $fetchable->getResponse()->getBody()->rewind();
            return $fetchable->getResponse();
        } else {
            throw $fetchable->getFailureReason();
        }
    }

    /**
     * create single request
     * @param  string   $url
     * @param  mixed    $data    data to maintain with each request
     * @param  string   $method  method 'GET', 'POST', 'PUT', 'DELETE', ...
     * @param  array    $options array of guzzle http request options
     * @param  callable $ok      function to call on success with \Skopenow\HttpRequests\Fetchable
     * @param  callable $error   function to call on error with \Skopenow\HttpRequests\Fetchable
     */
    public function createRequest($url, $data, $method, $options, $ok, $error)
    {
        // var_dump($url, $method);
        // $url = $this->filterURLEncoding($url);
        // $url = $this->alternativeUrl($url);
        $fetchable = new Fetchable(new request($method, $url), $data, $options);
        $promise = $fetchable->getRequestPromise();
        $promise->then(

            function ($fetchable) use ($ok) {
                $ok($fetchable);
            },

            function ($fetchable) use ($error) {
                $error($fetchable);
            }
        );

        $this->fetchables[] = $fetchable;
    }

    /**
     * run all requests
     */
    public function processRequests()
    {
        $this->urlFetcher->fetch($this->fetchables);
        $this->fetchables = [];
    }


    protected function filterURLEncoding($url)
    {
        $url = preg_replace_callback(
            '/[^\w\$-_\.\+!\*\'\(\),;\/\?:@=&<>#%{}\|\^~\[\]\s]+/',
            function ($matches) {
                return urlencode($matches[0]);
            }, $url
        ) ;
        return $url ;
    }

    protected function alternativeUrl($url)
    {
        $host = parse_url($url, PHP_URL_HOST);

        if (stripos($url, 'vine.co')) {
            $patt = "#vine.co\/(u\/)?((\w|\d|[\.\-_])+)[^\/\&]*$#i";
            preg_match($patt, $url, $rt);
            if (isset($rt[2])) {
                $un = $rt[2];
            }

            if (empty($un)) {
                return false;
            }

            if (!empty($rt[1])) {
                $url = 'https://vine.co/api/users/profiles/'.$un;
            } else {
                $url = 'https://vine.co/api/users/profiles/vanity/'.$un;
            }
            return $url;
        }

        return false;
    }
}
