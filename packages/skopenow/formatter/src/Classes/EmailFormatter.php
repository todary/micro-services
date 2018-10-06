<?php

/**
 * EmailFormatter Class to format array of Emails
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
 * EmailFormatter Class to format array of Emails
 *
 * @category Class
 * @package  MyPackage
 * @author   Queen tech <info@queentechsolutions.net>
 * @license  license http://www.queentechsolutions.net/
 * @link     http://www.queentechsolutions.net/
 */
class EmailFormatter extends FormatterAbstract implements FormatterInterface
{
    /**
     * [formatSingle description]
     * 
     * @param  string $email [description]
     * @return [string]        [description]
     */
    protected function formatSingle($email)
    {
        $email = trim(strtolower($email));
        return $email;
    }

}

?>