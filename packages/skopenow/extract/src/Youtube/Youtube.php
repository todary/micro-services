<?php

/**
 * Youtube class file
 *
 * PHP version 7
 *
 * @package   Youtube
 * @author    Queen tech <info@queentechsolutions.net>
 * @copyright 2017-2018 Queen tech
 * @access    public
 * @license   http://www.queentechsolutions.net/ BSD Licence
 * @version   CVS: 1.0.0
 * @link      http://www.queentechsolutions.net/
 */

namespace Skopenow\Extract\Youtube;

use Skopenow\Extract\Youtube\YoutubeInterface;
use Skopenow\Extract\Youtube\Iterator\YoutubeIteratorInterface;

/**
 * Youtube Class
 *
 * @package   Youtube
 * @author    Queen tech <info@queentechsolutions.net>
 * @copyright 2017-2018 Queen tech
 * @access    public
 * @license   http://www.queentechsolutions.net/ BSD Licence
 * @version   Release: 1.0.0
 * @link      http://www.queentechsolutions.net/
 */

class Youtube implements YoutubeInterface
{

    /**
     * $_link
     *
     * @var string
     */
    private $_link;
    
    /**
     * $_iterator
     *
     * @var iterator
     */
    private $_iterator;
    
    /**
     * Constructor
     *
     * @access public
     * @param  string                   $profileUrl
     * @param  YoutubeIteratorInterface $iterator
     *
     * @return YoutubeInterface
     */
    public function __construct(string $profileUrl, YoutubeIteratorInterface $iterator)
    {
        $this->_link = $profileUrl;
        $this->_iterator = $iterator;
        return $this;
    }

    /**
     * Extract
     *
     * @access public
     * @param  string $type
     *
     * @return Youtube instance
     */
    public function Extract(string $type = 'videos') : YoutubeInterface
    {
        $resultsArray = $this->processData($type);
        foreach ($resultsArray as $result) {
            $this->_iterator->addResult($result);
        }
        
        return $this;
    }
    
    /**
     * getResults
     *
     * @access public
     *
     * @return Iterator
     */
    public function getResults(): \Iterator 
    {
        return $this->_iterator->getIterator();
    }
    
    /**
     * getRequestUrl
     *
     * @access private
     * @param  string $link
     * @param  string $type
     * 
     * @return string
     */
    private function getRequestUrl(string $link, string $type) : string
    {
        return trim($link) . "/" . trim($type);
    }

    /**
     * sendRequest
     *
     * @access private
     * @param  string $requestUrl
     * @param  array  $curlOptions
     * 
     * @return array
     */
    public function sendRequest(string $requestUrl, array $curlOptions) : array
    {
        try{
            $response = loadService('HttpRequestsService')->fetch($requestUrl, "GET", $curlOptions);
            $response->getBody()->rewind();
            return ['body' => $response->getBody()->getContents()];
        } catch(\Exception $e) {
            // Todo: logger
            $noticeEventsCurl = "Curl Exception occured when fetching url: $requestUrl \n";
            $loggerData = [
                  "Exception" => $noticeEventsCurl,
                  "RequestData" => $requestUrl, 
                  "ReportId" => "",
                  "ClassMethod" => __METHOD__,
                  "Message" => $e->getMessage(),
                  "CurlOptions" => $curlOptions,
                  "HttpMethod" => "GET"
            ];
             // Todo: logger
            return ['body' => ''];
        }
    }
    
    /**
     * processData
     *
     * @param string $type
     * @param array  $return
     *
     * @return array
     */
    public function processData(string $type, array &$return = []) 
    {
        $options = [];
        $url = $this->getRequestUrl($this->_link, $type);
        $response = $this->sendRequest($url, $options);
        
        $type = rtrim($type, "s ");
        $videosId = [];
        $matched = preg_match('/window\[\\"ytInitialData\\"\]\s*=\s*(.*);/', $response['body'], $matches);        
        if ($matched) {
            $matched = preg_match_all('/(\\"videoId\\":\\"(\w+)\\")/', $matches[1], $matches);
            if ($matched) { 
                $videosId = $matches[2] ;
            }
        }
        
        $dataTemp = [];
        foreach ($videosId as $video ) {
            $dataTemp[] = "https://www.youtube.com/watch?v=" . $video ;
            $return[] = [
                            'url' => "https://www.youtube.com/watch?v=" . $video, 
                            'image' => '', 
                            'title' => '', 
                            'type' => $type 
                    ];
        }
        
        $htmlObj = \str_get_html($response['body']);
        
        if($htmlObj) {
            foreach ($htmlObj->find("li[class=channels-content-item]") as $element) {
                $data = [];

                $a = $element->find("a[class=yt-uix-tile-link]", 0);

                $img = $element->find("img", 0);

                if($a && $a->hasAttribute("title")) {
                    $data['title'] = $a->getAttribute("title");
                }

                if($a && $a->hasAttribute("href")) {
                    $data['url'] = "https://www.youtube.com" . $a->getAttribute("href");
                } 

                if($img && $img->hasAttribute("src")) {
                    $data['image'] =  explode('?', $img->getAttribute("src"))[0];
                }

                if(!empty($data) && !in_array($data['url'], $dataTemp)) {
                    $data['type'] = $type;
                    $return[] = $data ;
                }

            }
        }
        if($type == 'video') {
                return $this->processData('playlists', $return);
        }
        
        return $return;
    }
}