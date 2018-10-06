<?php

/**
 * InstagramIterator Interface
 *
 * PHP version 7
 *
 * @package   Instagram
 * @author    Queen tech <info@queentechsolutions.net>
 * @copyright 2017-2018 Queen tech
 * @access    public
 * @license   http://www.queentechsolutions.net/ BSD Licence
 * @version   CVS: 1.0.0
 * @link      http://www.queentechsolutions.net/
 */

namespace Skopenow\Extract\Instagram\Iterator;

/**
 * InstagramIteratorInterface
 *
 * @package   Instagram
 * @author    Queen tech <info@queentechsolutions.net>
 * @copyright 2017-2018 Queen tech
 * @access    public
 * @license   http://www.queentechsolutions.net/ BSD Licence
 * @version   CVS: 1.0.0
 * @link      http://www.queentechsolutions.net/
 */

interface InstagramIteratorInterface extends \IteratorAggregate
{
    
    /**
     * addResult
     *
     * @access public
     * @param  array $result
     * @return void
     */
    public function addResult(array $result);
}