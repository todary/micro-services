<?php

/**
 * PostInterface file
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

namespace Skopenow\Extract\Facebook\Posts;
use Skopenow\Extract\Facebook\Posts\Iterator\PostIteratorInterface;

/**
 * Post Interface
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

interface PostInterface
{
    
    /**
     * Constructor
     *
     * @access public
     * @param  string                $link
     * @param  PostIteratorInterface $postsIterator
     *
     * @return PostInterface
     */
    public function __construct(string $link, PostIteratorInterface $postsIterator);
    
    /**
     * setSessId
     *
     * @access public
     * @param  string $sessId
     *
     * @return PostInterface
     */
    public function setSessId(string $sessId) : PostInterface;
    
    /**
     * setRequestOptions
     *
     * @access public
     * @param  iterator $requestOptions
     *
     * @return PostInterface
     */
    public function setRequestOptions(array $requestOptions) : PostInterface;
    
    /**
     * Extract
     *
     * @access public
     * @return PostInterface
     */
    public function Extract() : PostInterface;
    
    /**
     * loopResults
     *
     * @access public
     *
     * @return Post
     */
    public function loopResults() : PostInterface;
    
    /**
     * getResults
     *
     * @access public
     *
     * @return \Iterator
     */
    public function getResults();
}

