<?php
namespace Skopenow\HttpRequests;

use Skopenow\HttpRequests\Interfaces\RequestProcessHandlerInterface;
use Skopenow\HttpRequests\Interfaces\ProxyProviderInterface;

/**
*
*/
class RequestProcessHandler implements RequestProcessHandlerInterface
{
    const USE_PROXY_ALWAYS = 'always';
    const USE_PROXY_AFTER_FAILURE = 'after_failure';
    const USE_PROXY_NEVER = 'never';

    protected $defaultNumberOfRetries = -1; //zero retry till success || -1 never retry
    protected $defaultRetryDelay = 100;
    protected $defaultProxyUsage = self::USE_PROXY_NEVER;
    protected $defaultOptions = [];

    protected $sourcesConfig = [];


    public function __construct(ProxyProviderInterface $proxyProvider, \Monolog\Logger $logger = null)
    {
        $this->proxyProvider = $proxyProvider;
        $this->logger = $logger;

        $this->defaultOptions = [
            'headers' => [
                'Accept' => "text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8",
                'Accept-Encoding' => "gzip, deflate",
                'Accept-Language' => "en-US,en;q=0.5",
                'Connection' => "keep-alive",
            ],
            'curl' => [
                CURLOPT_IPRESOLVE => CURL_IPRESOLVE_V4,
                CURLOPT_ENCODING => '',
                CURLOPT_SSL_VERIFYPEER => 0,
                CURLOPT_SSL_VERIFYHOST => 0,
                CURLOPT_NOSIGNAL => 1
            ],
            'connect_timeout' => app()->environment(['production'])?5:20,
            'allow_redirects' => [
                'max'             => 5,        // allow at most 5 redirects.
                // 'strict'          => true,      // use "strict" RFC compliant redirects.
                // 'referer'         => true,      // add a Referer header
                // 'protocols'       => ['https'], // only allow https URLs
                // 'on_redirect'     => $onRedirect,
                // 'track_redirects' => true
            ],
            'timeout' => app()->environment(['production'])?5:10
        ];
    }

    /**
     * Set default number of retries on failure
     * @param integer $defaultNumberOfRetries
     */
    public function setDefaultNumberOfRetries(int $defaultNumberOfRetries)
    {
        $this->defaultNumberOfRetries = $defaultNumberOfRetries;
    }

    /**
     * Set default delay before each retry
     * @param integer $defaultRetryDelay
     */
    public function setDefaultRetryDelay(int $defaultRetryDelay)
    {
        $this->defaultRetryDelay = $defaultRetryDelay;
    }

    /**
     * Set default setting for using proxy
     *
     * USE_PROXY_ALWAYS => use proxy from the first try of the request
     *
     * USE_PROXY_AFTER_FAILURE => use proxy only if request failed
     *
     * USE_PROXY_NEVER => never use proxy
     *
     * @param [type] $defaultProxyUsage [description]
     */
    public function setDefaultProxyUsage(string $defaultProxyUsage)
    {
        $this->defaultProxyUsage = $defaultProxyUsage;
    }


    /**
     * Set default guzzle options for all requests
     *
     * @param array $defaultOptions
     */
    public function setDefaultOptions(array $defaultOptions)
    {
        $this->defaultOptions = $defaultOptions;
    }

    /**
     * Add custom setting for requests from specific domain
     * @param string  $domain      request domain name
     * @param integer $noOfRetries max number of retries
     * @param integer $retryDelay  retry delay in milliseconds
     * @param string  $useProxy    proxy usage setting
     *
     *                             USE_PROXY_ALWAYS => use proxy from the first try of the request
     *
     *                             USE_PROXY_AFTER_FAILURE => use proxy only if request failed
     *
     *                             USE_PROXY_NEVER => never use proxy
     * @param array   $options     guzzle request options
     */
    public function addSourceConfig(string $domain, int $noOfRetries, $retryDelay, $useProxy, array $options = [])
    {
        $this->sourcesConfig[$domain] = [
            'no_of_retries' => $noOfRetries,
            'retry_delay' => $retryDelay,
            'use_proxy' => $useProxy,
            'options' => $options
        ];
    }

