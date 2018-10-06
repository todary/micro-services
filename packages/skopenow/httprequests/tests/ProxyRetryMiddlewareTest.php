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
use Skopenow\HttpRequests\RetryMiddleware;

/**
* Test cases for proxy retry manager
*/
class ProxyRetryManagerTest extends TestCase
{
    protected $proxyRetryManager;
    protected $count;
    protected function getProxyData()
    {
        $responseData = array (
            'status' => 1,
            'ip' => '77.237.228.12',
            'port' => 1212,
            'last_state' => 1,
            'is_self' => 0,
            'message' => '',
            'account' => 3628,
            'data' => null,
            'cookies' => 'IyBOZXRzY2FwZSBIVFRQIENvb2tpZSBGaWxlCiMgaHR0cHM6Ly9jdXJsLmhheHguc2UvZG9jcy9odHRwLWNvb2tpZXMuaHRtbAojIFRoaXMgZmlsZSB3YXMgZ2VuZXJhdGVkIGJ5IGxpYmN1cmwhIEVkaXQgYXQgeW91ciBvd24gcmlzay4KCi53d3cubGlua2VkaW4uY29tCVRSVUUJLwlGQUxTRQkxNTE2Njk0NzMxCUpTRVNTSU9OSUQJImFqYXg6ODAzMTQxMTM4NDIxOTg1MzY2MyIKd3d3LmxpbmtlZGluLmNvbQlGQUxTRQkvCUZBTFNFCTE1NDA3NzU3NTcJdmlzaXQJInY9MSZNIgoubGlua2VkaW4uY29tCVRSVUUJLwlGQUxTRQkwCWxhbmcJInY9MiZsYW5nPWVuLXVzIgoubGlua2VkaW4uY29tCVRSVUUJLwlGQUxTRQkxNTM5OTM1OTg0CWJjb29raWUJInY9MiY3NzFhZWI4OC1kYzhjLTQwOWMtOGY2Ny0xY2ExZGUwYjdkMGMiCiNIdHRwT25seV8ud3d3LmxpbmtlZGluLmNvbQlUUlVFCS8JVFJVRQkxNTM5OTM1OTg0CWJzY29va2llCSJ2PTEmMjAxNjEwMTgyMDIyMTIxMjAxZmVjOC0yZmQ1LTRjNGMtODM3My00OWRiOWEwOWViMmFBUUhXNHgwZTlTaDhqRURQcUlNbFQ1OE1HMFZmTVUxWCIKLmxpbmtlZGluLmNvbQlUUlVFCS8JRkFMU0UJMTQ4NTI0NTEzMQlsaWRjCSJiPVZCMzE6Zz04MzU6dT0xMTU6aT0xNDg1MTU4NzMxOnQ9MTQ4NTI0NTEzMTpzPUFRR2JIanNYZXdBVkt2WTFhcDVtR3c2WF9FdlgxYlBrIgojSHR0cE9ubHlfLnd3dy5saW5rZWRpbi5jb20JVFJVRQkvCUZBTFNFCTE1MTY2OTQ3MzEJbGlfYXQJQVFFREFSNjdwNmNCMWlFY0FBQUJXVm9lUmQ4QUFBRlp6Qk5EUzFZQU1YMnFYUW5rYjlWTWtKQmMxZE83R1JwcnRvTFBmQVl3QkVDYnJudEhIRjg1dmppQ1NMdXNGVXFNeHdrLThqWEF2ZW1aOGt5eHBWSDNrV3hLNkMzUzc1amRJbmNndVRxdElKMVVOWVpzNmRXUGk0bnIKLmxpbmtlZGluLmNvbQlUUlVFCS8JRkFMU0UJMTUxNjY5NDczMQlsaWFwCXRydWUKd3d3LmxpbmtlZGluLmNvbQlGQUxTRQkvCUZBTFNFCTE0ODUxNTk5MTIJbGVvX2F1dGhfdG9rZW4JIkdTVDo4OW9DZ0w4cmU4VDhPWkVnS1N4d21yUS1kaVROUmxJWmt6Z0pKTWxiMTRLUXpaY2dPWmp6cng6MTQ4NTE1ODExMzo1NWU1ZmQ2NTUwZmQ3NGJiZWMwMDYwODQzNTU1Njc1Y2M3MmQ5MzAxIgo=',
            'id' => 1377337687,
            'ip_id' => 233294,
            'username' => 'user-13925',
            'password' => '6282a10a50ebab8f',
        );
        return $responseData;
    }

