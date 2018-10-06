<?php

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Exception\RequestException;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

use Skopenow\HttpRequests\RequestProcessHandler;
use Skopenow\HttpRequests\ProxyProvider;
use Skopenow\HttpRequests\ProxyData;

class RequestProcessHandlerTest extends TestCase
{
    protected $requestProcessHandler;
    public function setup()
    {
        $proxyDataMock = $this->createMock(ProxyData::class);
         $proxyDataMock->method('getProxyURl')->willReturn('proxy_url');
         $proxyDataMock->method('getCookiesPath')->willReturn(__DIR__.'/cookies');
        $proxyProvider = $this->createMock(ProxyProvider::class);
        $proxyProvider->method('getProxy')
        ->willReturn($proxyDataMock);
        $loggerMock = $this->createMock(\Monolog\Logger::class);
        $this->requestProcessHandler = new RequestProcessHandler($proxyProvider, $loggerMock);
    }

    public function testDecideRetry()
    {
        //request uri mock
        $requestUriMock = $this->createMock(\Psr\Http\Message\UriInterface::class);
        $requestUriMock->method('getHost')->willReturn('example.com');

        //request mock
        $requestMock = $this->createMock(\Psr\Http\Message\RequestInterface::class);
        $requestMock->method('getUri')->willReturn($requestUriMock);
        
        //exception mock
        $exceptionMock = $this->createMock(\GuzzleHttp\Exception\RequestException::class);

        //response mock
        $responseMock = $this->createMock(\Psr\Http\Message\ResponseInterface::class);
        $responseMock->method('getStatusCode')->willReturn(500);
        
        $result = $this->requestProcessHandler->decideRetry($requestMock, ['retries' => 0]);
        $this->assertFalse($result);
        

        $this->requestProcessHandler->setDefaultNumberOfRetries(3);

        $result = $this->requestProcessHandler->decideRetry($requestMock, ['retries' => 0]);
        $this->assertFalse($result);
        
        //exception and max retries not reached return true
        $result = $this->requestProcessHandler->decideRetry($requestMock, ['retries' => 2], null, $exceptionMock);
        $this->assertTrue($result);

        //bad response and max retries not reached return true
        $result = $this->requestProcessHandler->decideRetry($requestMock, ['retries' => 2], $responseMock);
        $this->assertTrue($result);

        //max retries reached return false
        $result = $this->requestProcessHandler->decideRetry($requestMock, ['retries' => 3], null, $exceptionMock);
        $this->assertFalse($result);


        $this->requestProcessHandler->addSourceConfig('example.com', 5, 1000, RequestProcessHandler::USE_PROXY_ALWAYS);
        $result = $this->requestProcessHandler->decideRetry($requestMock, ['retries' => 3], null, $exceptionMock);
        $this->assertTrue($result);
    }

    public function testResolveRequestOptions()
    {

        //request uri mock
        $requestUriMock = $this->createMock(\Psr\Http\Message\UriInterface::class);
        $requestUriMock->method('getHost')->willReturn('example.com');

        //request mock
        $requestMock = $this->createMock(\Psr\Http\Message\RequestInterface::class);
        $requestMock->method('getUri')->willReturn($requestUriMock);
        

        $this->requestProcessHandler->setDefaultRetryDelay(3);
        $this->requestProcessHandler->setDefaultProxyUsage(RequestProcessHandler::USE_PROXY_NEVER);

        $result = $this->requestProcessHandler->resolveRequestOptions($requestMock, []);
        $this->assertArraySubset(['proxies_used' => [], 'retries' => 0], $result);

        $result = $this->requestProcessHandler->resolveRequestOptions($requestMock, ['retries' => 1]);

        $this->assertArraySubset(['delay' => 3, 'proxies_used' => [], 'retries' => 1], $result);

        $this->requestProcessHandler->addSourceConfig('example.com', 3, 1000, RequestProcessHandler::USE_PROXY_ALWAYS);
        $result = $this->requestProcessHandler->resolveRequestOptions($requestMock, ['retries' => 1]);

        $this->assertArraySubset(['delay' => 1000], $result);
        $this->assertArraySubset(['proxy' => 'proxy_url'], $result);
        $this->assertArraySubset(['curl' => [10031=>__DIR__.'/cookies']], $result);
        $this->assertArraySubset(['proxies_used' => ["proxy_url"]], $result);

        $this->requestProcessHandler->addSourceConfig('example.com', 3, 1000, RequestProcessHandler::USE_PROXY_NEVER);
        $result = $this->requestProcessHandler->resolveRequestOptions($requestMock, ['retries' => 1]);
    }

    public function testPrepareRequest()
    {
        //request uri mock
        $requestUriMock = $this->createMock(\Psr\Http\Message\UriInterface::class);
        $requestUriMock->method('getHost')->willReturn('example.com');

        //request mock
        $cookieValue = 'ajax:3848312338750636551';
        $request = $this->createMock(\Psr\Http\Message\RequestInterface::class);
        $request->method('getUri')->willReturn($requestUriMock);
        $request->method('getHeaders')->willReturn([ 'csrf-token' => [ 0 => '^!COOKIE!JSESSIONID^' ] ]);
        $request->method('withHeader')->will($this->returnSelf());
        $request->expects($this->atLeastOnce())
            ->method('withHeader');
            // ->with('csrf-token', $cookieValue);

        $this->requestProcessHandler->prepareRequest($request, ['curl' => [10031=>__DIR__.'/cookies']]);
    }
}