    /**
     * Decide retry according to request state
     * @param  \Psr\Http\Message\RequestInterface       $request
     * @param  array                                    $options
     * @param  \Psr\Http\Message\ResponseInterface|null $previousResponse
     * @param  \GuzzleHttp\Exception\RequestException   $previousException
     * @return boolean
     */
    public function decideRetry(
        \Psr\Http\Message\RequestInterface $request,
        array $options,
        \Psr\Http\Message\ResponseInterface $previousResponse = null,
        \GuzzleHttp\Exception\RequestException $previousException = null
    ) {

        // if ($previousResponse) {
        //     $this->logger->debug(
        //             $this->getRequestURL($request),
        //             [\GuzzleHttp\Psr7\str($previousResponse)]
        //         );
        // }

        if (isset($options['disable_retry']) && $options['disable_retry']) {
            return false;
        }

        $retries = $options['retries'];
        if (isset($options['max_retries'])) {
            $maxRetries = $options['max_retries'];
        } else {
            $maxRetries = $this->getMaxNumberOfRetries($request);
        }

        $responseCodesToRetry = [500, 503, 403];

        if (isset($options['try_proxy'])) {
            $maxRetries = 1;
        }

        if ($maxRetries < 0) {
            return false;
        }
        
        if ($maxRetries!=0 && $retries >= $maxRetries) {
            if ($this->logger && $maxRetries > 0) {
                $this->logger->debug(
                    "Max number of retries (". $retries .") reached for ",
                    array('url' => $this->getRequestURL($request))
                );
            }
            return false;
        } elseif ($previousException && (!$previousResponse || ($previousResponse && !in_array($previousResponse->getStatusCode(), [400, 404])))) {
            if ($this->logger) {
                $this->logger->debug(
                    "Retry #".($retries+1)." ".$this->getRequestURL($request)." due to exception.",
                    array('message' => $previousException->getMessage())
                );
            }
            return true;
        } elseif ($previousResponse && in_array($previousResponse->getStatusCode(), $responseCodesToRetry)) {
            if ($this->logger) {
                $this->logger->debug(
                    "Retry #". ($retries+1)." ".$this->getRequestURL($request)." due to internal server error.",
                    [/*\GuzzleHttp\Psr7\str($previousResponse)*/]
                );
            }
            return true;
        }
        return false;
    }

    /**
     * [resolveRequestOptions description]
     * @param  \Psr\Http\Message\RequestInterface       $request
     * @param  array                                    $options
     * @param  \Psr\Http\Message\ResponseInterface|null $previousResponse
     * @param  \GuzzleHttp\Exception\RequestException   $previousException
     * @return array                                    guzzle request options array
     */
    public function resolveRequestOptions(
        \Psr\Http\Message\RequestInterface $request,
        array $options,
        \Psr\Http\Message\ResponseInterface $previousResponse = null,
        \GuzzleHttp\Exception\RequestException $previousException = null
    ) {
        $options = array_merge($this->getHostOptions($request), $options);

        if (!isset($options['proxies_used'])) {
            $options['proxies_used'] = [];
        }

        if (!isset($options['retries'])) {
            $options['retries'] = 0;
        }

        $noOfRetries = $options['retries'];
        if ($noOfRetries) {
            $options['delay'] = $this->delay($request, $options, $previousResponse);
        }
        $proxyData = $this->resolveProxy($request, $options, $previousResponse);

        if ($proxyData) {
            $options['proxy'] = $proxyData->getProxyURL();
            
            $accountData = $proxyData->getAccountData();
            if ($accountData) {
                $options['account_data'] = $accountData;
            }

            $options['curl'][ CURLOPT_COOKIEFILE ] = $proxyData->getCookiesPath();
            $options['proxies_used'][] = $options['proxy'];
        }

        return $options;
    }

