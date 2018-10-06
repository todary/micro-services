<?php

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Exception\RequestException;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

use Skopenow\HttpRequests\Fetchable;
use Skopenow\HttpRequests\URLFetcher;

/**
* Test cases for url fetcher
*/
class URLFetcherTest extends TestCase
{
    protected $urlFetcher;

    protected function getUrlFetcher($expectedResponses)
    {
        // Create a mock and queue two responses.
        $mock = new MockHandler($expectedResponses);

        $handler = HandlerStack::create($mock);
        $client = new Client(['handler' => $handler]);

        $logger = new Logger('skope');

        $streamHandler = new StreamHandler(__DIR__.'/logs/my.log', Logger::DEBUG);
        $logger->pushHandler($streamHandler);

        $urlFetcher = new URLFetcher($client, $logger);
        return $urlFetcher;
    }

    public function testFetchConncurrentURLs()
    {

        $expectedResponses = [
            new Response(200, ['X-Foo' => 'Bar']),
            new Response(202, ['Content-Length' => 0]),
            new RequestException("Error Communicating with Server", new Request('GET', 'test'))
        ];


        //initialize fetchables
        $fetchables = [];
        $fetchables[] = new Fetchable(new Request('GET', 'http://google.com'), ['data' => 'google']);
        $fetchables[] = new Fetchable(new Request('GET', 'http://facebook.com'), ['data' => 'face']);
        $fetchables[] = new Fetchable(new Request('GET', 'http://web.com'), ['data' => 'web']);

        $urlFetcher = $this->getUrlFetcher($expectedResponses);
        $urlFetcher->fetch($fetchables);

        $this->assertEquals('200', $fetchables[0]->getResponse()->getStatusCode());
        $this->assertEquals(['data' => 'google'], $fetchables[0]->getData());

        $this->assertEquals('202', $fetchables[1]->getResponse()->getStatusCode());
        $this->assertEquals(['data' => 'face'], $fetchables[1]->getData());

        $this->assertFalse($fetchables[2]->isFullfiled());
        $this->assertTrue($fetchables[2]->isRejected());
        $this->assertEquals(['data' => 'web'], $fetchables[2]->getData());
    }
}
