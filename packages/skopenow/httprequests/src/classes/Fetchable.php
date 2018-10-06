<?php
namespace Skopenow\HttpRequests;

use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Promise\Promise;
use Skopenow\HttpRequests\Interfaces\FetchableInterface;

/**
* Fetchable class is a median class that holds data along with guzzle request and options
*/
class Fetchable implements FetchableInterface
{
    protected $request;
    protected $response;
    protected $data;
    protected $requestOptions;
    protected $requestPromise;
    protected $isSucceeded;
    protected $failureReason;

    public function __construct(\Psr\Http\Message\RequestInterface $request, $data = null, array $requestOptions = [])
    {
        $this->request = $request;
        $this->requestOptions = $requestOptions;
        $this->data = $data;
        $this->requestPromise = new Promise();
    }

    /**
     * Is fetchable fullfilled it request
     */
    public function isFullfiled() :bool
    {
        return $this->isSucceeded?true:false;
    }

    /**
     * Is fetchable request is rejected
     * @return boolean [description]
     */
    public function isRejected() :bool
    {
        return $this->isSucceeded?false:true;
    }

    /**
     * Failure exception
     * @return Exception exception for failure
     */
    public function getFailureReason()
    {
        return $this->failureReason;
    }

    /**
     * get promise for fetchable
     * @return GuzzleHttp\Promise\Promise
     */
    public function getRequestPromise() :\GuzzleHttp\Promise\Promise
    {
        return $this->requestPromise;
    }

    /**
     * set reason on fetch failure
     */
    public function onFetchFailure(/*\GuzzleHttp\Exception\RequestException*/ $reason)
    {
        if ($this->requestPromise->getState()=="fulfilled") {
            return;
        }
        $this->failureReason = $reason;
        $this->requestPromise->reject($this);
        $this->isSucceeded = false;
    }

    /**
     * set response on fetch success
     * @param \Psr\Http\Message\ResponseInterface $response
     */
    public function onFetchSuccess(\Psr\Http\Message\ResponseInterface $response)
    {
        $this->response = $response;
        $this->requestPromise->resolve($this);
        $this->isSucceeded = true;
    }

    /**
     * Get fetchable request
     * @return \Psr\Http\Message\RequestInterface
     */
    public function getRequest() :\Psr\Http\Message\RequestInterface
    {
        return $this->request;
    }

    /**
     * Get request guzzle options
     * @return array request options
     */
    public function getRequestOptions() :array
    {
        return $this->requestOptions;
    }

    /**
     * Get response associated with fetchable
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * Get data associated with fetchable
     * @return mixed data of fetchable
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Get url of request associated with fetchable
     * @return string
     */
    public function getRequestURL() :string
    {
        return $this->request->getUri()->__toString();
    }

    public function getCacheKey()
    {
        $keyArr = [];
        $keyArr['url'] = $this->request->getUri()->__toString();
        $keyArr['method'] = $this->request->getMethod();
        $keyArr['headers'] = $this->request->getHeaders();
        $keyArr['body'] = $this->request->getBody();
        $keyArr['options'] = $this->requestOptions;
        
        $key = md5(json_encode($keyArr));
        return $key;
    }
}
