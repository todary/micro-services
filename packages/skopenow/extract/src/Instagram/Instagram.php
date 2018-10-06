<?php

/**
 * Instagram file
 *
 * PHP version 7
 *
 * @package   Instagram
 * @author    Queen tech <info@queentechsolutions.net>
 * @copyright 2017-2018 Queen tech
 * @access    public
 * @license   http://www.queentechsolutions.net/ BSD Licence
 * @version   CVS: 1.0.0
 * @link      http://www.queentechsolutions.net/
 */

namespace Skopenow\Extract\Instagram;

use Skopenow\Extract\Instagram\InstagramInterface;
use Skopenow\Extract\Instagram\Iterator\InstagramIteratorInterface;

/**
 * Instagram Class
 *
 * PHP version 7
 *
 * @package   Instagram
 * @author    Queen tech <info@queentechsolutions.net>
 * @copyright 2017-2018 Queen tech
 * @access    public
 * @license   http://www.queentechsolutions.net/ BSD Licence
 * @version   CVS: 1.0.0
 * @link      http://www.queentechsolutions.net/
 */

class Instagram implements InstagramInterface
{

    /**
     * $_link
     *
     * @var string
     */
    private $_link;
    
    /**
     * $_limit
     *
     * @var int
     */
    private $_limit;
    
    /**
     * $_oldPhotos
     *
     * @var array
     */
    private $_oldPhotos;

    /**
     * $_requestOptions
     *
     * @var array
     */
    private $_requestOptions = [];
    
    /**
     * $_iterator
     *
     * @var iterator
     */
    private $_iterator;
    
    /**
     * $_queryUrl
     *
     * @var string
     */
    private $_queryUrl = "https://www.instagram.com/query/";
    
    /**
     * $_extractionCookie
     *
     * @var string
     */
    private $_extractionCookie = "/tmp/instagram_extraction.cookie";
    
    
    /**
     * Constructor
     *
     * @access public
     * @param  string                     $link
     * @param  InstagramIteratorInterface $iterator
     *
     * @return PostInterface
     */
    public function __construct(string $link, InstagramIteratorInterface $iterator)
    {
        $this->_link = $link;
        $this->_iterator = $iterator;
        return $this;
    }

    /**
     * setLimit
     *
     * @access public
     * @param  int $limit
     *
     * @return InstagramInterface
     */
    public function setLimit(int $limit): InstagramInterface 
    {
        $this->_limit = $limit;
        return $this;
    }
    
    /**
     * setOldPhotos
     *
     * @access public
     * @param  array $oldPhotos
     *
     * @return InstagramInterface
     */
    public function setOldPhotos(array $oldPhotos): InstagramInterface 
    {
        $this->_oldPhotos = $oldPhotos;
        return $this;
    }
    
    /**
     * setRequestOptions
     *
     * @access public
     * @param  array $requestOptions
     *
     * @return InstagramInterface
     */
    public function setRequestOptions(array $requestOptions): InstagramInterface 
    {
        $this->_requestOptions = $requestOptions;
        return $this;
    }

    /**
     * Extract
     *
     * @access public
     *
     * @return InstagramInterface
     */
    public function Extract() : InstagramInterface
    {
        $options = $this->getCurlOptions();
        $response = $this->sendRequest($this->_link, $options);
        
        $nodes = [];
        $pattern = "/window._sharedData\s=\s(\{.*?\});/m";
        
        preg_match($pattern, $response['body'], $matches);
        if(isset($matches[1])) {
            $sharedData = $matches[1];
            $sharedData = json_decode($sharedData, true);
            $nodes = @$sharedData['entry_data']['ProfilePage'][0]['user']['media']['nodes'];

            if(!$nodes) {
                $nodes = [];
            }
        }
        
        // after the first request .
        $countOldPhotos = count($this->_oldPhotos);
        if($countOldPhotos) {
            $arrayData = $this->getNodes($response, $countOldPhotos);   
            if(!empty($arrayData['media']['nodes'])) {
                $nodes = array_merge($nodes, $arrayData['media']['nodes']);
            }
        }
        
        foreach($nodes as $node) {
            $this->_iterator->addResult($node);
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
     * getCurlOptions
     *
     * @access private
     * 
     * @return array
     */
    private function getCurlOptions() : array 
    {
        $options = array();
        $options['cookie_file_path']= $this->_extractionCookie;
        
        return ["form_params" => $options];
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
    private function sendRequest(string $requestUrl, array $curlOptions) 
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
     * getNodes
     *
     * @access private
     * @param  array $response
     * @param  int   $countOldPhotos
     * 
     * @return mixed
     */
    public function getNodes(array $response, int $countOldPhotos)
    {
        $userId = 0;
        preg_match("/\"id\":\\s+\"(\\d+)\"/", $response['body'], $match);
        if(!empty($match[1])) {
            $userId = $match[1];
        }
        $endCursor = 0;
        preg_match("/\"end_cursor\":\\s+\"(\\d+)\"/", $response['body'], $match);
        if(!empty($match[1])) {
            $endCursor = $match[1];
        }
        $csrfToken = 0;
        preg_match("/\"csrf_token\":\\s+\"([^\"]+)\"/", $response['body'], $match);
        if(!empty($match[1])) {
            $csrfToken = $match[1];
        }
        $nodeHtml = $this->getNodeHtml($userId, $endCursor, $countOldPhotos);

        $options = [
            'form_params' => ["q" => $nodeHtml, "ref" => "users::show"],
            'headers' => [
                                  "Content-Type" => "application/x-www-form-urlencoded",
                                  "X-CSRFToken" => $csrfToken,
                                  "X-Instagram-AJAX" => 1,
                                  "X-Requested-With" => "XMLHttpRequest"
            ] 
        ];
        $return = $this->sendRequest($this->_queryUrl, $options);
        return json_decode($return['body'], true);
    }
    
    /**
     * getNodeHtml
     *
     * @param mixed $userId
     * @param mixed $endCursor
     * @param mixed $countOldPhotos
     *
     * @return string
     */
    private function getNodeHtml($userId, $endCursor, $countOldPhotos) : string
    {
        return "ig_user($userId) { media.after($endCursor, $countOldPhotos) {
                        count,
                        nodes {
                          caption,
                          code,
                          comments {
                            count
                          },
                          date,
                          dimensions {
                            height,
                            width
                          },
                          display_src,
                          id,
                          is_video,
                          likes {
                            count
                          },
                          owner {
                            id
                          },
                          thumbnail_src,
                          video_views
                        },
                        page_info
                      }
                       }";
    }
}