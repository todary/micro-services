#Http Requests
This service is responsible for making http requests synchronus and asynchronus using proxies when needed for certain domains and use proxy cookies with default amount of retries.

This service is a wrapper for [GuzzleHttp](http://docs.guzzlephp.org/en/stable/index.html)

It uses proxy by default for the folowing sites
    - linkedin.com
    - facebook.com
    - plus.google.com
    - youtube.com
    - pipl.com
    - api.pipl.com
    - google.com
    - 411locate.com
    - peoplebyname.com
    - slideshare.com
    - slideshare.net
    - angel.co
    - angel.co.skpaccount
    - angel.co.skpblocked
    - findthecompany.com
    - fastcompany.com

## How to use

### Initialization
```php
    use Skopenow\HttpRequestsService\EntryPoint;

    $httpService = new EntryPoint();
```

### Synchronus Request

####Simple Usage

Response is an instance of GuzzleHttp\Psr7\Request
```php
    //GuzzleHttp\Psr7\Response
    $response = $httpService->fetch('https://google.com');
    
    $htmlData = $response->getbody()->getContents();
    $headers = $response->getHeaders();
    $statusCode = $response->getStatusCode();
```

####Set http method and options
Refere to guzzle options [here](http://docs.guzzlephp.org/en/stable/request-options.html)
```php
    //GuzzleHttp Options
    $options = [
        'timeout' => 10,
        'headers' => [
            'User-Agent' => 'testing/1.0',
            'Accept'     => 'application/json',
            'X-Foo'      => ['Bar', 'Baz']
        ],
        'form_params' => [
            'foo' => 'bar',
            'baz' => ['hi', 'there!']
        ]
    ];
    
    //GuzzleHttp\Psr7\Response
    $response = $httpService->fetch('https://example.com','POST', $options);
```

#### Non guzzle options
```php
    $options = [
        'no_cache' => false, //whether to use cache or not with this request
        'headers' => [
            //if pattern is presented in header it will be replaced with cookie with key presented with pattern 
            {{key}} => '^!COOKIE!{{value}}^' 
        ],
        'try_proxy' => false //whether to try proxy or not for this request
    ];
```

### Asynchronous multiple request
```php
    use Skopenow\HttpRequests\Fetchable;

    $onSuccess = function (Fetchable $fetchable) {
        //do stuff here on success
        $data = $fetchable->getData();
    }

    $onFailure = function (Fetchable $fetchable) {
        //do stuff here on failure
        $data = $fetchable->getData();
    }

    $url = 'http://google.com';
    $data = ['any data here']; //any data goes here
    $options = [];
    //store request
    $httpService->createRequest($url, $data, $method, $options, $onSuccess, $onFailure);
    

    $url = 'http://facebook.com';
    $data = ['any data here']; //any data goes here
    //store request
    $httpService->createRequest($url, $data, $method, $options, $onSuccess, $onFailure);
    
    //run all stored requests asynchronusly
    $httpService->processRequests();
```

#### Fetchables
Fetchable is a data class that holds request details along with some data

```php
    
    /**
     * some code here that return fetchable
    **/
    
    //return true if request succeeded
    $fetchable->isFullfiled();

    //return true if request failed
    $fetchable->isRejected();
    
    //return failure exception
    $fetchable->getFailureReason();
    
    //return promise GuzzleHttp\Promise\Promise that you can listen to
    $promise = $fetchable->getRequestPromise();
    $promise->then(
            function (Fetchable $fetchable) {
                // do stuff on success
            },

            function (Fetchable $fetchable) {
                // do stuff on failure
            }
        );

    //return data that is passed with request
    $data = $fetchable->getData();
    
```

