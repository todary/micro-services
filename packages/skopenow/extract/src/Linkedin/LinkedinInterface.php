<?php

/**
 * LinkedinInterface file
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

namespace Skopenow\Extract\Linkedin;

use Skopenow\Extract\Linkedin\Iterator\LinkedinIteratorInterface;
use Skopenow\Extract\Linkedin\Extractor\ExtractorInterface;

/**
 * Linkedin Interface
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

interface LinkedinInterface
{
    
    /**
     * Constructor
     *
     * @access public
     * @param  LinkedinIteratorInterface $iterator
     *
     * @return Linkedin
     */
    public function __construct(LinkedinIteratorInterface $iterator);
    
    /**
     * Extract
     *
     * @access public
     * @param  ExtractorInterface
     * @return Linkedin
     */
    public function Extract(ExtractorInterface $extractor);
    
    /**
     * getResults
     *
     * @access public
     *
     * @return \Iterator
     */
    public function getResults(): \Iterator;
}