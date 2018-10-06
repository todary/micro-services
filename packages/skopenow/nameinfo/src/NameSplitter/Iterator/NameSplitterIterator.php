<?php

/**
 * NameSplitterIterator
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

use Skopenow\NameInfo\NameSplitter\Iterator\NameSplitterIteratorInterface;

/**
 * NameSplitterIterator Class
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

class NameSplitterIterator implements NameSplitterIteratorInterface
{

    /**
    * $names
    *
    *
    * @var array
    */
    protected $names = [];

    /**
    * addName
    *
    *
    * @access public
    * @param array $name
    * @return void
    */
    public function addName(array $name)
    {
        if(!in_array($name, $this->names))
            array_push($this->names, $name);
    }

    /**
    * getIterator
    *
    *
    * @access public
    * @return ArrayIterator
    */
    public function getIterator() : \ArrayIterator
    {
        return new \ArrayIterator($this->names);
    }
}