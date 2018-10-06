<?php

/**
 * PostIterator file 
 *
 * PHP version 7
 *
 * @package   Facebook Posts
 * @author    Queen tech <info@queentechsolutions.net>
 * @copyright 2017-2018 Queen tech
 * @access    public
 * @license   http://www.queentechsolutions.net/ BSD Licence
 * @version   CVS: 1.0.0
 * @link      http://www.queentechsolutions.net/
 */

namespace Skopenow\Extract\Facebook\Posts\Iterator;

use Skopenow\Extract\Facebook\Posts\Iterator\PostIteratorInterface;

/**
 * PostIterator class
 *
 * @package   Facebook Posts
 * @author    Queen tech <info@queentechsolutions.net>
 * @copyright 2017-2018 Queen tech
 * @access    public
 * @license   http://www.queentechsolutions.net/ BSD Licence
 * @version   CVS: 1.0.0
 * @link      http://www.queentechsolutions.net/
 */

class PostIterator implements PostIteratorInterface
{

    /**
     * $results
     *
     * @var array
     */
    protected $results = [];

    /**
     * setResults
     *
     * @access public
     * @param  array $result
     * @return void
     */
    public function setResults(array $result)
    {
        $this->results = $result;
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