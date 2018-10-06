<?php
/**
 * Created by PhpStorm.
 * User: todary
 * Date: 07/11/17
 * Time: 01:11 م
 */

namespace Skopenow\Api\Library\Errors;

interface ErrorInterface
{
    /**
     * @param $type
     * @param $code
     * @param null $extraMessage
     * @param bool $json
     * @return mixed
     */
    public static function getError($type, $code, $extraMessage = null, $json = false);

    /**
     * @param bool $json
     * @return mixed
     */
    public static function getErrors($json = true);

    /**
     * @param $type
     * @param $code
     * @return mixed
     */
    public static function getMessage($type, $code);


}