<?php

/**
 * ExtractorInterface file
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

namespace Skopenow\Extract\Twitter\Extractor;

/**
 * Extractor Interface
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

interface ExtractorInterface
{
    
    /**
     * Constructor
     *
     * @access public
     * @param  string $data
     */
    public function __construct(string $data);
    
    /**
     * Extract
     *
     * @access public
     * @return array
     */
    public function Process() : array;
}

