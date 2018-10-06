<?php
/**
 * Created by PhpStorm.
 * User: todary
 * Date: 22/10/17
 * Time: 01:24 م
 */

namespace Skopenow\Api\Requests\GetReport;

use Skopenow\Api\Library\Auth\AuthInterface;
use Skopenow\Api\Library\Validation\ValidationInterface;
use Skopenow\Api\Requests\RequestInterface;
use Skopenow\Api\Requests\RequestAuthInterface;
use Skopenow\Api\Library\Validation\ValidationMethod;
use Skopenow\Api\Library\Mapping\MappingInterface;
use Skopenow\Api\Library\GetResult\GetResultInterface;

use App\Models\Report;

class GetReport implements RequestInterface, RequestAuthInterface
{
    protected $headers = [];
    protected $data;
    protected $errors = [];

//    /**
//     * Validation rules
//     * @var array this variable have the rule validation request
//     */
//    protected $arrayValidation = ['id' => 'required'];

    protected $arrayValidation = [
        'key' => ['id' => 'required'],
        'input' => [
            'name' => 'array',
            'location' => 'array',
            'address' => 'array',
            'phone' => 'array',
            'email' => 'array',
            'birthday' => 'array',
            'age' => 'array',
            'job' => 'array',
            'school' => 'array',
            'username' => 'array',
            "suggestion_key" => 'string',
        ],
        'filters' => [
            'name' => "string|in:nickname,exact,all",
            'location' => 'string|in:0,10,100,state,all',
            'family' => 'boolean',
            'exact' => 'boolean',
            'limit' => 'integer',
            'offset' => 'integer',
            'hide' => 'array|in:summary,locations,nicknames,relatives,phones,emails,websites,profiles,metadata,score,urls,photos,tags,ip',
        ],
        'output' => [
            'type' => 'string|in:json,pdf',
            'destination' => 'string|in:url,email,ftp',
            'url' => 'string',
            'email' => 'string',
            'ftp' => 'json',
        ],
    ];

    protected $validationCodes = ['id' => 300];

    protected $report;

    protected $result = [];
    protected $finalResult = [];
    protected $mappingInterfaceObject;
    protected $getResultInterfaceObject;
    protected $reportEntryPoint;


    /**
     * Authentication constructor.
     * @param array $headers
     * @param $data
     */
    public function __construct(array $headers, $data)
    {
        $this->headers = $headers;
        $this->data = $data;
    }


    public function validation(ValidationInterface $validatObject): bool
    {

        $result = $validatObject->validation($this->data, $this->arrayValidation, $this->validationCodes);
//        $result = $validatObject->validationForGetReport($this->data, $this->arrayValidation, $this->validationCodes);
        return $result;
    }

    public function prepareRequest()
    {
        // TODO: Implement prepareRequest() method.
    }

    /**
     * @param AuthInterface $objectAuth
     * @return bool
     */
    public function requestAuth(AuthInterface $authObject): bool
    {
        $result = $authObject->authAPI();
        return $result;
    }

    public function checkReport()
    {
        config(['state.report_id' => $this->data['key']['id']]);
        $this->report = $this->reportEntryPoint->getReport();
        if (isset($this->report) && !empty($this->report)) {
            return true;
        }
        $this->errors[] = ['not_found', 805];
        return false;
    }

    public function checkGetResult()
    {
        $requestArray = $this->mappingInterfaceObject->getRequestArray($this->data, $this->report['id']);
        $result = $this->getResultInterfaceObject->getResultsRequest($requestArray);
        if (!$result['status']) {
            $this->errors[] = ['error', 500];
            return false;
        }
        $this->result['result'] = $result;
        return true;
    }

    public function checkGetsummary()
    {
        $summary = $this->getResultInterfaceObject->getSummary($this->report['id']);
        $this->result['summary'] = $summary;
    }

    public function mappingOutput()
    {
        $this->finalResult = $this->mappingInterfaceObject->mappingResultData($this->report, $this->result['result'], $this->data);
        $summary = $this->mappingInterfaceObject->mappingSummaryData($this->result);
        $this->finalResult = array_merge($this->finalResult, $summary);
    }

    public function filterHiden()
    {
        $this->finalResult = $this->mappingInterfaceObject->removeHideData($this->data, $this->finalResult);
    }


    /**
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * @return array
     */
    public function getFinalResult(): array
    {
        return $this->finalResult;
    }

    /**
     * @param mixed $getResultInterfaceObject
     */
    public function setGetResultInterfaceObject(GetResultInterface $getResultInterfaceObject)
    {
        $this->getResultInterfaceObject = $getResultInterfaceObject;
    }

    /**
     * @param mixed $mappingInterfaceObject
     */
    public function setMappingInterfaceObject(MappingInterface $mappingInterfaceObject)
    {
        $this->mappingInterfaceObject = $mappingInterfaceObject;
    }

    /**
     * @param mixed $reportEntryPoint
     */
    public function setReportEntryPoint($reportEntryPoint)
    {
        $this->reportEntryPoint = $reportEntryPoint;
    }

}
