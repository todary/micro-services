<?php
namespace Skopenow\HttpRequests\Interfaces;

interface FetchableInterface
{
    /**
     * Get request for the fetchable
     * @return \Psr\Http\Message\RequestInterface $request
     */
    public function getRequest() :\Psr\Http\Message\RequestInterface;

    /**
     * Get request options
     * @return guzzle options array
     */
    public function getRequestOptions() :array;

    /**
     * @param $reason guzzle exception
     */
    public function onFetchFailure(/*\GuzzleHttp\Exception\RequestException*/ $reason);

    /**
     * Function to call for successful request 
     * @param  \Psr\Http\Message\ResponseInterface $response [description]
     * @return [type]                                        [description]
     */
    public function onFetchSuccess(\Psr\Http\Message\ResponseInterface $response);
}
