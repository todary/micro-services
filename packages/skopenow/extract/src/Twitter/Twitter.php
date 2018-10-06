<?php

/**
 * Twitter file
 *
 * PHP version 7
 *
 * @package   Twitter
 * @author    Queen tech <info@queentechsolutions.net>
 * @copyright 2017-2018 Queen tech
 * @access    public
 * @license   http://www.queentechsolutions.net/ BSD Licence
 * @version   CVS: 1.0.0
 * @link      http://www.queentechsolutions.net/
 */

namespace Skopenow\Extract\Twitter;

use Skopenow\Extract\Twitter\TwitterInterface;
use Skopenow\Extract\Twitter\Iterator\TwitterIteratorInterface;
use Skopenow\Extract\Twitter\Extractor\ExtractorInterface;

/**
 * Twitter Class
 *
 * PHP version 7
 *
 * @package   Twitter
 * @author    Queen tech <info@queentechsolutions.net>
 * @copyright 2017-2018 Queen tech
 * @access    public
 * @license   http://www.queentechsolutions.net/ BSD Licence
 * @version   CVS: 1.0.0
 * @link      http://www.queentechsolutions.net/
 */

class Twitter implements TwitterInterface
{
    
    /**
     * $_iterator
     *
     * @var iterator
     */
    private $_iterator;
    
     /**
      * Constructor
      *
      * @access public
      * @param  TwitterIteratorInterface $iterator
      *
      * @return Linkedin
      */
    public function __construct(TwitterIteratorInterface $iterator)
    {
        $this->_iterator = $iterator;
        return $this;
    }

    /**
     * Extract
     *
     * @access public
     * @param  ExtractorInterface
     * @return Twitter
     */
    public function Extract(ExtractorInterface $extractor) 
    {
        $data = $extractor->Process();
        $this->_iterator->setResults($data);
        return $this;
    }
    
    /**
     * getResults
     *
     * @access public
     *
     * @return Iterator
     */
    public function getResults(): \Iterator 
    {
        return $this->_iterator->getIterator();
    }
}