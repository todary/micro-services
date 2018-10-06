<?php

/**
 * Facebook Post file
 *
 * PHP version 7
 *
 * @package   Facebook Posts
 * @author    Queen tech <info@queentechsolutions.net>
 * @copyright 2017-2018 Queen tech
 * @access    public
 * @license   http://www.queentechsolutions.net/ BSD Licence
 * @version   CVS: 1.0.0
 * @link      http://www.queentechsolutions.net/
 */

namespace Skopenow\Extract\Facebook\Posts;

use Skopenow\Extract\Facebook\Posts\PostInterface;
use Skopenow\Extract\Facebook\Posts\Iterator\PostIteratorInterface;

/**
 * Facebook Post class
 *
 * PHP version 7
 *
 * @package   Facebook Posts
 * @author    Queen tech <info@queentechsolutions.net>
 * @copyright 2017-2018 Queen tech
 * @access    public
 * @license   http://www.queentechsolutions.net/ BSD Licence
 * @version   CVS: 1.0.0
 * @link      http://www.queentechsolutions.net/
 */

class Post implements PostInterface
{

    /**
     * $_link
     *
     * @var string
     */
    private $_link;

    /**
     * $_sessId
     *
     * @var string
     */
    private $_sessId = "";

    /**
     * $_requestOptions
     *
     * @var array
     */
    private $_requestOptions = [];

    /**
     * $_results
     *
     * @var iterator
     */
    private $_results = [];
    
    /**
     * $_daysOfWeek
     *
     * @var array
     */
    private $_daysOfWeek = array("sunday", "monday", "tuesday", "wednesday", "thursday", "friday", "saturday","at");

    /**
     * $_postsIterator
     *
     * @var iterator
     */
    private $_postsIterator;
    
    /**
     * $_personId
     *
     * @var int
     */
    private $_personId;
    
    /**
     * Constructor
     *
     * @access public
     * @param  string                $link
     * @param  PostIteratorInterface $postsIterator
     *
     * @return PostInterface
     */
    public function __construct(string $link, PostIteratorInterface $postsIterator) 
    {
        $this->_link = $link;
        $this->_postsIterator = $postsIterator;
        $this->_personId = config('state.report_id');
        return $this;
    }

    /**
     * setSessId
     *
     * @access public
     * @param  string $sessId
     *
     * @return PostInterface
     */
    public function setSessId(string $sessId): PostInterface 
    {
        $this->_sessId = $sessId;
        return $this;
    }

    /**
     * setRequestOptions
     *
     * @access public
     * @param  array $requestOptions
     *
     * @return PostInterface
     */
    public function setRequestOptions(array $requestOptions): PostInterface 
    {
        $this->_requestOptions = $requestOptions;
        return $this;
    }

    /**
     * Extract
     *
     * @access public
     *
     * @return PostInterface
     */
    public function Extract() : PostInterface
    {
        $link = $this->getFullLink($this->_link);
        $requestUrl = $this->getRequestUrl($link);

        $options = $this->getCurlOptions();
        $html = $this->sendRequest($requestUrl, $options);
        if (!array_key_exists('limit', $this->_requestOptions)) {
            $this->_requestOptions['limit'] = 10;
            if (!empty($this->_requestOptions['offset'])) {
                $this->_requestOptions['limit'] += $this->_requestOptions['offset'] + 1;
            }
        }

        $results = $this->callGraph(
                                        [
                                            "body" => $html['body'],
                                            "advanced" => true,
                                            "limit" => $this->_requestOptions['limit'],
                                            "link" => $link,
                                            "type" => "timeline",
                                            "searchKeyword" => "",
                                            "requestOptions" => $this->_requestOptions
                                        ]
        );
        $this->_results = $results;
        return $this;
    }

    /**
     * loopResults
     *
     * @access public
     *
     * @return PostInterface
     */
    public function loopResults() : PostInterface 
    {
        $fullArray = [];
        foreach ($this->_results as $key => $result) {
            if ($key == "posts") {
                foreach ($result as $key2 => $post) {
                    if (isset($post['date']) && $post['date'] != 'Not Available') {
                        $t1 = strtotime(str_replace($this->_daysOfWeek, "", $post['date']));
                        $result['posts'][$key2]['time_stamp'] = $t1;
                        $post['time_stamp'] = $t1;
                    }
                    switch ($post['type']) {
                    case 'photo':
                        $fullArray['Images'][] = $post;
                        break;
                    case 'video':
                        $fullArray['Videos'][] = $post;
                        break;
                    case 'post':
                        $fullArray['Posts'][] = $post;
                        break;
                    }
                }
            }
            if ($key == "instagram") {
                $fullArray['instagramAccount'][] = $result['instagram'];
            }
            
        }
        $fullArray['requestOptions'] = $this->_results['requestOptions'];
        $this->_postsIterator->setResults($fullArray);
        return $this;
    }
    
    /**
     * getResults
     *
     * @access public
     *
     * @return Iterator
     */
    public function getResults() 
    {
        return $this->_postsIterator->getIterator();
    }

    /**
     * getFullLink
     *
     * @access private
     * @param  string $link
     * 
     * @return string
     */
    public function getFullLink(string $link) : string
    {
        $link = str_replace("profile.php?id=", "", $link);
        $link = $link . '/timeline/' . date('Y');
        return $link;
    }

    /**
     * getRequestUrl
     *
     * @access private
     * @param  string $link
     * 
     * @return string
     */
    public function getRequestUrl(string $link) : string
    {
        $request_url = getRedirectUrl($link);
        if (strpos($request_url, '?') !== false) {
            return str_replace('?', '?__sid=' . $this->_sessId . '&', $request_url);
        }
        return $request_url . '?__sid=' . $this->_sessId;
    }

    /**
     * getCurlOptions
     *
     * @access private
     * 
     * @return array
     */
    public function getCurlOptions() : array 
    {
        $options = array();
        $options['ignore_auto_select_ip'] = true;
        if ($this->_personId != null) {
            $options['person_id'] = $this->_personId;
        }
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
                              "ReportId" => $this->_personId,
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
     * callGraph
     *
     * @access private
     * @param  array $response
     * 
     * @return array
     */
    public function callGraph(array $response) : array
    {
        $ret = \FacebookGraphSearch::getWordSearchResults($response['body'], $response['advanced'], $response['limit'], $response['link'], $response['type'], $response['searchKeyword'], $response['requestOptions']);
        return $ret;
    }
}