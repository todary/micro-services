<?php

/**
 * Pipl unique name search
 *
 * PHP version 7
 *
 * @package   UniqueName
 * @author    Queen tech <info@queentechsolutions.net>
 * @copyright 2017-2018 Queen tech
 * @access    public
 * @license   http://www.queentechsolutions.net/ BSD Licence
 * @version   CVS: 1.0.0
 * @link      http://www.queentechsolutions.net/
 */

namespace Skopenow\NameInfo\UniqueName\Search;

use Skopenow\NameInfo\UniqueName\Search\UniqueNameSearchInterface;

/**
 * PiplUniqueNameSearch class
 *
 *
 * @package   UniqueName
 * @author    Queen tech <info@queentechsolutions.net>
 * @copyright 2017-2018 Queen tech
 * @access    public
 * @license   http://www.queentechsolutions.net/ BSD Licence
 * @version   CVS: 1.0.0
 * @link      http://www.queentechsolutions.net/
 */

class PiplUniqueNameSearch implements UniqueNameSearchInterface
{

    /**
    * $apiKey
    *
    *   
    * @var string
    */
    private $apiKey;

    /**
    * $apiUrl
    *
    *   
    * @var string
    */
    private $apiUrl = "http://api.pipl.com/search/?key=%s&country=US&first_name=%s&last_name=%s";

    /**
    * $firstName
    *
    *   
    * @var string
    */
    private $firstName;

    /**
    * $lastName
    *
    *   
    * @var string
    */
    private $lastName;

    /**
    * $jsonResponse
    *
    *   
    * @var string
    */
    private $jsonResponse;

    /** Constructor
    *
    * 
    * @access public
    * @param string $firstName
    * @param string $lastName
    * @param string $apiKey
    * @return void
    */
    public function __construct(string $firstName, string $lastName, string $apiKey = '')
    {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->apiKey = $apiKey;
    }

    /**
    * search
    *
    * 
    * @access public
    * @return mixed
    */
    public function search()
    {
        try{
            $results = $this->getResults();
            return $results;
        } catch (\Exception $ex) {
            $ex_syn = "[error] " . date("d/m/Y : H:i:s", time()) . "\n";
            $ex_syn .= $ex . "\n";
            $ex_syn .= "--------------------------------------------------------\n";
            // Todo: logger
            $loggerData = [
                  "Exception" => $ex_syn,
                  "RequestData" => sprintf($this->apiUrl, $this->apiKey, $this->firstName, $this->lastName), 
                  "ReportId" => config('state.report_id'),
                  "Method" => __METHOD__,
                  "Message" => $ex->getMessage(),
                  "CurlOptions" => $this->getCurlOptions()
            ];
            // Todo: logger
            return false;
        }
        return false;
    }

    /**
    * getResults
    *
    * 
    * @access public
    * @return array
    */
    public function getResults() : array
    {
        $personID = config('state.report_id');
        $cacheKey = $this->firstName.','.$this->lastName;
        $log ="";
        $gender = false;
        // Check cache
        if(\Cache::has($cacheKey."_pipl")) {
            $piplResultsCount = \Cache::get($cacheKey."_pipl");
            $log .= "get PIPL results count from cache , name : ".$cacheKey." count : ".$piplResultsCount."\n";            
            // Todo here:logger
            $loggerData = [
                  "Message" => $log,
                  "ReportId" => $personID
            ];
            // Todo here: logger
            
            return array('resultsCount' => $piplResultsCount, 'gender' => $gender);
        }
        // validate and search
        $this->validate($this->firstName, $this->lastName);
        $response = $this->sendRequest();
        // check response
        if(!$this->checkResponse($response)){
            throw new \Exception("Pipl Response Error\n");
        }
        if(isset($this->jsonResponse["@persons_count"])) {
            if (isset($this->jsonResponse['possible_persons'])) {
                   $gender = $this->jsonResponse['possible_persons'][0]['gender']['content']??false;
            }
            $piplResults = array('resultsCount' => $this->jsonResponse["@persons_count"], 'gender' => $gender);
            $log .= "request Unique Name from PIPL api, name : ".$cacheKey."\n";
            if(!empty($piplResults['resultsCount'])) {
                $piplResultsCount = $piplResults['resultsCount'];
                $log .= "  Results Count : (".$piplResultsCount.") \n";
                \Cache::put($cacheKey."_pipl", $piplResultsCount, 60*24*30*6);
            }
            // Todo here:logger
            $loggerData = [
                  "Message" => $log,
                  "ReportId" => $personID
            ];
            // Todo here: logger
            
            return $piplResults;
        }

        return ['resultsCount' => 0];
    }

    /**
    * validate
    *
    * 
    * @access private
    * @param string $firstName
    * @param string $lastName
    * @return void
    */
    private function validate(string &$firstName, string &$lastName)
    {
        if(empty($firstName) || empty($lastName))
        {
            throw new \Exception("Missing " . (empty($firstName)?"FirstName":"LastName") . " \n");
        }

        $firstName = urldecode($firstName);
        $lastName = urldecode($lastName);

        $firstName = preg_replace('#\W.*#', '', $firstName);
        $lastName = preg_replace('#\W.*#', '', $lastName);

        if(mb_strlen($firstName) < 3 || mb_strlen($lastName) < 3)
        {
            throw new \Exception("FirstName or lastName must be greater than 3 characters \n");
        }
    }

    /**
    * sendRequest
    *
    * 
    * @access public
    * @return array
    */
    public function sendRequest() : array
    {
        try{
        $apiUrl = sprintf($this->apiUrl, $this->apiKey, $this->firstName, $this->lastName);
        $response = loadService('HttpRequestsService')->fetch($apiUrl, "POST", $this->getCurlOptions());
            $response->getBody()->rewind();
        $body = $response->getBody()->getContents();
        return [
                  "body" => $body
               ];
        } catch(\Exception $e) {
            // Todo: logger
            $url = sprintf($this->apiUrl, $this->apiKey, $this->firstName, $this->lastName);
            $loggerData = [
                  "Exception" => "Curl Exception occured when fetching url: $url \n",
                  "RequestData" => $url, 
                  "ReportId" => config('state.report_id'),
                  "Method" => __METHOD__,
                  "Message" => $e->getMessage(),
                  "CurlOptions" => $this->getCurlOptions()
            ];
            // Todo: logger
            
            return ['body' => ''];
        }
    }

    /**
    * checkResponse
    *
    * 
    * @access private
    * @param array $response
    * @return bool
    */
    public function checkResponse($response) : bool
    {
        if (isset($response['error_no'])){
            return false;
        }

        $this->jsonResponse = isset($response['body'])?json_decode($response['body'], true):array();
        if(empty($this->jsonResponse)) {
            if ($this->jsonResponse) {
                notifyDev('PIPL API bad return:' . print_r($this->jsonResponse,1));
            }
            return false;
        }
        if($this->jsonResponse["@http_status_code"] != 200) {
            notifyDev('PIPL API bad return:' . print_r($this->jsonResponse,1));
            return false;
        }

        return true;
    }

    /**
    * getCurlOptions
    *
    * 
    * @access private
    * @return array
    */
    private function getCurlOptions() : array
    {
        return [
            "form_params" => 
                [
                    "timeout" => 2
                ]
        ];
    }
}