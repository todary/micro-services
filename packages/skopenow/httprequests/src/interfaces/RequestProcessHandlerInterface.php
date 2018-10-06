<?php
namespace Skopenow\HttpRequests\Interfaces;

interface RequestProcessHandlerInterface
{
    public function decideRetry(
        \Psr\Http\Message\RequestInterface $request,
        array $options,
        \Psr\Http\Message\ResponseInterface $previousResponse = null,
        \GuzzleHttp\Exception\RequestException $previousException = null
    );
    
    public function resolveRequestOptions(
        \Psr\Http\Message\RequestInterface $request,
        array $options,
        \Psr\Http\Message\ResponseInterface $previousResponse = null,
        \GuzzleHttp\Exception\RequestException $previousException = null
    );
}
