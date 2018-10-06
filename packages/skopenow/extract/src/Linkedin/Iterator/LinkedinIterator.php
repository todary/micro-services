<?php

/**
 * LinkedinIterator Interface
 *
 * PHP version 7
 *
 * @package   Linkedin
 * @author    Queen tech <info@queentechsolutions.net>
 * @copyright 2017-2018 Queen tech
 * @access    public
 * @license   http://www.queentechsolutions.net/ BSD Licence
 * @version   CVS: 1.0.0
 * @link      http://www.queentechsolutions.net/
 */

namespace Skopenow\Extract\Linkedin\Iterator;

use Skopenow\Extract\Linkedin\Iterator\LinkedinIteratorInterface;

/**
 * LinkedinIteratorInterface
 *
 * @package   Linkedin
 * @author    Queen tech <info@queentechsolutions.net>
 * @copyright 2017-2018 Queen tech
 * @access    public
 * @license   http://www.queentechsolutions.net/ BSD Licence
 * @version   CVS: 1.0.0
 * @link      http://www.queentechsolutions.net/
 */

class LinkedinIterator implements LinkedinIteratorInterface
{

    /**
     * $results
     *
     * @var array
     */
    protected $results = [];

    /**
     * addResult
     *
     * @access public
     * @param  array $result
     * @return void
     */
    public function addResult(array $result)
    {
        array_push($this->results, $result);
    }

    /**
     * getIterator
     *
     * @access public
     * @return Iterator
     */
    public function getIterator() : \Iterator
    {
        return new \ArrayIterator($this->results);
    }
}