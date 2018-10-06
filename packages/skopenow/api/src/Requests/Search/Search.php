<?php
/**
 * Created by PhpStorm.
 * User: todary
 * Date: 22/10/17
 * Time: 01:25 م
 */

namespace Skopenow\Api\Requests\Search;

use Illuminate\Support\Facades\Validator;
use Skopenow\Api\Library\Auth\AuthInterface;
use Skopenow\Api\Library\AuthUser\AuthUserInterface;
use Skopenow\Api\Library\Validation\ValidationInterface;
use Skopenow\Api\Requests\Authentication\Authentication;
use Skopenow\Api\Requests\RequestAuthInterface;
use Skopenow\Api\Requests\RequestInterface;
use Skopenow\Api\Library\Errors\ErrorsRequest;
use Skopenow\Api\Library\Auth\KeyAuthMethod;
use Skopenow\Api\Models\User;
use Skopenow\Api\Library\Validation\ValidationMethod;


//use Illuminate\Support\Facades\Validator;

//use  Illuminate\Validation\Factory;
//use Illuminate\Validation\Validator;


//use Illuminate\Contracts\Validation\Validator;
//use Illuminate\Contracts\Validation\Factory;

use Illuminate\Http\Request;

//use Illuminate\Validation\Factory;


/**
 * Class Search
 * @package Skopenow\Api\Requests\Search
 */
class Search implements RequestInterface, RequestAuthInterface
{
    /**
     * Headers's request
     * @var array this variable have the headers request
     */
    protected $headers = [];
    /**
     * Data's request
     * @var this variable have the data request
     */
    protected $data;

    protected $reportEntryPoint;

