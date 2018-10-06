<?php
/**
 * Created by PhpStorm.
 * User: todary
 * Date: 22/10/17
 * Time: 01:24 م
 */

namespace Skopenow\Api\Requests\DefaultRequest;
class DefaultRequest
{
    protected $headers = [];
    protected $data;

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
}