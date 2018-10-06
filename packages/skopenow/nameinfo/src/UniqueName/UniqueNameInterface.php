<?php

/**
 * Handles unique name operations
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

namespace Skopenow\NameInfo\UniqueName;

/**
 * UniqueName Interface
 *
 * Skelton interface along with the main functions
 * for the unique name interface.
 *
 * @package   UniqueName
 * @author    Queen tech <info@queentechsolutions.net>
 * @copyright 2017-2018 Queen tech
 * @access    public
 * @license   http://www.queentechsolutions.net/ BSD Licence
 * @version   CVS: 1.0.0
 * @link      http://www.queentechsolutions.net/
 */

interface UniqueNameInterface
{

    /**
    * Constructor
    *
    * 
    * @access public
    * @param array $names
    * @param bool $isRelative
    * @param string $apiKey
    * @return void
    */
    public function __construct(\Iterator $names, bool $isRelative = false, string $apiKey = '');

    /**
    * checkUniqueName
    *
    * 
    * @access public
    * @return \Iterators
    */
    public function checkUniqueName() : \Iterator;
}