<?php

/**
 * ImageInterface file
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

namespace Skopenow\Extract\Facebook\Images;

use Skopenow\Extract\Facebook\Images\Iterator\ImageIteratorInterface;

/**
 * Image Interface
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

interface ImageInterface
{
     /**
      * Constructor
      *
      * @access public
      * @param  string                 $link
      * @param  ImageIteratorInterface $iterator
      * 
      * @return ImageInterface
      */
    public function __construct(string $link, ImageIteratorInterface $iterator);
    
    /**
     * setSessId
     *
     * @access public
     * @param  string $sessId
     *
     * @return PostInterface
     */
    public function setSessId(string $sessId) : ImageInterface;
    
    /**
     * Extract
     *
     * @access public
     * 
     * @return mixed
     */
    public function Extract();
    
    /**
     * getResults
     *
     * @access public
     *
     * @return \Iterator
     */
    public function getResults(): \Iterator;
}
