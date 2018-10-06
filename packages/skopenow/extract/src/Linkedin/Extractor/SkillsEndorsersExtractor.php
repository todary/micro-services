<?php

/**
 * SkillsEndorsersExtractor file
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
 * SkillsEndorsersExtractor class
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

class SkillsEndorsersExtractor implements ExtractorInterface
{
    
    /**
     * $_skills
     *
     * @var array
     */
    private $_skills;
    
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
    private $_requestUrl = "https://www.linkedin.com/voyager/api/identity/profiles/%s/endorsements?count=%s&includeHidden=true&pagingStart=0&q=findEndorsementsBySkillId&skillId=%s&start=0";
    
    /**
     * Constructor
     *
     * @access public
     * @param  mixed $data
     */
    public function __construct($data)
    {
        $this->_skills = $this->toArray($data);
        $this->_personId = config('state.report_id');
    }
    
    /**
     * Extract
     *
     * @access public
     * @return array
     */
    public function Process() : array
    {
        if(count($this->_skills) == 0) {
            return [];
        }
        $skillsEndorsers = [];
        
        $options = $this->getCurlOptions();
        $responses = $this->sendRequest($options);
        foreach($responses as $ret) {
            $response = $ret["body"];
            $content = json_decode($response);
            $elements =  @$content->elements;
            $endorsers = [];
                foreach($elements as $element) {
                    $miniProfile = $element->endorser->miniProfile;
                    $endorsers[] = [
                        'name' => $miniProfile->firstName.' '.$miniProfile->lastName,
                        'profileId' => $miniProfile->publicIdentifier
                    ];
                }
                $skillsEndorsers[] = ['skill' => ['name' => $ret["skillName"]['tmp']['name']], 'endorsers' => $endorsers];
        }
        return $skillsEndorsers;
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
     * toArray
     *
     * @access private
     * @param  \Iterator $iterator
     * @return array
     */
    private function toArray(\Iterator $iterator)
    {
        return iterator_to_array($iterator);
    }
    
    /**
     * getRequestUrl
     *
     * @access private
     * @param  string $profileId
     * @param  string $skillId
     * 
     * @return string
     */
    public function getRequestUrl(string $profileId, $skillId) : string
    {
        return sprintf($this->_requestUrl, $profileId, $this->_maxSkillsCount, $skillId);
    }
    
    /**
     * sendRequest
     *
     * @access private
     * @param  array $options
     * 
     * @return array
     */
    public function sendRequest(array $options)
    {
        $responses = [];
        $onSuccessCallback =  function ($ret) use (&$responses) {
            $responses[] = ["body" => (string) $ret->getResponse()->getBody(), "skillName" => $ret->getData()];
        };
        $profileId = $this->_skills[0]['profileId'];
        
        $onFailureCallback = function ($e) use ($profileId, $options) {
            // Todo: log service
            $noticeEventsCurl = "Notice [error eventsCurl]: we got an error when we tried to extract skill endorsers for profile $profileId \n";
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
            // Todo: log service
        };
        
        $httpService = loadService('HttpRequestsService');
        foreach($this->_skills as $skill) {
            $skillId = $skill['skillId'];
            $profileId = $skill['profileId'];
            $url = $this->getRequestUrl($profileId, $skillId);
            $data['tmp'] = $skill;
            $httpService->createRequest($url, $data, 'GET', $options, $onSuccessCallback, $onFailureCallback);
        }
        $httpService->processRequests();
        return $responses;
    }
}