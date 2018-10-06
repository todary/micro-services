<?php

/**
 * Handles searching through pipl and howmanyofme services
 *
 * PHP version 7
 *
 * @package   UniqueName
 * @author    Queen tech <info@queentechsolutions.net>
 * @copyright 2017-2018 Queen tech
 * @access    public
 * @license   http://www.queentechsolutions.net/ BSD Licence
 * @version   CVS: 1.0.0
 * @link      http://www.queentechsolutions.net/
 */

namespace Skopenow\NameInfo\UniqueName\Search;

/**
 * PiplInterface Interface
 *
 *
 * @package   UniqueName
 * @author    Queen tech <info@queentechsolutions.net>
 * @copyright 2017-2018 Queen tech
 * @access    public
 * @license   http://www.queentechsolutions.net/ BSD Licence
 * @version   CVS: 1.0.0
 * @link      http://www.queentechsolutions.net/
 */

interface UniqueNameSearchInterface
{

    /** Constructor
    *
    * 
    * @access public
    * @param string $firstName
    * @param string $lastName
    * @param string $apiKey
    * @return void
    */
    public function __construct(string $firstName, string $lastName, string $apiKey = '');

    /**
    * search
    *
    * 
    * @access public
    * @return mixed
    */
    public function search();
}