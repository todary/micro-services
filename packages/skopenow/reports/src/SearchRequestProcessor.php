<?php
namespace Skopenow\Reports;

use Skopenow\Reports\Models\Report;

/**
*
*/
class SearchRequestProcessor
{
    protected $requestData;
    protected $validationErrors = [];
    protected $report;

    protected $nameInfoService;

    public function __construct(array $requestData, $nameInfoService)
    {
        $this->validationService = loadService('validation');
        $this->requestData = $requestData;
        $this->nameInfoService = $nameInfoService;
    }

    public function isValidRequest()
    {
        $inputs = $this->requestData;
        try {
            $this->validationService->validate(new \ArrayIterator($inputs));
            $validationResults = $this->validationService->getResults();
        } catch (\Exception $e) {
            $validationResults = new \ArrayIterator;
            $errors['service']['message'] = $e->getMessage();
            $required_in = true;
        }

        $isValid = true;
        foreach ($validationResults as $rule => $validationResult) {
            foreach ($validationResult as $singleResult) {
                if (!$singleResult['isValid']) {
                    $isValid = false;
                    if (!isset($this->validationErrors[$rule])) {
                        $this->validationErrors[$rule] = [];
                    }
                    $this->validationErrors[$rule][] = $singleResult['error'];
                }
                //valid phone
                //valid email
                //valid username
                //valid address
            }
        }

        $fieldsRequired = false;

        if (!isset($inputs['name']) || empty(array_filter($inputs['name']))) {
            $fieldsRequired = true;

            if (!isset($this->validationErrors['name'])) {
                $this->validationErrors['name'] = [];
            }

            $this->validationErrors['name'][] = 'name is required';
        }

        if (!isset($inputs['location']) || empty(array_filter($inputs['location']))) {
            $fieldsRequired = true;

            if (!isset($this->validationErrors['location'])) {
                $this->validationErrors['location'] = [];
            }

            $this->validationErrors['location'][] = 'location is required';
        }

        if ($isValid && isset($inputs['address']) && ! empty(array_filter($inputs['address']))) {
            $this->validationErrors = [];
            $fieldsRequired = false;
        }

        if ($isValid && isset($inputs['phone']) && ! empty(array_filter($inputs['phone']))) {
            $this->validationErrors = [];
            $fieldsRequired = false;
        }

        if ($isValid && isset($inputs['username']) && ! empty(array_filter($inputs['username']))) {
            $this->validationErrors = [];
            $fieldsRequired = false;
        }

        if ($isValid && isset($inputs['email']) && ! empty(array_filter($inputs['email']))) {
            $this->validationErrors = [];
            $fieldsRequired = false;
        }

        return $isValid & (!$fieldsRequired);
    }

    public function getValidationErrors()
    {
        return $this->validationErrors;
    }

    public function getReport()
    {
        $inputs = $this->requestData;
        //set report data
        $report = new Report();

        // if (isset($inputs['email']) && is_array($inputs['email'])) {
        //     $extra_schools = SearchApis::extractSchoolsFromEmails($email);
        //     $school = array_merge($school, $extra_schools);
        // }

        if (isset($inputs['name'])) {
            // $namesParts = $this->extractNamesParts($inputs['name']);

            // $report->first_name = $namesParts['first_names'];
            // $report->middle_name = $namesParts['middle_names'];
            // $report->last_name =  $namesParts['last_names'];

            $names = array_map(function ($item) {
                return $this->nameInfoService->honorificNicknames($item);
            }, $inputs['name']);

            $report->full_name = $names;
            if ($report->full_name) {
                $report->searched_names = $report->full_name;
            }
        }

        if (isset($inputs['birthdate'])) {
            $report->date_of_birth = $inputs['birthdate'];
        }

        if (isset($inputs['age'])) {
            $report->age = $inputs['age'];
        }

        if (isset($inputs['address'])) {
            $report->address =$inputs['address'];
            $report->street = $report->address;
        }

        if (isset($inputs['location'])) {
            $report->city = $inputs['location'];
        }

        $report->state = '';
        $report->country = '';
        $report->zip = [];

        if (isset($inputs['phone'])) {
            $report->phone = $inputs['phone'];
        }

        if (isset($inputs['occupation'])) {
            $report->company = $inputs['occupation'];
        }

        if (isset($inputs['email'])) {
            $report->email = $inputs['email'];
        }

        if (isset($inputs['username'])) {
            $report->usernames = $inputs['username'];
        }

        if (isset($inputs['school'])) {
            $report->school = $inputs['school'];
        }

        return $report;
    }
}
