<?php
/**
 * Created by PhpStorm.
 * User: todary
 * Date: 22/10/17
 * Time: 12:49 م
 */

namespace Skopenow\Api\RequestFactory;

use Skopenow\Api\Requests\Authentication\Authentication;
use Skopenow\Api\Requests\DefaultRequest\DefaultRequest;
use Skopenow\Api\Requests\GetReport\GetReport;
use Skopenow\Api\Requests\Search\Search;
use Skopenow\Api\Requests\RegenerateReport\RegenerateReport;

class RequestFactory extends FactoryMethod
{
    /**
     * @param string $path
     * @param string $method
     * @param array $headers
     * @param $data
     * @return Authentication|DefaultRequest|GetReport|Search|UpdateReport
     */
    protected function createRequest(string $path, string $method, array $headers, $data)
    {


        if ($method == 'GET' && $path == '') {
            $defaultObject = new DefaultRequest($headers, $data);
            return $defaultObject;
        } elseif ($method == 'GET' && $path == 'v1') {
            $authorityObject = new Authentication($headers, $data);
            return $authorityObject;
        } elseif ($method == 'POST' && $path == 'v1/search') {
            $searchObject = new Search($headers, $data);
            return $searchObject;
        } elseif ($method == 'GET' && "v1/search/id" == $path) {
            $getReportObject = new GetReport($headers, $data);
            return $getReportObject;
        } elseif ($method == 'POST' && "v1/search/id" == $path) {

            $updateReportObject = new RegenerateReport($headers, $data);
            return $updateReportObject;
        } else {
            echo 'heer';
        }


//        preg_match("/v1\/search\/(.)/", $path)
    }
}