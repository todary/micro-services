<?php

/**
 * InstagramInterface file
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

namespace Skopenow\Extract\Instagram;
use Skopenow\Extract\Instagram\Iterator\InstagramIteratorInterface;

/**
 * Instagram Interface
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

interface InstagramInterface
{
    
    /**
     * Constructor
     *
     * @access public
     * @param  string                     $link
     * @param  InstagramIteratorInterface $iterator
     *
     * @return PostInterface
     */
    public function __construct(string $link, InstagramIteratorInterface $iterator);
    
    /**
     * setLimit
     *
     * @access public
     * @param  int $limit
     *
     * @return InstagramInterface
     */
    public function setLimit(int $limit): InstagramInterface;
    
    /**
     * setOldPhotos
     *
     * @access public
     * @param  array $oldPhotos
     *
     * @return InstagramInterface
     */
    public function setOldPhotos(array $oldPhotos): InstagramInterface;
        
    /**
     * setRequestOptions
     *
     * @access public
     * @param  iterator $requestOptions
     *
     * @return InstagramInterface
     */
    public function setRequestOptions(array $requestOptions) : InstagramInterface;
    
    /**
     * Extract
     *
     * @access public
     *
     * @return InstagramInterface 
     */
    public function Extract() : InstagramInterface;
    
    /**
     * getResults
     *
     * @access public
     *
     * @return \Iterator
     */
    public function getResults(): \Iterator;
}

