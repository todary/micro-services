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

/**
 * NameFormatter Class to format array of full names
 *
 * @category Class
 * @package  MyPackage
 * @author   Queen tech <info@queentechsolutions.net>
 * @license  license http://www.queentechsolutions.net/
 * @link     http://www.queentechsolutions.net/
 */
interface FormaterEntrypointInterface
{
    /**
    * [format Formate full name and set it in its attr]
    * 
    * @return \Iterator $inputs [description]
    */
    public function format(\Iterator $inputs);
}

?>