    private $enc_key = 'VkYp3s6v9y$B?E(H';
    /**
     * Validation rules
     * @var array this variable have the rule validation request
     */
    protected $arrayValidation = [
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


    protected $validationCodes = [
        'input' => [
            'name' => 100,
            'location' => 101,
            'address' => 102,
            'phone' => 103,
            'email' => 104,
            'birthday' => 105,
            'age' => 106,
            'job' => 107,
            'school' => 108,
            'username' => 109,
        ],
        'filters' => [
            'name' => 110,
            'location' => 111,
            'family' => 112,
            'exact' => 113,
            'hide' => 114,
        ],
        'output' => [
            'type' => 115,
            'destination' => 116,
            'url' => 117,
            'email' => 118,
            'ftp' => 119,
        ],
        'default' => [
            'empty_data' => 200,
            'invalid_data' => 201,
        ],
    ];

    protected $errorsCode = [
        100 => ['code' => 400, 'message' => 'invalid location'],
        101 => ['code' => 400, 'message' => 'invalid location'],
        102 => ['code' => 400, 'message' => 'invalid phone'],
        103 => ['code' => 400, 'message' => 'invalid address'],
        104 => ['code' => 400, 'message' => 'invalid address'],
        105 => ['code' => 400, 'message' => 'invalid address'],
        106 => ['code' => 400, 'message' => 'invalid address'],
        107 => ['code' => 400, 'message' => 'invalid address'],
        108 => ['code' => 400, 'message' => 'invalid address'],
        109 => ['code' => 400, 'message' => 'invalid address'],
        110 => ['code' => 400, 'message' => 'invalid address'],
        111 => ['code' => 400, 'message' => 'invalid address'],
        112 => ['code' => 400, 'message' => 'invalid address'],
        113 => ['code' => 400, 'message' => 'invalid address'],
        114 => ['code' => 400, 'message' => 'invalid address'],
        115 => ['code' => 400, 'message' => 'invalid address'],
        116 => ['code' => 400, 'message' => 'invalid address'],
        117 => ['code' => 400, 'message' => 'invalid address'],
        118 => ['code' => 400, 'message' => 'invalid address'],
        119 => ['code' => 400, 'message' => 'invalid address'],
        120 => ['code' => 400, 'message' => 'Default'],
        200 => ['code' => 400, 'message' => 'Empty Data'],
        201 => ['code' => 400, 'message' => 'Empty Data'],

    ];

    /**
     * @var array this variable all errors that output from validation
     */
    protected $validateError = [];

    /**
     * @var array this variable all errors that output from validation
     */
    protected $authenticationError;


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

    /**
     * @param AuthInterface $authObject
     * @return bool
     */
    public function requestAuth(AuthInterface $authObject): bool
    {
        $result = $authObject->authAPI();
        return $result;
    }

    /**
     * @param ValidationInterface $validatObject
     * @return bool
     */
    public function validation(ValidationInterface $validatObject): bool
    {
        $result = $validatObject->validation($this->data, $this->arrayValidation, $this->validationCodes);
//        if (!$result) {
//            $this->validateError = $validatObject->getValidationError();
//        }
        return $result;
    }


    public function checkSearchResult()
    {


        $result = $this->reportEntryPoint->generateReport($this->data['input'], true, $this->data);
//    dd($this->data['input']);


        return $result;
        $searchData = $objectEntryPoint->getSuggestions($result);

        dd($searchData);
        acceptSuggestion($result, $searchData[0]);
        var_dump($result);
        die();
        var_dump($objectEntryPoint->getErrors());
        die();
    }

    public function getResultSuggestion($reportId)
    {
        return $this->reportEntryPoint->getSuggestions($reportId);
    }

    public function prepareSuggestion($suggestionData, $reportId): array
    {

        $data = [];
        foreach ($suggestionData as $key => $suggestion) {
            $person = array(
                'person_id' => EncryptID($reportId),
                'first_name' => $suggestion['first_name'],
                'middle_name' => $suggestion['middle_name'],
                'last_name' => $suggestion['last_name'],
            );

            $suggestion["suggestion_key"] =
                \CEncryption::EncryptID(gzdeflate(\CJSON::encode($person)),
                    $this->enc_key);
            $data[] = $suggestion;
        }
        return $data;
    }

    public function prepareSaveSearchReport()
    {
        $result = [];
        $data = \CJSON::decode(gzinflate(\CEncryption::DecryptID($this->data["input"]["suggestion_key"], $this->enc_key)));
        $model = \Persons::model()->findByPk(DecryptID($data['person_id']));

        if (!empty($data) && !empty($model)) {
            $result ['data'] = $data;
            $result ['report_id'] = $model->id;
        }
        return $result;
    }


    public function acceptSuggestion($reslutSave)
    {
        return $this->reportEntryPoint->acceptSuggestion($reslutSave['report_id'], $reslutSave['data']);
    }

    public function startSearch($reportId)
    {
        $this->reportEntryPoint->startSearch();
    }

//    /**
//     * Make Mapping with array code
//     * @return array
//     */
//    protected function validationMappingCode(): array
//    {
//        $errorsCode = [];
//
//        if (!empty($this->validateError)) {
//            foreach ($this->validateError as $key => $value) {
//                if (is_array($value)) {
//                    foreach ($value as $keyField => $error) {
//                        $errorsCode [] = $this->validationCodes[$key][$keyField];
//                    }
//
//                }
//            }
//        }
//
//
//        return $errorsCode;
//    }

//    /**
//     * Get Errors for user
//     * @param array $codes
//     * @return array
//     */
//    protected function getErrors($codes = []): array
//    {
//        $errors = [];
//
//        if (!empty($codes)) {
//            foreach ($codes as $key => $value) {
//                if (isset($this->errorsCode[$value])) {
//                    $errors [] = $this->errorsCode[$value];
//                } else {
//                    $errors [] = $this->errorsCode[120];
//                }
//
//            }
//        }
//
//
//        return $errors;
//
//    }

    /**
     * Start Request
     * @return string
     */
    public function prepareRequest()
    {

        if (isset($this->headers['x-api-key'])) {
            $user = User::getUser($this->headers['x-api-key']);
        } else {
            return response()->json(['error' => ErrorsRequest::getError('required', 200, 'key')])->content();
        }
        $auth = new AuthMethod();
        $authentication = $auth->authAPI($user);
        if (!$authentication) {
            return response()->json(['error' => $auth->getError()])->content();
        }

        $this->validation();


        $error = new ErrorsRequest('test', 5);
        $errorsCode = $this->validationMappingCode();

        $errors = $this->getErrors($errorsCode);

        if (!empty($errors)) {
            return response()->json(['error' => $errors[0]])->content();
        } else {
            return response()->json(['message' => 'Done', 'code' => 200])->content();
        }
    }

//    /**
//     * @return array
//     */
//    public function getValidationError(): array
//    {
//        return ErrorsRequest::getErrors(0);
//    }

//    /**
//     * @return array
//     */
//    public function getAuthenticationError(): array
//    {
//        return $this->authenticationError;
//    }

    /**
     * @param mixed $reportEntryPoint
     */
    public function setReportEntryPoint($reportEntryPoint)
    {
        $this->reportEntryPoint = $reportEntryPoint;
    }

    /**
     * @return mixed
     */
    public function getReportEntryPoint()
    {
        return $this->reportEntryPoint;
    }


}

