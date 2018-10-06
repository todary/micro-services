<?php

/**
 * Linkedin file
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

use Skopenow\Extract\Linkedin\LinkedinInterface;
use Skopenow\Extract\Linkedin\Iterator\LinkedinIteratorInterface;
use Skopenow\Extract\Linkedin\Extractor\ExtractorInterface;

/**
 * Linkedin class
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

class Linkedin implements LinkedinInterface
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
     * @param  InstagramIteratorInterface $iterator
     *
     * @return LinkedinInterface
     */
    public function __construct(LinkedinIteratorInterface $iterator)
    {
        $this->_iterator = $iterator;
        return $this;
    }

    /**
     * Extract
     *
     * @access public
     * @param  ExtractorInterface
     * @return Linkedin
     */
    public function Extract(ExtractorInterface $extractor) 
    {
        $data = $extractor->Process();
        foreach($data as $skill) {
            $this->_iterator->addResult($skill);
        }
        
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