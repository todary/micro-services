<?php
/**
 * Created by PhpStorm.
 * User: todary
 * Date: 07/11/17
 * Time: 01:11 م
 */

namespace Skopenow\Api\Library\Errors;

use Skopenow\Api\Library\Errors\ErrorInterface;

/**
 * Class ErrorsRequest
 * @package Skopenow\Api\Library\Classes
 */
class ErrorsRequest implements ErrorInterface
{

    private static $_messages = array();
    private static $_messagesApi;



    /**
     * @param $type
     * @param $code
     * @param null $extraMessage
     */
    public static function setError($type, $code, $extraMessage = null)
    {
//        self::$_messages['status'] = false;
        self::$_messages['errors'][] = self::getError($type, $code, $extraMessage);
    }

    /**
     * @param $type
     * @param $code
     * @param bool $json
     * @param null $extraMessage
     * @return array|string
     */
    public static function getError($type, $code, $extraMessage = null, $json = false)
    {
        if (self::$_messagesApi == null) {
            self::$_messagesApi = require app()->basePath(
                '/packages/skopenow/api/src/Library/Errors/error_messages.php');
        }
//        self::$_messages['status'] = false;
//        self::$_messages['type'] = $type;

        if (!isset(self::$_messagesApi[$type][$code])) {
            $type = 'error';
            $code = 500;
        }

        $status = self::$_messagesApi[$type][$code][0];
        $extraMessage = $extraMessage ? ' ' . $extraMessage : null;
        $message = self::$_messagesApi[$type][$code][1] . $extraMessage;
        $error = array(
            'code' => $status,
            'message' => $message,
        );

        return $json ? json_encode($error) : $error;
    }


    /**
     * @param bool $json
     * @return array|null|string
     */
    public static function getErrors($json = true)
    {
        if (!self::$_messages) {
            return null;
        }
        return $json ? json_encode(self::$_messages) : self::$_messages;
    }

    /**
     * @param $type
     * @param $code
     * @return string
     */
    public static function getMessage($type, $code)
    {
        if (self::$_messagesApi == null) {
            self::$_messagesApi = require app()->basePath(
                '/packages/skopenow/api/src/Library/Errors/error_messages.php');
        }

        if (isset(self::$_messagesApi[$type][$code]))
            return self::$_messagesApi[$type][$code][1];
        else
            return "Unknown error!";
    }
}