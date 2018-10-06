<?php

/**
 * UniqueNameIterator
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

namespace Skopenow\NameInfo\UniqueName\Iterator;

use Skopenow\NameInfo\UniqueName\Iterator\UniqueNameIteratorInterface;

/**
 * UniqueNameIterator Class
 *
 *
 * @package   NameInfo
 * @author    Queen tech <info@queentechsolutions.net>
 * @copyright 2017-2018 Queen tech
 * @access    public
 * @license   http://www.queentechsolutions.net/ BSD Licence
 * @version   CVS: 1.0.0
 * @link      http://www.queentechsolutions.net/
 */

class UniqueNameIterator implements UniqueNameIteratorInterface
{

    /**
    * $uniqueNames
    *
    *
    * @var array
    */
    protected $uniqueNames = [];

    /**
    * addUniqueName
    *
    *
    * @access public
    * @param string $uniqueName
    * @return void
    */
    public function addUniqueName(array $uniqueName)
    {
        if(!in_array($uniqueName, $this->uniqueNames))
            array_push($this->uniqueNames, $uniqueName);
    }

    /**
    * getIterator
    *
    *
    * @access public
    * @return Iterator
    */
    public function getIterator() : \Iterator
    {
        return new \ArrayIterator($this->uniqueNames);
    }
}