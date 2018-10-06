<?php

/**
 * PageImages file
 *
 * PHP version 7
 *
 * @package   Facebook Images
 * @author    Queen tech <info@queentechsolutions.net>
 * @copyright 2017-2018 Queen tech
 * @access    public
 * @license   http://www.queentechsolutions.net/ BSD Licence
 * @version   CVS: 1.0.0
 * @link      http://www.queentechsolutions.net/
 */

namespace Skopenow\Extract\Facebook\Images;

use Skopenow\Extract\Facebook\Images\ImageInterface;
use Skopenow\Extract\Facebook\Images\Iterator\ImageIteratorInterface;
use Skopenow\Extract\Facebook\Images\PageUrlStrategy\DesktopUrl;
use Skopenow\Extract\Facebook\Images\PageUrlStrategy\MobileUrl;
use Skopenow\Extract\Facebook\Images\PageUrlStrategy\DefaultUrl;
use Skopenow\Extract\Facebook\Images\PageUrlStrategy\PageUrlInterface;

/**
 * PageImages class
 *
 * PHP version 7
 *
 * @package   Facebook Images
 * @author    Queen tech <info@queentechsolutions.net>
 * @copyright 2017-2018 Queen tech
 * @access    public
 * @license   http://www.queentechsolutions.net/ BSD Licence
 * @version   CVS: 1.0.0
 * @link      http://www.queentechsolutions.net/
 */

class PageImages implements ImageInterface
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
     * $_result
     *
     * @var mixed
     */
    private $_result = [];
    
    /**
     * $_iterator
     *
     * @var ImageIteratorInterface
     */
    private $_iterator;
    
    /**
     * Constructor
     *
     * @access public
     * @param  string $link
     *
     * @return ImageInterface
     */
    public function __construct(string $link, ImageIteratorInterface $iterator) 
    {
        $this->_link = $link;
        $this->_iterator = $iterator;
        return $this;
    }
    
    /**
     * setSessId
     *
     * @access public
     * @param  string $sessId
     *
     * @return ImageInterface
     */
    public function setSessId(string $sessId) : ImageInterface 
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
     * @return ImageInterface
     */
    public function setRequestOptions(array $requestOptions) : ImageInterface 
    {
        $this->_requestOptions = $requestOptions;
        return $this;
    }
    
    /**
     * setOldResult
     *
     * @access public
     * @param  mixed $result
     *
     * @return ImageInterface
     */
    public function setOldResult($result) : ImageInterface 
    {
        $this->_result = $result;
        return $this;
    }
    
    /**
     * Extract
     *
     * @access public
     * 
     * @return ImageInterface
     */
    public function Extract() : ImageInterface
    {
        $pageUrl = $this->getPageUrlStrategy($this->_link);
        $url = $pageUrl->getUrl();
        
        $requestUrl = $this->getRequestUrl($url);
        $options = $this->getCurlOptions();
        $response = $this->sendRequest($requestUrl, $options);
        
        $pattern = "/\"InitMMoreItemAutomatic\",\"main\",\\[\\],\\[\\{\"id\":\"m_more_photos\",\"href\":\"([^\"]+)/";
        if(preg_match($pattern, $response['body'], $match)) {
            $MoreLink=  "https://m.facebook.com".stripslashes($match[1]);
        }
        
        $links = $this->extractPagePhotos($response['body']);
        
        if(isset($MoreLink)) {
            $extraLinks = $this->getMoreLinks($MoreLink, $options);
            if(!empty($extraLinks)) {
                foreach ($extraLinks as $link) {
                    array_push($links, $link);
                }
            }
        }
        
        $this->_iterator->setResults($links);
        
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
     * getPageUrlStrategy
     *
     * @access private
     * @param  string $link
     * 
     * @return DesktopUrl|MobileUrl|DefaultUrl
     */
    public function getPageUrlStrategy(string $link) : PageUrlInterface
    {
        if(strpos($link, "www")) {
            return new DesktopUrl($link);
        } elseif(!strpos($link, "m.facebook")) {
            return new MobileUrl($link);
        } 
        
        return new DefaultUrl($link);
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
        $link= $link."/photos" ;
        $requestUrl = getRedirectUrl($link);
        if (strpos($requestUrl, '?') !== false) {
            return str_replace('?', '?__sid=' . $this->_sessId . '&', $requestUrl);
        }
        return $requestUrl . '?__sid=' . $this->_sessId;
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
        $headers['USER-AGENT'] = 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/53.0.2785.116 Safari/537.36';
        return ["form_params" => $options, "headers" => $headers];
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
            $data = [
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
     * getMoreLinks
     *
     * @access private
     * @param  string $getMoreLink
     * @param  array  $options
     * 
     * @return array
     */
    public function getMoreLinks(string $getMoreLink, array $options) : array
    {
        $requestUrl = $this->getRequestUrl($getMoreLink);
        $response = $this->sendRequest($requestUrl, $options);

        if(preg_match("/\"html\":\"([^,]+)\"/", $response['body'], $match)) {
            $html = json_decode('"'.$match[1].'"', true);
            if($html) {
                $newLinks = $this->extractPagePhotos($html);
                return $newLinks;
            }
            return [];
        }
        return [];
    }
    
    /**
     * extractPagePhotos
     * 
     * @access public
     * @param  string $html
     * @return array
     */
    public function extractPagePhotos(string $html = '') : array
    {
        preg_match_all("/<a\\s*class=\"_39pi\"\\s*href=\"([^\"]+)\">(.+?)<\\/a>/", $html, $match, PREG_SET_ORDER);

        $links = array();
        foreach ($match as $index => $r) {
            $directLink = "";
            if(!empty($match[$index][2])) {
                $pattern = "/style=\"background-image:\\s+url\\(([^)]+)/";
                preg_match($pattern, $match[$index][2], $directLink);
                if(isset($directLink[1])) {
                    $directLink =  str_replace('"', "", html_entity_decode($directLink[1]));
                    $links[$r[1]] = array(
                        "link" => "https://www.facebook.com" . $r[1],
                        "direct_link" => $directLink
                    );
                }
            }
        }
        return $links;
    }
}