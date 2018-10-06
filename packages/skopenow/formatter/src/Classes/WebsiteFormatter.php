<?php

/**
 * WebsiteFormatter Class to format array of website
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
 * WebsiteFormatter Class to format array of website
 *
 * @category Class
 * @package  MyPackage
 * @author   Queen tech <info@queentechsolutions.net>
 * @license  license http://www.queentechsolutions.net/
 * @link     http://www.queentechsolutions.net/
 */
class WebsiteFormatter extends FormatterAbstract implements FormatterInterface
{
    /**
     * [formatSingle description]
     * 
     * @param  string $name [description]
     * @return [string]       [description]
     */
    protected function formatSingle($url)
    {
        if (strpos($url, "http://") === false 
            && strpos($url, "https://") === false 
            && filter_var("http://" .$url, FILTER_VALIDATE_URL) != false
        ) {
            $url = "http://" .$url;
        }

        return $url;
    }


}

?>