    public function prepareRequest(
        \Psr\Http\Message\RequestInterface $request,
        array $options,
        \Psr\Http\Message\ResponseInterface $previousResponse = null,
        \GuzzleHttp\Exception\RequestException $previousException = null
    ) {
        $url = $request->getURI()->__toString();

        if (isset($options['curl'][CURLOPT_COOKIEFILE])) {
            $request = $this->replaceCookiesKeysInHeaders($request, $options['curl'][CURLOPT_COOKIEFILE]);
        }
        
        // $request = $request->withHeader('USER-AGENT', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/53.0.2785.116 Safari/537.36');

        if (strpos($url, 'google.com')!==false && strpos($url, 'plus.google') ===false) {
            $request = $request->withHeader('USER-AGENT', "/5.0 (Windows; U; Windows NT 5.1; en-ZW) AppleWebKit/534.34 (KHTML, like Gecko)  QtWeb Internet Browser/3.8.5 http://www.QtWeb.net");
        } elseif (strpos($url, 'angel.co') !==false) {
            $request = $request->withHeader('USER-AGENT', "Mozilla/5.0 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)");
        } elseif (strpos($url, 'linkedin.com') !==false) {
            $request = $request->withHeader('USER-AGENT', "Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:56.0) Gecko/20100101 Firefox/56.0");
        } else {
            $request = $request->withHeader('USER-AGENT', "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/53.0.2785.116 Safari/537.36");
        }

        if (isset($options['headers'])) {
            foreach ($options['headers'] as $key => $value) {
                $request = $request->withHeader($key, $value);
            }
        }


        if (!empty($options['account_data'])) {
            $uri = $request->getUri();
            $query = $uri->getQuery();
            if (strpos($query, '_-DATA-_') !== false) {
                $uri = $uri->withQuery(str_replace('_-DATA-_', $options['account_data'], $query));
                $request = $request->withUri($uri, true);
            }
        }

        return $request;
    }

    protected function cookieFileToArray($cookieFile)
    {
        $cookies = file_get_contents($cookieFile);
        $lines = explode(PHP_EOL, $cookies);
        $data = [];

        // iterate over lines
        foreach ($lines as $line) {
          // we only care for non-comment, non-blank lines
            if (isset($line[0]) && $line[0] != '#' && substr_count($line, "\t") == 6) {
                // get tokens in an array
                $tokens = explode("\t", $line);

                // trim the tokens
                $tokens = array_map(function ($item) {
                    return trim($item, '"');
                }, $tokens);

                // let's convert the expiration to something readable
                // $tokens[4] = date('Y-m-d h:i:s', $tokens[4]);
                $data[] = $tokens;
            }
        }

        return $data;
    }

    protected function replaceCookiesKeysInHeaders($request, $cookiesFile)
    {
        //use cookie data in headers
        if ($cookiesFile) {
            $cookiesArray = null;
            $headers = $request->getHeaders();

            foreach ($headers as $key => $value) {
                $value = $value[0];

                preg_match('/\^\!COOKIE\!(.*)\^/', $value, $matches);
                if ($matches) {
                    if (!$cookiesArray) {
                        $cookiesArray = $this->cookieFileToArray($cookiesFile);
                    }
                    $cookieKey = $matches[1];
                    $cookieValue = null;
                    foreach ($cookiesArray as $item) {
                        if ($item[5] == $cookieKey) {
                            $cookieValue = $item[6];
                            break;
                        }
                    }

                    if ($cookieValue) {
                        $newValue=str_replace("^!COOKIE!$cookieKey^", $cookieValue, $value);
                        $request = $request->withHeader($key, $newValue);
                    }
                }
            }
        }
        return $request;
    }

    
      /**
     * Resolve proxy for given request
     * @param  array $proxiesUsed proxies used
     * @param  integer $noOfRetries [description]
     * @param  \Psr\Http\Message\RequestInterface $request     request to get proxy for
     * @param  \Psr\Http\Message\ResponseInterface $response    Response fr last try if any
     * @return \Skope\ProxyDataInterface              proxy data or null
     */
    protected function resolveProxy($request, $options, $response = null)
    {
        if (isset($options['ignore_proxy'])) {
            return null;
        }

        $proxiesUsed = isset($options['proxiesUsed'])?$options['proxiesUsed']:[];
        $noOfRetries = isset($options['retries'])?$options['retries']:0;
        $useProxy = $this->getProxyConfig($request);
        $tryProxy = $options['try_proxy']??false;
        if ($useProxy == self::USE_PROXY_ALWAYS
            || (
                (
                    $tryProxy
                    || $useProxy == self::USE_PROXY_AFTER_FAILURE
                ) && $noOfRetries > 0
            )
        ) {
            if ($tryProxy) {
                $source = 'request.fallback';
            } else {
                $source = $this->getDomainFromHost($request->getUri()->getHost());
            }
            $proxy = $this->proxyProvider->getProxy($source, $request->getUri(), $proxiesUsed);
            
            
            if ($this->logger && $proxy) {
                $this->logger->debug(
                    'Using proxy for retry #'.$noOfRetries." ".$this->getRequestURL($request),
                    array('proxy' => $proxy->getProxyURl())
                );
            }
            return $proxy;
        }
        return null;
    }

