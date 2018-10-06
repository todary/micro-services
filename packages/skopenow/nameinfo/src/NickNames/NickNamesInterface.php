<?php

/**
 * Handles nicknames operations
 *
 * PHP version 7
 *
 * @package   NickNames
 * @author    Queen tech <info@queentechsolutions.net>
 * @copyright 2017-2018 Queen tech
 * @access    public
 * @license   http://www.queentechsolutions.net/ BSD Licence
 * @version   CVS: 1.0.0
 * @link      http://www.queentechsolutions.net/
 */

namespace Skopenow\NameInfo\NickNames;

use Skopenow\NameInfo\NickNames\Iterator\NickNamesIteratorInterface;

/**
 * NickNames Interface
 *
 * Skelton interface along with the main functions
 * for the nicknames interface.
 *
 * @package   NameInfo
 * @author    Queen tech <info@queentechsolutions.net>
 * @copyright 2017-2018 Queen tech
 * @access    public
 * @license   http://www.queentechsolutions.net/ BSD Licence
 * @version   CVS: 1.0.0
 * @link      http://www.queentechsolutions.net/
 */

interface NickNamesInterface
{

    /**
    * Constructor
    *
    *
    * @access public
    * @param Iterator $names
    */
    public function __construct(\Iterator $names, NickNamesIteratorInterface $nickNamesiterator);

    /**
    * searchNickNames
    *
    * search nicknames
    * 
    * @access public
    * @return void 
    */
    public function search();

    /**
    * getNickNames
    *
    * get nicknames
    * 
    * @access public
    * @return iterator 
    */
    public function getNickNames() : \Iterator;
}