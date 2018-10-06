<?php
/**
 * Created by PhpStorm.
 * User: todary
 * Date: 22/10/17
 * Time: 01:49 م
 */

namespace Skopenow\Api\Requests;

use Skopenow\Api\Library\Validation\ValidationInterface;

/**
 * Interface RequestInterface
 */
interface RequestInterface
{
    public function __construct(array $headers, $data);

    public function validation(ValidationInterface $validatObject):bool;

    public function prepareRequest();

}