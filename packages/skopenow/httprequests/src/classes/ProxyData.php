<?php
namespace Skopenow\HttpRequests;

use Skopenow\HttpRequests\Interfaces\ProxyDataInterface;

/**
* Proxy data holds and resolves data returned from proxy provider
*/
class ProxyData implements ProxyDataInterface
{
    protected $data;

    public function __construct(array $proxyData)
    {
        $this->data = $proxyData;
        /*$this->data = array (
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
        );*/
    }

    /**
     * get proxy url from proxy data
     * @return string proxy url
     */
    public function getProxyURl() :string
    {
        return "http://".$this->data['username'].":".$this->data['password']."@".$this->data['ip'].":".$this->data['port'];
    }

    public function getAccountData() :string
    {
        return $this->data['data']??"";
    }

    /**
     * get path for proxy cookies file
     * @return string path for proxy stored cookies
     */
    public function getCookiesPath() :string
    {
        $cookies_file = (isset($this->data['account'])? 'Account_'.$this->data['account']: 'IP_'.$this->data['ip_id']);
        $cookie_file_path = "/tmp/" . $cookies_file . ".cookie";
    
        $md5_1 = @md5_file($cookie_file_path);

        if(isset($this->data['cookies']))
            $proxyCookies = (base64_decode($this->data['cookies']));
        else
            $proxyCookies = '';
        $md5_2 = md5($proxyCookies);

        //if ($md5_1 != $md5_2) {
            file_put_contents($cookie_file_path, $proxyCookies);
        //}
        return $cookie_file_path;
    }
}
