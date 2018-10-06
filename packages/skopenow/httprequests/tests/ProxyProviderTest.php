<?php

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Exception\RequestException;

use Skopenow\HttpRequests\ProxyProvider;
use Skopenow\HttpRequests\ProxyData;

/**
* Test cases for proxy prvider
*/
class ProxyProviderTest extends TestCase
{

    function getResponseData()
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

    function testGetProxy()
    {
        $responseData = $this->getResponseData();

        // Create a mock and queue two responses.
        $mock = new MockHandler([
            new Response(200, [], json_encode($responseData))
        ]);

        $handler = HandlerStack::create($mock);
        $client = new Client(['handler' => $handler]);

        $proxyProvider = new ProxyProvider('http://example.com', $client);
        $expectedProxyData = new ProxyData($responseData);
        $proxyData = $proxyProvider->getProxy('host', '', ['77.237.228.12']);

        $this->assertEquals($expectedProxyData->getProxyURl(), $proxyData->getProxyURl());
        $this->assertEquals($expectedProxyData->getCookiesPath(), $proxyData->getCookiesPath());
    }

    function testGetProxyWithFailedStatus()
    {
       // Create a mock and queue two responses.
        $mock = new MockHandler([
            new Response(200)
        ]);

        $handler = HandlerStack::create($mock);
        $client = new Client(['handler' => $handler]);

        $proxyProvider = new ProxyProvider('http://example.com', $client);
        $proxyData = $proxyProvider->getProxy('host');
        $this->assertFalse($proxyData);
    }
}
