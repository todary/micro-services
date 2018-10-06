<?php

/**
 * SkillsExtractor file
 *
 * PHP version 7
 *
 * @package   Linkedin
 * @author    Queen tech <info@queentechsolutions.net>
 * @copyright 2017-2018 Queen tech
 * @access    public
 * @license   http://www.queentechsolutions.net/ BSD Licence
 * @version   CVS: 1.0.0
 * @link      http://www.queentechsolutions.net/
 */

namespace Skopenow\Extract\Linkedin\Extractor;

use Skopenow\Extract\Linkedin\Extractor\ExtractorInterface;

/**
 * SkillsExtractor class
 *
 * PHP version 7
 *
 * @package   Linkedin
 * @author    Queen tech <info@queentechsolutions.net>
 * @copyright 2017-2018 Queen tech
 * @access    public
 * @license   http://www.queentechsolutions.net/ BSD Licence
 * @version   CVS: 1.0.0
 * @link      http://www.queentechsolutions.net/
 */

class SkillsExtractor implements ExtractorInterface
{
    
    /**
     * $_profileId
     *
     * @var string
     */
    private $_profileId;
    
    /**
     * $_personId
     *
     * @var int
     */
    private $_personId;
    
    /**
     * $_maxSkillsCount
     *
     * @var int
     */
    private $_maxSkillsCount = 100;
    
    /**
     * $_requestUrl
     *
     * @var string
     */
    private $_requestUrl = "https://www.linkedin.com/voyager/api/identity/profiles/%s/featuredSkills?includeHiddenEndorsers=true&count=%s";
    
    /**
     * Constructor
     *
     * @access public
     * @param  mixed $data
     */
    public function __construct($data)
    {
        $this->_profileId = $data;
        $this->_personId = config('state.report_id');
    }
    
    /**
     * Process
     *
     * @access public
     * @return array
     */
    public function Process() : array
    {   
        $skills = [];
        $requestUrl = $this->getRequestUrl();
        $options = $this->getCurlOptions();
        $response = $this->sendRequest($requestUrl, $options);
        $content = json_decode($response);
        $skillsItems = @$content->elements;

        if($skillsItems) {
            foreach ($skillsItems as $item) {
                preg_match('/:\((.*),(\d*)\)/', $item->skill->entityUrn, $matches);
                $profileId = $matches[1];
                $skillId = $matches[2];
                $skill = [
                    'name' => $item->skill->name,
                    'skillId' => $skillId,
                    'profileId' => $profileId,
                ];
                $skills[] = $skill;
            }
        }
        return $skills;
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
        $options = [ 
                'headers' => ['CSRF-Token' => '^!COOKIE!JSESSIONID^']
        ];
        return $options;
    }
    
    /**
     * getRequestUrl
     *
     * @access private
     * 
     * @return string
     */
    public function getRequestUrl() : string
    {
        return sprintf($this->_requestUrl, $this->_profileId, $this->_maxSkillsCount);
    }
    
    /**
     * sendRequest
     *
     * @access private
     * @param  string $requestUrl
     * @param  array  $options
     * 
     * @return array
     */
    public function sendRequest(string $requestUrl, array $options)
    {
        $response = '';
        $onSuccessCallback = function ($ret) use (&$response) {
            $response = (string) $ret->getResponse()->getBody();
        };
        
        $onFailureCallback = function ($e) use ($options) {
            // Todo: logger service
            $noticeEventsCurl = "Notice [error eventsCurl]: we got an error when we tried to extract endorsment skills for profile $this->_profileId \n";
            $noticeEventsCurl .="    content the error message: ".$e->getMessage()."\n--------\n";            
            $loggerData = [
                  "Exception" => $noticeEventsCurl,
                  "RequestData" => $e->getRequest(), 
                  "ReportId" => $this->_personId,
                  "ClassMethod" => __METHOD__,
                  "Message" => $e->getMessage(),
                  "CurlOptions" => $options,
                  "HttpMethod" => "GET"
            ];
            // Todo: logger service
        };
        $httpService = loadService('HttpRequestsService');
        $httpService->createRequest($requestUrl, [], 'GET', $options, $onSuccessCallback, $onFailureCallback);
        $httpService->processRequests();
        return $response;
    }
}