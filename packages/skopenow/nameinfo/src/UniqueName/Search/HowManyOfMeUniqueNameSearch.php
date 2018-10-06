<?php

/**
 * Howmanyofme unique name search
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
 * HowManyOfMeUniqueNameSearch class
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

class HowManyOfMeUniqueNameSearch implements UniqueNameSearchInterface
{

    /**
    * $apiUrl
    *
    *   
    * @var string
    */
    private $apiUrl = "http://howmanyofme.com/search/";

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
        }catch (\Exception $ex) {
            $ex_syn = "[error] " . date("d/m/Y : H:i:s", time()) . "\n";
            $ex_syn .= $ex . "\n";
            $ex_syn .= "--------------------------------------------------------\n";
            // Todo: logger
            $loggerData = [
                  "Exception" => $ex_syn,
                  "RequestData" => $this->apiUrl, 
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
        $log = "";
        if(\Cache::has($cacheKey."_howmany")) {
            $howManyResultsCount = \Cache::get($cacheKey."_howmany");
            $log .= "get HOWMANYOFME results count from cache , name : ".$cacheKey." count : ".$howManyResultsCount."\n";
            // Begin to do: log here
            ## SearchApis::logData($personID,$log);
            // End to do: log here
            return array('resultsCount' => $howManyResultsCount);
        }
        $log .= "request Unique Name from howmanyofme, name : ".$cacheKey."\n" ;
        $this->validate($this->firstName, $this->lastName);
        $response = $this->sendRequest();
        // check response
        if(!$this->checkResponse($response)){
            throw new \Exception("HowManyOfMe Response Error\n");
        }
        $howManyResults = $this->findMatchAndSearch($response);
        if(isset($howManyResults['resultsCount']) && !empty($howManyResults['resultsCount'])) {
            $howManyResultsCount = $howManyResults['resultsCount'] ;
            $log .= "  Results Count: (".$howManyResultsCount.") \n";
            // save into cache
            \Cache::put($cacheKey."_howmany", $howManyResultsCount, 60*24);
            // Begin to do: log here
            ## SearchApis::logData($personID,$log);
            // End to do: log here
            return $howManyResults;
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
         $entry = loadService('HttpRequestsService');
         try{
            $response = $entry->fetch($this->apiUrl, "POST", $this->getCurlOptions());
            $response->getBody()->rewind();
            $body = $response->getBody()->getContents();
            return array(
                'body' => $body
            );
         } catch(\Exception $e) {
             // Todo: logger
            $loggerData = [
                  "Exception" => "Curl Exception occured when fetching url: $this->apiUrl \n",
                  "RequestData" => $this->apiUrl, 
                  "ReportId" => config('state.report_id'),
                  "Method" => __METHOD__,
                  "Message" => $e->getMessage(),
                  "CurlOptions" => $this->getCurlOptions()
            ];
            // Todo: logger
            
            return array(
                    'body' => ''
             );
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
    private function checkResponse(array $response) : bool
    {
        if (isset($response['error_no']) && $response['error_no']) {
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
            'form_params' => [
                    "given" => $this->firstName,
                    "sur" => $this->lastName,
                    "sub" => "Search Names",
                    "ofage" => "yes",
                ]
            ];
    }

    /**
    * findMatchAndSearch
    *
    * 
    * @access private
    * @param $response array
    * @return array
    */
    private function findMatchAndSearch(array $response) : array
    {
        $howManyResults = [];
        $expressionToMatch = '/<span class="popnum">([^<]+)<\/span>\s*(people in the U\.S\. named|<b>or fewer)/i';
        if(preg_match($expressionToMatch, $response['body'], $match)) {
            $resultsCount = (int) str_ireplace(",", "", $match[1]);
            if ($resultsCount <= 25) {
                $expressionToMatch = '/Names similar to.*?>(\w+)<\/a>.*?<\/ul>/s';
                if(preg_match($expressionToMatch, $response['body'], $match)) {
                    $similarName = trim($match[1]);
                    if (strlen($similarName) > strlen($this->firstName)) {
                        $this->firstName = $similarName;
                        $response = $this->sendRequest();
                        $similarCount = $this->findMatchAndSearch($response);
                        if(isset($similarCount['resultsCount'])) {
                            $resultsCount = ceil(($resultsCount + $similarCount['resultsCount'])/2);
                        }
                    }
                }
            }
            $howManyResults = array("resultsCount" => $resultsCount);
        }
        return $howManyResults;
    }
}