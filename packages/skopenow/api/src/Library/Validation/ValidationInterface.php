<?php
/**
 * Created by PhpStorm.
 * User: todary
 * Date: 13/11/17
 * Time: 12:00 م
 */

namespace Skopenow\Api\Library\Validation;

/**
 * Interface ValidationInterface
 * @package Skopenow\Api\Library\Validation
 */
interface ValidationInterface
{
    /**
     * @param array $data
     * @param array $rules
     * @return mixed
     */
    public function validation(array $data, array $rules):bool ;


}