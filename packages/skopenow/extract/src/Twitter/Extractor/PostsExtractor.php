<?php

/**
 * PostsExtractor file
 *
 * PHP version 7
 *
 * @package   Twitter
 * @author    Queen tech <info@queentechsolutions.net>
 * @copyright 2017-2018 Queen tech
 * @access    public
 * @license   http://www.queentechsolutions.net/ BSD Licence
 * @version   CVS: 1.0.0
 * @link      http://www.queentechsolutions.net/
 */

namespace Skopenow\Extract\Twitter\Extractor;

use Skopenow\Extract\Twitter\Extractor\ExtractorInterface;

/**
 * PostsExtractor class
 *
 * PHP version 7
 *
 * @package   Twitter
 * @author    Queen tech <info@queentechsolutions.net>
 * @copyright 2017-2018 Queen tech
 * @access    public
 * @license   http://www.queentechsolutions.net/ BSD Licence
 * @version   CVS: 1.0.0
 * @link      http://www.queentechsolutions.net/
 */

class PostsExtractor implements ExtractorInterface
{
    
    /**
     * $_url
     *
     * @var string
     */
    private $_url;
    
    /**
     * $_personId
     *
     * @var int
     */
    private $_personId;
    
    /**
     * $_combinationId
     *
     * @var int
     */
    private $_combinationId;
    
    /**
     * Constructor
     *
     * @access public
     * @param  string $data
     */
    public function __construct(string $data)
    {
        $this->_url = $data;
        $this->_personId = config('state.report_id');
        $this->_combinationId = config('state.combination_id');
    }
    
    /**
     * Process
     *
     * @access public
     * @return array
     */
    public function Process() : array
    {
        $tweetsList = [];
        $options = $this->getCurlOptions();
        $response = $this->sendRequest($this->_url, $options);
        $html = $response['body'];
        
        if(!empty($html)) {
            $result = json_decode($html, true);

            if(!empty($result)) {
                $page = html_entity_decode($result['page']);
            } else {
                $page = html_entity_decode($html);
            }

            $pattern = "/data-permalink-path=\"(.+?)\"/";
            $isMatch = preg_match_all($pattern, $page, $match);
            if($isMatch) {
                foreach ($match[1] as $tweetUrl)
                {
                    $permalinkUrl  = "http://twitter.com" . $tweetUrl ;
                    $tweetId = explode("/", $tweetUrl)[3] ;
                    $tweetsList[] = array('tweet_id' => $tweetId, 'permalinkUrl' => $permalinkUrl);
                }
            }
        }
           return $tweetsList ;
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
        $options = [ 
                'form_params' => [
                    //'person_id' => $this->_personId, 
                    //'combination_id' => $this->_combinationId
                ]
        ];
        return $options;
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
        }catch(\Exception $e) {
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
}