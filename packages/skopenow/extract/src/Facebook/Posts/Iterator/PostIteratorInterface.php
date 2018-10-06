<?php

/**
 * PostIteratorInterface file
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

/**
 * PostIterator Interface
 *
 * @package   Facebook Posts
 * @author    Queen tech <info@queentechsolutions.net>
 * @copyright 2017-2018 Queen tech
 * @access    public
 * @license   http://www.queentechsolutions.net/ BSD Licence
 * @version   CVS: 1.0.0
 * @link      http://www.queentechsolutions.net/
 */

interface PostIteratorInterface extends \IteratorAggregate
{
    
    /**
     * setResults
     *
     * @access public
     * @param  array $result
     * @return void
     */
    public function setResults(array $result);
}