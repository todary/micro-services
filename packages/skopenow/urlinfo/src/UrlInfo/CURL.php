<?php
namespace Skopenow\UrlInfo\UrlInfo;

class CURL
{
    /**
     * Curl Content
     * @param string $url
     * @param array  $options
     * @return array
     */
    public function curl_content(string $url, array $options = [])
    {
        // $agent = 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.9.0.8) Gecko/2009032609 Firefox/3.0.8';
        // $curl = curl_init();
        // curl_setopt($curl, CURLOPT_URL, $url);
        // curl_setopt($curl, CURLOPT_HEADER, 0);
        // curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        // curl_setopt($curl, CURLOPT_HTTPPROXYTUNNEL, 1);
        // curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 5);
        // curl_setopt($curl, CURLOPT_USERAGENT, $agent);
        // $result = curl_exec($curl);
        // return ['body' => $result];
        try {
            $entry = loadService('HttpRequestsService');
            $response = $entry->fetch($url, 'GET', $options);
            $response->getBody()->rewind();
            return ['body' => $response->getBody()->getContents()];
        } catch (\Exception $ex) {
            return ['body' => ''];
        }
    }
}
