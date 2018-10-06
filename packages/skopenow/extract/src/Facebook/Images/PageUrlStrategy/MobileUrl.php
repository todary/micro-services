<?php

/**
 * MobileUrl file
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

namespace Skopenow\Extract\Facebook\Images\PageUrlStrategy;

use Skopenow\Extract\Facebook\Images\PageUrlStrategy\PageUrlInterface;

/**
 * MobileUrl class
 *
 * @package   Facebook Images
 * @author    Queen tech <info@queentechsolutions.net>
 * @copyright 2017-2018 Queen tech
 * @access    public
 * @license   http://www.queentechsolutions.net/ BSD Licence
 * @version   CVS: 1.0.0
 * @link      http://www.queentechsolutions.net/
 */

class MobileUrl implements PageUrlInterface
{
    
    /**
     * $_link
     *
     * @var string
     */
    private $_link;
    
    /**
     * Constructor
     *
     * @param type $link
     */
    public function __construct($link)
    {
        $this->_link = $link;
    }
    
    /**
     * getUrl
     *
     * @return string
     */
    public function getUrl() : string
    {
        return str_replace("facebook", "m.facebook", $this->_link);
    }
}