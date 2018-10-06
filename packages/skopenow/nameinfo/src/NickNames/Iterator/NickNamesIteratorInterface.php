<?php

/**
 * NickNamesIterator Interface
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

namespace Skopenow\NameInfo\NickNames\Iterator;

/**
 * NickNamesIterator Interface
 *
 *
 * @package   NickNames
 * @author    Queen tech <info@queentechsolutions.net>
 * @copyright 2017-2018 Queen tech
 * @access    public
 * @license   http://www.queentechsolutions.net/ BSD Licence
 * @version   CVS: 1.0.0
 * @link      http://www.queentechsolutions.net/
 */

interface NickNamesIteratorInterface extends \IteratorAggregate  
{

    /**
    * addNickName
    *
    *
    * @access public
    * @param string $nickName
    * @return void
    */
    public function addNickName(array $nickName);
}