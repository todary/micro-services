<?php

/**
 * NameSplitterParserInterface
 *
 * PHP version 7
 *
 * @package   NameSplitterParserInterface
 * @author    Queen tech <info@queentechsolutions.net>
 * @copyright 2017-2018 Queen tech
 * @access    public
 * @license   http://www.queentechsolutions.net/ BSD Licence
 * @version   CVS: 1.0.0
 * @link      http://www.queentechsolutions.net/
 */

namespace Skopenow\NameInfo\NameSplitter\Parser;

use Skopenow\NameInfo\NameSplitter\Iterator\NameSplitterIterator;
use Skopenow\NameInfo\NameSplitter\Commands\NameSplitterCommandList;

/**
 * NameSplitterParserInterface
 *
 *
 * @package   NameSplitter
 * @author    Queen tech <info@queentechsolutions.net>
 * @copyright 2017-2018 Queen tech
 * @access    public
 * @license   http://www.queentechsolutions.net/ BSD Licence
 * @version   CVS: 1.0.0
 * @link      http://www.queentechsolutions.net/
 */

interface NameSplitterParserInterface
{

     /**
     * Constructor
     * 
     * @param array $names
     */
     public function __construct(\Iterator $names, NameSplitterCommandList $commandList, NameSplitterIterator $iterator);

     /**
     * process
     *
     * processes the names
     * 
     * @return iterator names
     */
     public function process() : \Iterator;
}