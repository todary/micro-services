<?php

/**
 * NameFormatter Class to format array of full names
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
 * NameFormatter Class to format array of full names
 *
 * @category Class
 * @package  MyPackage
 * @author   Queen tech <info@queentechsolutions.net>
 * @license  license http://www.queentechsolutions.net/
 * @link     http://www.queentechsolutions.net/
 */
class NameFormatter extends FormatterAbstract implements FormatterInterface
{
    /**
     * [formatSingle description]
     * 
     * @param  string $name [description]
     * @return [string]       [description]
     */
    protected function formatSingle($fullName)
    {
        $fullName = htmlspecialchars_decode(
            trim(
                html_entity_decode(
                    strip_tags(
                        str_replace("&nbsp;", " ", $fullName)
                    )
                )
            )
        );
        $fullName = ucwords($fullName);
        return $fullName;
    }
}
