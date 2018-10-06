<?php
namespace Skopenow\HttpRequests;

use GuzzleHttp\Promise\PromiseInterface;
use GuzzleHttp\Promise\RejectedPromise;
use GuzzleHttp\Promise\FulfilledPromise;
use GuzzleHttp\Psr7;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

use Skopenow\HttpRequests\Interfaces\RequestProcessHandlerInterface;

use Cache;

/**
*  Request Process Handler middler ware
*/
class RetryMiddleware
{
    protected $requestProcessHandler;
    protected $nextHandler;
    protected $pendingRequests;

    public function __construct(RequestProcessHandlerInterface $requestProcessHandler)
    {
        $this->requestProcessHandler = $requestProcessHandler;
        $this->pendingRequests = [];
    }


    /**
     * @param RequestInterface $request
     * @param array            $options
     *
     * @return GuzzleHttp\Promise\PromiseInterface
     */
    public function __invoke(RequestInterface $request, array $options, $originalRequest = null)
    {
        //store original request for retry as when we retry we change proxy used
        if (!$originalRequest) {
            $originalRequest = clone $request;
        }

        $options = $this->resolveOptions($originalRequest, $options);
        $request = $this->prepareRequest($originalRequest, $options);

        $fn = $this->nextHandler;
        return $fn($request, $options)
            ->then(
                $this->onFulfilled($request, $options, $originalRequest),
                $this->onRejected($request, $options, $originalRequest)
            );
    }

    /**
     * return guzzle middleware callback function
     * @return callable guzzle callback function
     */
    public function getGuzzleMiddleware()
    {
         return function (callable $handler) {
            $this->setNextHandler($handler);
            return $this;
         };
    }

    /**
     * set next handler
     * @param callable $handler [description]
     */
    protected function setNextHandler(callable $handler)
    {
        $this->nextHandler = $handler;
    }

    /**
     * create callable for after request success
     * @param  RequestInterface $req     request that has been succeeded
     * @param  array            $options guzzle request options array
     * @return callable                  callable function to call after success
     */
    private function onFulfilled(RequestInterface $req, array $options, $originalRequest)
    {
        return function ($value) use ($req, $options, $originalRequest) {
            $exception = null;

            $value->getBody()->rewind();
            if (!$this->requestProcessHandler->isValidResponse($value->getBody()->getContents(), $req->getURI()->__toString())) {
                $exception = new \GuzzleHttp\Exception\RequestException('invalid response', $req, $value);
            }
            
            //reset stream index
            $value->getBody()->rewind();
            
            if (!$this->requestProcessHandler->decideRetry($req, $options, $value)) {
                if ($exception) {
                    return new RejectedPromise($exception);
                }
                
                return $value;
            }
            $options = $this->resolveOptions($req, $options, $value);
            $request = clone($originalRequest);
            $req = $this->prepareRequest($request, $options, $value);
            
            return $this->doRetry($req, $options, $originalRequest);
        };
    }

    /**
     * create callable function to be called after request failure
     * @param  RequestInterface $req     request that has been failed
     * @param  array            $options guzzle request options
     * @return callable                  callable function to call after request failure
     */
    private function onRejected(RequestInterface $req, array $options, $originalRequest)
    {
        return function ($reason) use ($req, $options, $originalRequest) {
            $response = null;
            try {
                $response = $reason->getResponse();
            } catch (\Exception $ex) {
            }
            if (!$this->requestProcessHandler->decideRetry($req, $options, $response, $reason)) {
                return new RejectedPromise($reason);
            }

            $options = $this->resolveOptions($req, $options, null, $reason);
            $request = clone($originalRequest);
            $req = $this->prepareRequest($request, $options, null, $reason);
            return $this->doRetry($req, $options, $originalRequest);
        };
    }

    /**
     * retry current request
     * @param  RequestInterface $request request to be retried
     * @param  array            $options guzzle options for request
     * @return GuzzleHttp\Promise\PromiseInterface
     */
    private function doRetry(RequestInterface $request, array $options, $originalRequest)
    {
        $options['retries']++;
        return $this($request, $options, $originalRequest);
    }


    /**
     * resolve request options
     * @param  \Psr\Http\Message\RequestInterface       $request           request to be resolved for its options
     * @param  array                                    $options           guzzle request options
     * @param  \Psr\Http\Message\ResponseInterface|null $previousResponse  previous response if this is retry
     * @param  GuzzleHttp\Exception\RequestException    $previousException previous exception if this is retry
     * @return array                                                       guzzle request options
     */
    protected function resolveOptions(
        \Psr\Http\Message\RequestInterface $request,
        $options,
        \Psr\Http\Message\ResponseInterface $previousResponse = null,
        $previousException = null
    ) {
        return $this->requestProcessHandler->resolveRequestOptions($request, $options, $previousResponse, $previousException);
    }


    /**
     * resolve request options
     * @param  \Psr\Http\Message\RequestInterface       $request           request to be resolved for its options
     * @param  array                                    $options           guzzle request options
     * @param  \Psr\Http\Message\ResponseInterface|null $previousResponse  previous response if this is retry
     * @param  GuzzleHttp\Exception\RequestException    $previousException previous exception if this is retry
     * @return array                                                       guzzle request options
     */
    protected function prepareRequest(
        \Psr\Http\Message\RequestInterface $request,
        $options,
        \Psr\Http\Message\ResponseInterface $previousResponse = null,
        $previousException = null
    ) {
        return $this->requestProcessHandler->prepareRequest($request, $options, $previousResponse, $previousException);
    }
}
