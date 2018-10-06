<?php

/**
 * Addess Formatter Class to format array of Addesses
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
 * Addess Formatter Class to format array of Addesses
 *
 * @category Class
 * @package  MyPackage
 * @author   Queen tech <info@queentechsolutions.net>
 * @license  license http://www.queentechsolutions.net/
 * @link     http://www.queentechsolutions.net/
 */
class AddressFormatter extends FormatterAbstract implements FormatterInterface
{
    /**
     * [formatSingle description]
     *
     * @param  \ArrayIterator $address [description]
     * @return [string] [full address]
     */
    protected function formatSingle($address)
    {
        //repalce lane with ln in add
        $address['full_address'] = str_ireplace('lane', 'ln', $address['full_address']);

        if (strtolower($address['full_address']) == "nyc") {
            $address['full_address'] = "New York, NY";
        }
        //cehck if zip not found make it with empty string
        if (!array_key_exists('zip', $address)) {
            $address['zip'] = '';
        }

        //set full address with the found data
        $fullAddress = $address['full_address'];

        /*
        //cehck if adress proberties not found set fullname with add
        if (stripos($address['full_address'], trim($address['city'])) !== false
            || stripos($address['full_address'], trim($address['zip']))
        ) {
            $fullAddress=$address['full_address'];
        }
        */

        //trim , from full address
        $fullAddress = trim($fullAddress, ',');

        return $fullAddress;
    }

}

?>
