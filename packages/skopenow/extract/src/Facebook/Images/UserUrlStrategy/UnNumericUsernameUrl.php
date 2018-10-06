<?php

/**
 * UnNumericUsernameUrl file
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

use Skopenow\Extract\Facebook\Images\UserUrlStrategy\UserUrlInterface;

/**
 * UnNumericUsernameUrl class
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

class UnNumericUsernameUrl implements UserUrlInterface
{
    
    /**
     * $_username
     *
     * @var string
     */
    private $_username;
    
    /**
     * Constructor
     *
     * @param type $username
     */
    public function __construct($username)
    {
        $this->_username = $username;
    }
    
    /**
     * getProfileUrl
     *
     * @return string
     */
    public function getProfileUrl() : string
    {
        return 'https://www.facebook.com/'.$this->_username;
    }
    
    /**
     * getPhotosUrl
     *
     * @return string
     */
    public function getPhotosUrl() : string
    {
        return 'https://www.facebook.com/'.$this->_username."/photos";
    }
    
    /**
     * getAlbumUrl
     *
     * @return string
     */
    public function getAlbumUrl() : string
    {
        return 'https://www.facebook.com/'.$this->_username."/photos_albums" ;
    }
}