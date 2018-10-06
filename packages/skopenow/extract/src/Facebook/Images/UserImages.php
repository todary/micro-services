<?php

/**
 * UserImages file
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
use Skopenow\Extract\Facebook\Images\UserUrlStrategy\NumericUsernameUrl;
use Skopenow\Extract\Facebook\Images\UserUrlStrategy\UnNumericUsernameUrl;
use Skopenow\Extract\Facebook\Images\UserUrlStrategy\UserUrlInterface;
use Skopenow\Extract\Facebook\Images\Iterator\ImageIteratorInterface;

/**
 * UserImages class
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

class UserImages implements ImageInterface
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
    private $_limit = 20;
    
    /**
     * $_extractLevel
     *
     * @var int
     */
    private $_extractLevel = 0;

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
     * $_personId
     *
     * @var int
     */
    private $_personId;
    
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
     * @param  string                 $link
     * @param  ImageIteratorInterface $iterator
     *
     * @return PostInterface
     */
    public function __construct(string $link, ImageIteratorInterface $iterator) 
    {
        $this->_link = $link;
        $this->_personId = config('state.report_id');
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
    public function setSessId(string $sessId): ImageInterface 
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
    public function setRequestOptions(array $requestOptions): ImageInterface 
    {
        $this->_requestOptions = $requestOptions;
        return $this;
    }
    
    /**
     * setLimit
     *
     * @access public
     * @param  int $limit
     *
     * @return ImageInterface
     */
    public function setLimit(int $limit = 20): ImageInterface 
    {
        $this->_limit = $limit;
        return $this;
    }
    
    /**
     * setLimit
     *
     * @access public
     * @param  int $level
     *
     * @return ImageInterface
     */
    public function setExtractLevel(int $level = 0): ImageInterface 
    {
        $this->_extractLevel = $level;
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
    public function setOldResult($result = []) : ImageInterface 
    {
        $this->_result = $result;
        return $this;
    }
    
    /**
     * Extract
     *
     * @access public
     * 
     * @return void
     */
    public function Extract()
    {
        $urlStrategy = $this->getFacebookUsernameUrls($this->_link);
        $facebookProfle = $urlStrategy->getProfileUrl();
        //$photosUrl = $urlStrategy->getPhotosUrl();
        $albumsURL = $urlStrategy->getAlbumUrl();
        $options = $this->getCurlOptions();
        $links = array();
        
        if(true) {
            $requestUrl = $this->getRequestUrl($albumsURL);
            $response = $this->sendRequest($requestUrl, $options);
            $pattern = "/<div[^>]*?><a[^>]*?class=\"[^\"]*?albumThumbLink[^\"]*\"\s*?href=\"([^\"]*)\".*?<div class=\"photoTextSubtitle.*?\">(.*?)<\/div>/";
            preg_match_all($pattern, $response['body'], $match);
            
            $noPhotosAlbums = [];
            $noPhotosAlbums = preg_replace("/<div.*\">(\d+)\sphotos/", "$1", $match[2]);
            
            $noAlbums = count($noPhotosAlbums);
            if($noAlbums <= 0) {
                // Todo here: logger
                $loggerData = [
                    "Message" => "No Albums \n",
                    "ReportId" => $this->_personId,
                    "AlbumsData" => [],
                    "AlbumsMap" => [],
                    "AlbumaTemp" => [],
                    "SingleAlbum" => []
                ];
                // Todo here: logger
            }
            
            $numImOneAlbum = $this->countPhotosFromOneAlbum($this->_limit, $noAlbums);
            
            $albumsTemp = 0;
            $albumsData = [];
            $albumsMap = [];
            $albumsUrl = array_unique($match[1]);
            
            $counter = 0 ;
            $offset = $this->_extractLevel * $numImOneAlbum;
          
            foreach ($albumsUrl as $key => $album) {
                if(empty($noPhotosAlbums[$key])) {
                     // Todo here:logger
                     $loggerData = [
                           "Message" => "empty no_photos_albums key\n",
                           "ReportId" => $this->_personId,
                           "AlbumsData" => $albumsData,
                           "AlbumsMap" => $albumsMap,
                           "AlbumaTemp" => $albumsTemp,
                           "SingleAlbum" => []
                     ];
                      // Todo here: logger
                        continue;
                }
                if($counter > $this->_limit) {
                      // Todo here:logger
                      $loggerData = [
                           "Message" => "Counter limit exceeded\n",
                           "ReportId" => $this->_personId,
                           "AlbumsData" => $albumsData,
                           "AlbumsMap" => $albumsMap,
                           "AlbumaTemp" => $albumsTemp,
                           "SingleAlbum" => []
                      ];
                       // Todo here: logger
                        break;
                }
                $totalPhotos = $noPhotosAlbums[$key] - $offset;
                if($totalPhotos >= $numImOneAlbum) {
                        $albumsData[] = ["url" => $album ,
                                "limit" => $numImOneAlbum ,
                                "total" => $noPhotosAlbums[$key]];
                        $albumsMap[] = $noPhotosAlbums[$key] ;
                } else if($totalPhotos > 0 && $totalPhotos < $numImOneAlbum) {
                        $diff = $numImOneAlbum - $totalPhotos ;
                        $albumsData[] = ["url" => $album ,
                                "limit" => $totalPhotos ,
                                "total" => $noPhotosAlbums[$key] ] ;
                        $albumsMap[] = $noPhotosAlbums[$key] ;
                        $albumsTemp += $diff ;
                        $counter += $diff;
                        continue;
                } else {
                        // Todo here:logger
                        $loggerData = [
                              "Message" => "Photos album limit reached!\n",
                              "ReportId" => $this->_personId,
                              "AlbumsData" => $albumsData,
                              "AlbumsMap" => $albumsMap,
                              "AlbumaTemp" => $albumsTemp,
                              "SingleAlbum" => []
                        ];
                        // Todo here: logger
                    
                        $albumsTemp += $numImOneAlbum;
                }

                $counter += $numImOneAlbum;
            }
            $albumsTemp = $this->getNumberPhotos($albumsTemp, $albumsMap, $albumsData, $numImOneAlbum);
            
            // Todo here:logger
            $loggerData = [
                  "Message" => "Getting Albums Data\n",
                  "ReportId" => $this->_personId,
                  "AlbumsData" => $albumsData,
                  "AlbumsMap" => $albumsMap,
                  "AlbumaTemp" => $albumsTemp,
                  "SingleAlbum" => []
            ];
            // Todo here: logger
            
            $links = $this->runRequestLoop($albumsData, $offset, $facebookProfle);
        }
        $this->_iterator->setResults($links);        
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
     * getFacebookUsernameUrls
     *
     * @access private
     * @param  string $link
     * 
     * @return UnNumericUsernameUrl|NumericUsernameUrl
     */
    public function getFacebookUsernameUrls(string $link) : UserUrlInterface
    {
        $username = $this->getFacebookUsername($link);
        
        if(is_numeric($username)) {
            return new NumericUsernameUrl($username);
        }
        
        return new UnNumericUsernameUrl($username);
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
     * countPhotosFromOneAlbum
     *
     * @access private
     * @param  int $limit
     * @param  int $numAlbums
     * 
     * @return int
     */
    private function countPhotosFromOneAlbum(int $limit, int $numAlbums) : int
    {
        $noImagesFromOneAlbum = 1;
        if($numAlbums <= $limit && $numAlbums > 0) {
            $noImagesFromOneAlbum = ceil($limit / $numAlbums);
        }
        return $noImagesFromOneAlbum;
    }
    
    /**
     * getNumberPhotos
     *
     * @access private
     * @param  int   $albumsTemp
     * @param  array $albumsMap
     * @param  array $albumsData
     * @param  int   $numImagesFromOneAlbum
     * 
     * @return int
     */
    private function getNumberPhotos(int $albumsTemp, array &$albumsMap, array &$albumsData, int $numImagesFromOneAlbum) : int
    {
        $temp_photos = function ($count)
 use (&$albumsMap, &$albumsData, $numImagesFromOneAlbum, &$temp_photos) {
            if($count == 0) {
                return 0;
            }
            if(empty($albumsMap)) {
                return $count;
            }
            
            $max_album = max($albumsMap);
            $key = array_search($max_album, array_column($albumsData, 'total'));
                
            if($key === false || $key === 0  || !isset($albumsMap[$key])) {
                return 0;
            }
            $offset = $this->_extractLevel * $numImagesFromOneAlbum;
            $total_photos = $albumsMap[$key] - ($offset + $numImagesFromOneAlbum);
            if($total_photos > 0 && $count > $total_photos) {
                $albumsData[$key]['limit'] += $total_photos;
                $count -= $total_photos;
                unset($albumsMap[$key]);
                $temp_photos($count);
            } else if ($total_photos > 0 && $count <= $total_photos) {
                $albumsData[$key]['limit'] += $count;
                return 0;
            } else if($total_photos <= 0) {
                unset($albumsMap[$key]);
                $temp_photos($count);
            } else {
                return 0;
            }
        };
        return $temp_photos($albumsTemp);
    }
    
    /**
     * getFacebookImagesDirectLink
     * 
     * 
     * @param string $content
     * 
     * 
     * @return array
     */
    public function getFacebookImagesDirectLink(string $content)
    {
        $imagesLinks = array();
   	preg_match_all("/tagWrapper[\"']><i\\s*style=[\"']background-image:\\s*url\\(([^')]*)\\).*?data-fbid=\\\"(\d+)\\\"/is", $content, $imagesLinks, PREG_SET_ORDER);
   	return $imagesLinks;
    }
    
    /**
     * getFacebookUsername
     *
     * @access public
     * @param  string $link
     * 
     * @return string
     */
    public function getFacebookUsername(string $link)
    {
        $pattern = "#facebook\.com\/((\w|\d|[\.\-_\?\=])+)[^\/\&]*$#i";
        $link = rtrim($link, "/");
        preg_match($pattern, $link, $match);

        if(!isset($match[1]) || strpos($link, "photo.php") !== false) {
            return;
        }
        
        $username = '';
        if(isset($match[1])) {
            $username = $match[1];
        }
        $type = strpos($link, "profile.php") !== false ? "id" : "username";
        if ($type == "id") {
            preg_match("#profile.php\?id\=([^&]+)#i", $link, $match);
            if(!empty($match[1])) {
                $username = $match[1];
            }
        }
        return $username;
    }
    
    /**
     * runRequestLoop
     * 
     * @access private
     * @param  array  $albumsData
     * @param  int    $offset
     * @param  string $facebookProfle
     * 
     * @return array
     */
    public function runRequestLoop(array $albumsData, int $offset, string $facebookProfle) : array
    {
        $links = [];
        
        $httpService = loadService('HttpRequestsService');
        foreach ($albumsData as $album) {
            $requestUrl = strstr($album['url'], "=");
            $requestUrl = $facebookProfle."/media_set?set".$requestUrl;
            $requestUrl = html_entity_decode($requestUrl);

            $onSuccessCallback = function ($ret) use (&$links, $album, $offset) {
                $pattern = "#data-reorderid=['\"](.+?)['\"]#is";
                
                $content = (string) $ret->getResponse()->getBody();
                
                $isImagesExist = preg_match_all($pattern, $content, $albumHTML, PREG_SET_ORDER);

                if($isImagesExist == 0) {
                    $re = '/background-image:.*?url\((.+?)\).*?data-fbid=\\"(\d+)\\"/';
                    preg_match_all($re, $content, $matches, PREG_SET_ORDER, 0);
                    $imagesLinks = $matches ;
                    $isImagesExist = 1;
                } else {
                    $imagesLinks = $this->getFacebookImagesDirectLink($content);
                }
                    // Todo here:logger
                    $loggerData = [
                          "Message" => "Displaying ImagesLinks \n",
                          "ReportId" => $this->_personId,
                          "AlbumsData" => $imagesLinks,
                          "AlbumsMap" => [],
                          "AlbumaTemp" => [],
                          "SingleAlbum" => $album
                    ];
                    // Todo here: logger

                if(!$isImagesExist) {
                    // Todo here:logger
                    $loggerData = [
                          "Message" => "no images exist\n",
                          "ReportId" => $this->_personId,
                          "AlbumsData" => $imagesLinks,
                          "AlbumsMap" => [],
                          "AlbumaTemp" => [],
                          "SingleAlbum" => $album
                    ];
                    // Todo here: logger
                    
                    return [];
                }

                $albumHTML = array_slice($imagesLinks, $offset, $album['limit']);

                if(empty($albumHTML)) {
                    $albumHTML = $imagesLinks;
                }
                
                foreach ($albumHTML as $r) {
                    if (empty($r[2])) { 
                        continue;
                    }

                    $link = "";
                    $directLink = "";

                    if(!empty($r[1])) {
                        $directLink = $r[1];
                    }

                        $link = "https://www.facebook.com/photo.php?fbid=" . $r[2];

                    $links[$r[1]] = array(
                        "link" => $link,
                        "direct_link" => html_entity_decode($directLink)
                    );
                }
            };

            $onFailureCallback = function ($e) {
                 // Todo: logger service
                  $noticeEventsCurl = "Notice [error eventsCurl]: we got an error when we tried to opining this profile: {$e->result->info['url']} \n";
                  $noticeEventsCurl .="    content the error message: ".$e->getMessage()."\n--------\n";
                  $loggerData = [
                      "Exception" => $noticeEventsCurl,
                      "RequestData" => $e->getRequest(), 
                      "ReportId" => $this->_personId,
                      "ClassMethod" => __METHOD__,
                      "Message" => $e->getMessage(),
                      "CurlOptions" => [],
                      "HttpMethod" => "GET"
                ];
                // Todo: logger service
            };

            $httpService->createRequest($requestUrl, [], 'GET', [], $onSuccessCallback, $onFailureCallback);
        }
        
        $httpService->processRequests();
        
        return $links;
    }
}