    /**
     * Return delay used to retry request
     * @param  int $retries  number of retries
     * @param  \Psr\Http\Message\RequestInterface $request
     * @param  \Psr\Http\Message\ResponseInterface $response
     * @return int Delay in milliseconds
     */
    protected function delay($request, $options, $response)
    {
        $retryDelay = $this->getRetryDelay($request);
        if ($this->logger) {
            $this->logger->info(
                'Delay '.$retryDelay.' milliseconds for retry #'.$options['retries']." ".$this->getRequestURL($request)
            );
        }
        return $retryDelay;
    }

    /**
     * Extract domain from given host
     * @param  string $url url string  for example http://www.example.com/example?p=10
     * @return string $host host without subdomain
     */
    protected function getDomainFromHost(string $host) :string
    {
        $domain = str_replace("api.", "", $host);
        $domain = str_replace("www.", "", $domain);
        $domain = str_replace("m.", "", $domain);
        return strtolower($domain);
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
     * Get maximum number of retries for request
     * @param  \Psr\Http\Message\RequestInterface $request psr-7 request
     * @return int          number of retries for request
     */
    protected function getMaxNumberOfRetries($request)
    {
        $hostConfig = $this->getHostConfig($this->getDomainFromHost($request->getUri()->getHost()));
        if (isset($hostConfig['no_of_retries']) && $hostConfig['no_of_retries'] !== null) {
            return $hostConfig['no_of_retries'];
        }
        return $this->defaultNumberOfRetries;
    }

    /**
     * Get retry delay in milliseconds
     * @param  \Psr\Http\Message\RequestInterface $request [description]
     * @return int          retry delay in milliseconds
     */
    protected function getRetryDelay($request)
    {
        $hostConfig = $this->getHostConfig($this->getDomainFromHost($request->getUri()->getHost()));
        if (isset($hostConfig['retry_delay']) && $hostConfig['retry_delay'] != null) {
            return $hostConfig['retry_delay'];
        }
        return $this->defaultRetryDelay;
    }

    /**
     * Get proxy config for given request
     * @param  \Psr\Http\Message\RequestInterface $request psr7 request
     * @return array          configuration for proxy
     */
    protected function getProxyConfig($request)
    {
        $hostConfig = $this->getHostConfig($this->getDomainFromHost($request->getUri()->getHost()));
        if (isset($hostConfig['use_proxy']) && $hostConfig['use_proxy'] != null) {
            return $hostConfig['use_proxy'];
        }
        return $this->defaultProxyUsage;
    }

    /**
     * Get host guzzle options
     * @param  \Psr\Http\Message\RequestInterface $request psr7 request
     * @return array          configuration for proxy
     */
    protected function getHostOptions($request)
    {
        $hostConfig = $this->getHostConfig($this->getDomainFromHost($request->getUri()->getHost()));
        if (isset($hostConfig['options']) && $hostConfig['options'] != null) {
            return array_merge($this->defaultOptions, $hostConfig['options']);
        }
        return $this->defaultOptions;
    }

    /**
     * Get host retry and proxy configuration
     * @param  string $host [description]
     * @return mixed configuration array or null
     */
    protected function getHostConfig(string $host)
    {
        $sourcesConfig = $this->sourcesConfig;
        if (isset($sourcesConfig[$host])) {
            return $sourcesConfig[$host];
        }
    }


    /**
     * Check on body if there respones is not valid
     * @param  [Body content]
     * @return boolean
     */
    public function isValidResponse($body, $url)
    {
        $status = 1;
        
        /*if(stripos($url, 'Eye.Catching.Photography.ByLVJ')){
            echo "<h4>{$url}</h4>";
            $url = 'http://facebook.com/checkpoint/block';
        }*/

        if (stripos($url, '/captcha') || stripos($url, '/blocked')) {
            return $status = 0;
        }

        ## To ignore check point  facebook profile (capacha)
        if (stripos($url, 'facebook') && stripos($url, '/checkpoint/block')) {
            return $status = 0;
        }
        if (stripos($url, 'facebook') && stripos($url, 'ook.com/home.php')) {
            return $status = 0;
        }

        //if(stripos($url, 'facebook')) return $status = 0;
        ## ..
        
        if (stripos($url, 'facebook') && stripos($body, "work properly without JavaScript enabled")!==false) {
            return $status = 0;
        }

        ## facebook not found for mobil version
        if (stripos($url, 'facebook') && stripos($body, "The page you requested cannot be displayed right now")!==false) {
            return $status = 0;
        }
        if (stripos($url, 'facebook') && stripos($body, "<title>Content not found</title>")!==false) {
            return $status = 0;
        }
        if (stripos($url, 'facebook') && stripos($body, "join facebook today")!==false) {
            return $status = 0;
        }
        ## // ..
        
        if (stripos($url, 'facebook') && preg_match('/<title>[^<]*Redirecting[^<]*<\/title/i', $body)) {
            return $status = 0;
        }

        if (stripos($url, 'facebook') && stripos($body, "login_form")!==false) {
            return $status = 0;
        }
     
        if (stripos($url, 'facebook') && stripos($body, "you must log in first")!==false) {
            return $status = 0;
        }
        if (stripos($url, 'facebook') && stripos($body, "email address or phone number")!==false) {
            return $status = 0;
        }
        
        if (stripos($url, 'facebook') && stripos($body, "email address or phone number")!==false) {
            return $status = 0;
        }
        
        if (stripos($url, 'linkedin') && stripos($body, "profile not found")!==false) {
            return $status = 0;
        }

        if (stripos($url, 'linkedin')) {
            $re = '/profileview","status":([^,]+)/si';
            if (preg_match($re, $body, $match)) {
                $status_code = $match[1];
            }
            if (isset($status_code) && $status_code == 500) {
                return $status = 0;
            }
        }

        if (stripos($body, "t understand this search.")!==false) {
            return $status = 0;
        }
        if (stripos($body, "The link you followed may have expired")!==false) {
            return $status = 0;
        }
        
        ## Linkedin Limit
        if (stripos($body, "reached the limit on profile")!==false) {
            return $status = 0;
        }
        
        ## this is what returend from friendsList Json
        if (stripos($body, 'Please try refreshing the page or closing and re-opening')!==false) {
            return $status = 0;
        }
        ## // ..
        if (stripos($body, "session_redirect=")!==false) {
            return $status = 0;
        }
        
        //if (stripos($body,"New to Twitter?")!==false) return $status = 0;
        ## for twitter 404 page
        if (stripos($url, 'twitter') && stripos($body, "Sorry, that page doesn")!==false && stripos($body, "search-404")!==false) {
            return $status = 0;
        }
        
        if (stripos($url, 'angel.co') && stripos($body, "There Was A Problem Loading Your Content") !== false) {
            return $status = 0;
        }

        if (stripos($url, 'angel.co') && stripos($body, "may have been made private or deleted") !== false) {
            return $status = 0;
        }

        if (stripos($url, 'angel.co') && stripos($body, "Page not found") !== false) {
            return $status = 0;
        }

        return $status;
    }
}
