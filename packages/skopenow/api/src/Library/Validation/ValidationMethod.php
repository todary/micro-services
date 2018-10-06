<?php
/**
 * Created by PhpStorm.
 * User: todary
 * Date: 13/11/17
 * Time: 12:00 م
 */

namespace Skopenow\Api\Library\Validation;

use Illuminate\Support\Facades\Validator;
use Skopenow\Api\Library\Errors\ErrorsRequest;

/**
 * Class ValidationMethod
 * @package Skopenow\Api\Library\Validation
 */
class ValidationMethod implements ValidationInterface
{

    protected $validationError = array();


    /**
     * @param array $data
     * @param array $rules
     * @return mixed
     */
    public function validation(array $data, array $rules, array $errorCode = []): bool
    {
        if (!empty($data)) {
            foreach ($data as $key => $value) {
                if (is_array($value)) {
                    if (isset($rules[$key])) {
                        $validator = Validator::make($data[$key], $rules[$key]);
                        if ($validator->fails()) {
                            $this->validationError [$key] = $validator->errors()->messages();
                            if (!empty($errorCode)) {
                                foreach ($this->validationError [$key] as $keyValidation => $errorMessage) {
                                    ErrorsRequest::setError('api_error', $errorCode[$key][$keyValidation]);
                                }
                            }
                        }
                    } else {
                        ErrorsRequest::setError('api_error', 920, $key);
                    }
                } else {
                    ErrorsRequest::setError('api_error', 920, $value);
                }
            }

        } else {
            ErrorsRequest::setError('api_error', 922);
            return false;
        }

        if (!empty(ErrorsRequest::getErrors(0))) {
            return false;
        }
        return true;
    }


    public function validationForGetReport(array $data, array $rules, array $errorCode = []): bool
    {
        foreach ($data as $key => $value) {
            if (isset($rules[$key])) {
                $validator = Validator::make($data, $rules);
            }
            if ($validator->fails()) {

                if (!empty($errorCode)) {
                    ErrorsRequest::setError('api_error', $errorCode[$key]);

                }
            }
        }

        if (!empty(ErrorsRequest::getErrors(0))) {
            return false;
        }

        return true;
    }

    /**
     * @return array
     */
    public function getValidationError(): array
    {
        return $this->validationError;
    }


}