    public function decideRetry()
    {
        if (!$this->count) {
            $this->count = 0;
        }
        $this->count++;
        if ($this->count == 3) {
            return false;
        }
        return true;
    }

    public function testInvoke()
    {
        $requestProcessHandler = $this->createMock(RequestProcessHandler::class);
        $requestProcessHandler->method('decideRetry')->will($this->returnCallback(array($this, 'decideRetry')));
        $requestProcessHandler->method('resolveRequestOptions')->willReturn(['retries' => 0]);
        $requestProcessHandler->method('prepareRequest')->will($this->returnArgument(0));
        $requestProcessHandler->method('isValidResponse')->willReturn(true);
        $this->proxyRetryMiddleware = new RetryMiddleware($requestProcessHandler);
        $expectedResponses = [
            new Response(500),
            new Response(500),
            new Response(200),
        ];
        $client = $this->getClient($expectedResponses);
        $response = $client->get('http://example.com');

        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testFailInvoke()
    {
        
        $requestProcessHandler = $this->createMock(RequestProcessHandler::class);
        $requestProcessHandler->method('decideRetry')->will($this->returnCallback(array($this, 'decideRetry')));
        $requestProcessHandler->method('resolveRequestOptions')->willReturn(['retries' => 0]);
        $requestProcessHandler->method('prepareRequest')->will($this->returnArgument(0));
        $this->proxyRetryMiddleware = new RetryMiddleware($requestProcessHandler);

        $expectedResponses = [
            new RequestException("Error Communicating with Server", new Request('GET', 'http://example.com')),
            new RequestException("Error Communicating with Server", new Request('GET', 'http://example.com')),
            new RequestException("Error Communicating with Server", new Request('GET', 'http://example.com')),
            new RequestException("Error Communicating with Server", new Request('GET', 'http://example.com')),
            new RequestException("Error Communicating with Server", new Request('GET', 'http://example.com')),
            new RequestException("Error Communicating with Server", new Request('GET', 'http://example.com')),
            new RequestException("Error Communicating with Server", new Request('GET', 'http://example.com')),
            new RequestException("Error Communicating with Server", new Request('GET', 'http://example.com')),
            new RequestException("Error Communicating with Server", new Request('GET', 'http://example.com')),
            new RequestException("Error Communicating with Server", new Request('GET', 'http://example.com'))
        ];
        $client = $this->getClient($expectedResponses);

        $requests = function ($total) {
            $uri = 'http://example.com';
            for ($i = 0; $i < $total; $i++) {
                yield new Request('GET', $uri);
            }
        };

        $pool = new \GuzzleHttp\Pool($client, $requests(2), [
            'concurrency' => 5,
            'fulfilled' => function ($response, $index) {
                // this is delivered each successful response
                $this->assertFalse(true);
            },
            'rejected' => function ($reason, $index) {
                // this is delivered each failed request
                $this->assertTrue(true);
            },
        ]);

        // Initiate the transfers and create a promise
        $promise = $pool->promise();

        // Force the pool of requests to complete.
        $promise->wait();
    }
    
    protected function getClient($expectedResponses)
    {
        $mock = new MockHandler($expectedResponses);
        $middleware = $this->proxyRetryMiddleware->getGuzzleMiddleware();
        $handler = HandlerStack::create($mock);
        $handler->after('cookies', $middleware);
        $client = new Client(['handler' => $handler]);
        return $client;
    }
}
