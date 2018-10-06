<?php

/**
 * NameSplitterCommandInterface
 *
 * PHP version 7
 *
 * @package   NameSplitterCommandInterface
 * @author    Queen tech <info@queentechsolutions.net>
 * @copyright 2017-2018 Queen tech
 * @access    public
 * @license   http://www.queentechsolutions.net/ BSD Licence
 * @version   CVS: 1.0.0
 * @link      http://www.queentechsolutions.net/
 */

namespace Skopenow\NameInfo\NameSplitter\Commands;

use Skopenow\NameInfo\NameSplitter\NameSplitterInterface;

/**
 * NameSplitterCommandInterface
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

interface NameSplitterCommandInterface
{

    /**
     * execute
     * 
     * 
     * @access public
     * @param NameSplitterInterface $nameSplitter
     * @return void
     */
    public function execute(NameSplitterInterface $nameSplitter);
}