<?php

/**
 * PhoneFormatter Class to format array of Phones
 *
 * @category Class
 * @package  MyPackage
 * @author   Queen tech <info@queentechsolutions.net>
 * @license  license http://www.queentechsolutions.net/
 * @link     http://www.queentechsolutions.net/
 */
namespace Skopenow\Formatter\Classes;

use Skopenow\Formatter\Classes\FormatterAbstract;
use Skopenow\Formatter\Classes\FormatterInterface;

/**
 * PhoneFormatter Class to format array of Phones
 *
 * @category Class
 * @package  MyPackage
 * @author   Queen tech <info@queentechsolutions.net>
 * @license  license http://www.queentechsolutions.net/
 * @link     http://www.queentechsolutions.net/
 */
class PhoneFormatter extends FormatterAbstract implements FormatterInterface
{
    /**
     * [formatSingle description]
     * 
     * @param  string $name [description]
     * @return [string]       [description]
     */
    protected function formatSingle($phone)
    {
        //remove any thing not number
        $phone = preg_replace("/[^0-9]/", "", $phone);
        //remove 1 from the start
        $phone = ltrim($phone, 1);
        //check if new number is more than or equal 10 digits
        //then formate the phone
        if (strlen($phone) >= 10) {
            $phone = "(" . substr($phone, 0, 3) . ") " . substr($phone, 3, 3) 
            . "-" . substr($phone, 6);
        }
        return $phone;
    }

}
?>