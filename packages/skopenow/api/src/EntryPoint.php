<?php

namespace Skopenow\Api;

use App\Models\ApiWebhookRequest;
use Skopenow\Api\RequestFactory\RequestFactory;
use Skopenow\Api\Requests\Search\Search;
use Skopenow\Api\Requests\GetReport\GetReport;
use Skopenow\Api\Requests\RegenerateReport\RegenerateReport;
use Skopenow\Api\Library\Auth\KeyAuthMethod;
use Skopenow\Api\Library\Validation\ValidationMethod;
use Skopenow\Api\Library\Errors\ErrorsRequest;
use Skopenow\Api\Library\AuthUser\AuthUser;
use Skopenow\Api\Library\Authorization\Authorization;
use Skopenow\Api\Library\MappingAndSearchRequest\MASR;
use Skopenow\Api\Library\Mapping\Mapping;
use Skopenow\Api\Library\GetResult\GetResult;
use Skopenow\Api\Library\SearchCompleted\SearchCompleted;

use Skopenow\Reports\EntryPoint as EnteryPointObject;

class EntryPoint
{
    /**
     * @param string $path
     * @param string $method
     * @param array $headers
     * @param $data
     */
    public function processRequest(string $path, string $method, array $headers, $data)
    {


        $requestObject = new RequestFactory();
        $result = $requestObject->create($path, $method, $headers, $data);

        $headers['x-api-key'] = 'e8f9e1de352e11cbeb9635ab470ec798f575fbe7';

        $keyAuthObject = new KeyAuthMethod($headers);
        $authResult = $result->requestAuth($keyAuthObject);

        if (!$authResult) {
            return $keyAuthObject->getErrors();
        }


        $validatObject = new ValidationMethod();
        $validationResult = $result->validation($validatObject);

        if (!$validationResult) {

            echo response()->json(ErrorsRequest::getErrors(0))->content();
            die();
        }

        $authorizationObject = new Authorization($keyAuthObject->getAuthUser());

        if ($result instanceof Search) {



            if (!$authorizationObject->canSearch()) {
                return $authorizationObject->getErrors();
            }
            if (!$authorizationObject->checkLimit()) {
                return $authorizationObject->getErrors();
            }


            /** start  search request */
            $result->setReportEntryPoint(new EnteryPointObject);
            if (isset($data['input']['suggestion_key'])) {
                $reslutSave = $result->prepareSaveSearchReport();
                if (empty($reslutSave)) {

                }

                $resultSave = $result->acceptSuggestion($reslutSave);
                var_dump(DecryptID($resultSave['person_id']));
                die();

            }
            $reportId = $result->checkSearchResult();

            if (!$reportId) {
//                return $result->getReportEntryPoint()->getErrors();
            }
            $suggestionData = $result->getResultSuggestion($reportId);

            if (empty($suggestionData)) {

            }
            if (count($suggestionData) != 1) {
                return $result->prepareSuggestion($suggestionData, $reportId);
            }
            die();


            /** end  search request */


            $authUserObject = new AuthUser($headers);


//            if (empty($authUserObject->getUser())){
//                echo response()->json(ErrorsRequest::getErrors(0))->content();
//                die();
//            }
//


//
//            if (!$authUserObject->exceededUser()) {
//                echo response()->json(ErrorsRequest::getErrors(0))->content();
//                die();
//            }

            var_dump('heer');
            die();


        } elseif ($result instanceof GetReport) {
            $result->setReportEntryPoint(new EnteryPointObject);
            if (!$result->checkReport()) {
                return $result->getErrors();
            }
            $objectMapping = new Mapping();
            $objectGetResult = new GetResult();

            $result->setMappingInterfaceObject($objectMapping);
            $result->setGetResultInterfaceObject($objectGetResult);
            $resultCheck = $result->checkGetResult();
            if (!$resultCheck) {
                return $result->getErrors();
            }
            $result->checkGetsummary();
            $result->mappingOutput();
            $result->filterHiden();
            var_dump($result->getFinalResult());
            die();
            return $result->getFinalResult();

        } elseif ($result instanceof RegenerateReport) {

//            $data= [];
//            $data['url']='test';
//            ApiWebhookRequest::create($data);
//            var_dump('heer');
//            die();
            /** start  search request */
            $result->setReportEntryPoint(new EnteryPointObject);
            if (!$result->checkReport()) {
                return $result->getErrors();
            }
            if (!$result->saveReport()) {
                return $result->getErrors();
            }

            $this->afterSearch($result->getReport());

        }


    }


    public function afterSearch($reportModel)
    {


        $prepareReport = new SearchCompleted($reportModel);
        $prepareReport->onSearchCompleted();

    }


}
