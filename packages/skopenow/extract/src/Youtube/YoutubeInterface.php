<?php

/**
 * YoutubeInterface file
 *
 * PHP version 7
 *
 * @package   Youtube
 * @author    Queen tech <info@queentechsolutions.net>
 * @copyright 2017-2018 Queen tech
 * @access    public
 * @license   http://www.queentechsolutions.net/ BSD Licence
 * @version   CVS: 1.0.0
 * @link      http://www.queentechsolutions.net/
 */

namespace Skopenow\Extract\Youtube;

use Skopenow\Extract\Youtube\Iterator\YoutubeIteratorInterface;

/**
 * Youtube Interface
 *
 * @package   Youtube
 * @author    Queen tech <info@queentechsolutions.net>
 * @copyright 2017-2018 Queen tech
 * @access    public
 * @license   http://www.queentechsolutions.net/ BSD Licence
 * @version   Release: 1.0.0
 * @link      http://www.queentechsolutions.net/
 */

interface YoutubeInterface
{
    
    /**
     * Constructor
     *
     * @access public
     * @param  string                   $profileUrl
     * @param  YoutubeIteratorInterface $iterator
     *
     * @return YoutubeInterface
     */
    public function __construct(string $profileUrl, YoutubeIteratorInterface $iterator);
    
    /**
     * Extract
     *
     * @access public
     * @param  string $type
     *
     * @return Youtube instance  
     */
    public function Extract(string $type = 'videos') : YoutubeInterface;
    
    /**
     * getResults
     *
     * @access public
     *
     * @return \Iterator
     */
    public function getResults(): \Iterator;
}

