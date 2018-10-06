<?php

/**
 * UserUrlInterface file
 *
 * PHP version 7
 *
 * @package   Facebook Images
 * @author    Queen tech <info@queentechsolutions.net>
 * @copyright 2017-2018 Queen tech
 * @access    public
 * @license   http://www.queentechsolutions.net/ BSD Licence
 * @version   CVS: 1.0.0
 * @link      http://www.queentechsolutions.net/
 */

namespace Skopenow\Extract\Facebook\Images\UserUrlStrategy;

/**
 * UserUrlInterface class
 *
 * PHP version 7
 *
 * @package   Facebook Images
 * @author    Queen tech <info@queentechsolutions.net>
 * @copyright 2017-2018 Queen tech
 * @access    public
 * @license   http://www.queentechsolutions.net/ BSD Licence
 * @version   CVS: 1.0.0
 * @link      http://www.queentechsolutions.net/
 */

interface UserUrlInterface
{
    
    /**
     * Constructor
     *
     * @param type $username
     */
    public function __construct($username);
    
    /**
     * getProfileUrl
     *
     * @return string
     */
    public function getProfileUrl() : string;
    
    /**
     * getPhotosUrl
     *
     * @return string
     */
    public function getPhotosUrl() : string;
    
    /**
     * getAlbumUrl
     *
     * @return string
     */
    public function getAlbumUrl() : string;
}