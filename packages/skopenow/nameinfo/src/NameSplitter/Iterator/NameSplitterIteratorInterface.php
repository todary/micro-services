<?php

/**
 * NameSplitterIterator Interface
 *
 * PHP version 7
 *
 * @package   NameSplitterIterator
 * @author    Queen tech <info@queentechsolutions.net>
 * @copyright 2017-2018 Queen tech
 * @access    public
 * @license   http://www.queentechsolutions.net/ BSD Licence
 * @version   CVS: 1.0.0
 * @link      http://www.queentechsolutions.net/
 */

namespace Skopenow\NameInfo\NameSplitter\Iterator;

/**
 * NameSplitterIterator Interface
 *
 *
 * @package   NameSplitterIterator
 * @author    Queen tech <info@queentechsolutions.net>
 * @copyright 2017-2018 Queen tech
 * @access    public
 * @license   http://www.queentechsolutions.net/ BSD Licence
 * @version   CVS: 1.0.0
 * @link      http://www.queentechsolutions.net/
 */

interface NameSplitterIteratorInterface extends \IteratorAggregate  
{

    /**
    * addName
    *
    *
    * @access public
    * @param array $name
    * @return void
    */
    public function addName(array $name);
}