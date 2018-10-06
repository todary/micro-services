<?php

/**
 * Created by PhpStorm.
 * User: todary
 * Date: 22/10/17
 * Time: 01:25 م
 */

namespace Skopenow\Api\Requests\RegenerateReport;

use Skopenow\Api\Library\Auth\AuthInterface;
use Skopenow\Api\Library\Validation\ValidationInterface;
use Skopenow\Api\Requests\RequestAuthInterface;
use Skopenow\Api\Requests\RequestInterface;
use App\Models\Report;


class RegenerateReport implements RequestInterface, RequestAuthInterface
{
    protected $headers = [];
    protected $data;

    protected $reportEntryPoint;
    protected $errors = [];
    protected $report;
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
        return $result;
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
        return false;
    }

    public function saveReport()
    {
        $options = null;
        unset($this->data['key']);
        $options = $this->data;
        $options += $this->report['api_options'];

        return $this->reportEntryPoint->updateApiOptions($this->report['id'], $options);

    }

    /**
     * @param mixed $reportEntryPoint
     */
    public function setReportEntryPoint($reportEntryPoint)
    {
        $this->reportEntryPoint = $reportEntryPoint;
    }

    /**
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }


    public function prepareRequest()
    {
        // TODO: Implement prepareRequest() method.
    }

    /**
     * @return mixed
     */
    public function getReport()
    {
        return $this->report;
    }


}
