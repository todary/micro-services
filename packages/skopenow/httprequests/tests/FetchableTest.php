<?php

use GuzzleHttp\Psr7\Request;
use Skopenow\HttpRequests\Fetchable;

/**
* Test cases for fetchable
*/
class FetchableTest extends TestCase
{

    public function testFetchableGetRequestURL()
    {
        $fetchable = new Fetchable(new Request('GET', 'http://example.com'));

        $this->assertEquals('http://example.com', $fetchable->getRequestURL());
    }

    public function testFetchableGetData()
    {
        $fetchable = new Fetchable(new Request('GET', 'http://example.com'), ['data' => 'data']);

        $this->assertEquals(['data' => 'data'], $fetchable->getData());
    }

    public function testFetchableGetRequestOptions()
    {
        $fetchable = new Fetchable(new Request('GET', 'http://example.com'), [], ['timeout' => 10]);
        $this->assertEquals(['timeout' => 10], $fetchable->getRequestOptions());
    }

    public function testFetchableGetRequest()
    {
        $request = new Request('GET', 'http://example.com');
        $fetchable = new Fetchable($request);
        $this->assertEquals($request, $fetchable->getRequest());
    }

    public function testFetchableGetResponse()
    {
        $fetchable = new Fetchable(new Request('GET', 'http://example.com'));
        $this->assertNull($fetchable->getResponse());

        $response = $this->createMock(\Psr\Http\Message\ResponseInterface::class);

        $fetchable->onFetchSuccess($response);
        $this->assertEquals($response, $fetchable->getResponse());
    }

    public function testGetPromise()
    {
        $fetchable = new Fetchable(new Request('GET', 'http://example.com'));
        $promise = $fetchable->getRequestPromise();
        $this->assertInstanceOf(\GuzzleHttp\Promise\Promise::class, $promise);
    }

    public function testFetchFailure()
    {
        $fetchable = new Fetchable(new Request('GET', 'http://example.com'));
        $reason = $this->createMock(\GuzzleHttp\Exception\RequestException::class);
        $fetchable->onFetchFailure($reason);
        $this->assertEquals($reason, $fetchable->getFailureReason());
        $this->assertTrue($fetchable->isRejected());
        $this->assertFalse($fetchable->isFullfiled());
    }

    public function testFetchSuccess()
    {
        $fetchable = new Fetchable(new Request('GET', 'http://example.com'));
        $response = $this->createMock(\Psr\Http\Message\ResponseInterface::class);
        $fetchable->onFetchSuccess($response);
        $this->assertEquals($response, $fetchable->getResponse());
        $this->assertTrue($fetchable->isFullfiled());
        $this->assertFalse($fetchable->isRejected());
    }
}
