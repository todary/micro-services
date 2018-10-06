<?php

/**
 * Created by PhpStorm.
 * User: todary
 * Date: 23/11/17
 * Time: 01:13 م
 */

namespace Skopenow\Api\Library\SearchCompleted;

use App\Models\ApiWebhookRequest;
use Skopenow\Api\Library\Mapping\Mapping;
use Skopenow\Api\Library\GetResult\GetResult;
use App\User;

class SearchCompleted
{
    protected $report;
    protected $mappingObject;
    protected $searchObject;
    protected $finalResult;

    public function __construct($report)
    {
        $this->report = $report;
        $this->mappingObject = new Mapping();
        $this->searchObject = new GetResult();
    }


    public function onSearchCompleted()
    {


        $api_options = $this->report['api_options'];

        if (isset($api_options['output']) && !empty($api_options['output'])) {
            $api_options['filters']['limit'] = 30;
            $api_options['filters']['offset'] = 0;
            if (isset($api_options['output']["type"]) && $api_options['output']["type"] == "json") {
                $requestArray = $this->mappingObject->getRequestArray($api_options, $this->report['id']);

                $result = $this->searchObject->getResultsRequest($requestArray);

                $result['summary'] = $this->searchObject->getSummary($this->report['id']);

                $this->finalResult = $this->mappingObject->mappingResultData($this->report, $result, $api_options);

                $summary = $this->mappingObject->mappingSummaryData($result);
                $this->finalResult = array_merge($this->finalResult, $summary);
                $this->finalResult = $this->mappingObject->removeHideData($api_options, $this->finalResult);

                if (isset($this->finalResult) && isset($api_options['output']["url"]) && !empty($api_options['output']["url"])) {
                    $this->saveApiWebhook($api_options['output']["url"]);
                }
                dd($this->finalResult);
                dd($summary);
            }
        }
    }

    public function saveApiWebhook($url)
    {
        $resultfinal = \CJSON::encode($this->finalResult);

        $data = [];
        $apiKey = User::getApiKey($this->report['user_id']);
        $data['person_id'] = $this->report['id'];
        $data['url'] = $url;
        $data['data'] = $resultfinal;
        $hashed = hash_hmac("sha1", $resultfinal, $apiKey->api_key);
        $data['hash'] = $hashed;
        $data['dateline'] = new \CDbExpression("now()");
        $apiWebhookRequest = ApiWebhookRequest::create($data);
        $this->sendWebhook($apiWebhookRequest);
        var_dump($data->person_id);
        die();

        \SearchApiController::sendWebhook($apiWebhookRequest);


    }

    public function sendWebhook($apiWebhookRequest){

    }
}