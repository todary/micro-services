<?php
/**
 * Created by PhpStorm.
 * User: todary
 * Date: 22/10/17
 * Time: 01:23 م
 */

namespace Skopenow\Api\Requests\Authentication;

use Skopenow\Api\Requests\RequestInterface;

/**
 * Class Authentication
 * @package Skopenow\Api\requests\authentication
 */
class Authentication implements RequestInterface
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

    /**
     * @var array this variable all errors that output from validation
     */
    protected $validateError = [];


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

    public function validation()
    {

        if (isset($this->headers['x-api-key'])) {
            return true;
        }else{
            return false;
        }
    }

    public function prepareRequest()
    {
        $validate = $this->validation();

        if ($validate) {
            return response()->json(["code"=>200,"message"=>"Authenticated"])->content();
        } else {
            return response()->json(["code"=>500,"message"=>"Not Authenticated"])->content();
        }
    }
}
