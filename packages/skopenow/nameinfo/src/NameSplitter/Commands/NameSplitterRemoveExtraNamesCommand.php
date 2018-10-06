<?php

/**
 * NameSplitterRemoveExtraNamesCommand
 *
 * PHP version 7
 *
 * @package   NameSplitterRemoveExtraNamesCommand
 * @author    Queen tech <info@queentechsolutions.net>
 * @copyright 2017-2018 Queen tech
 * @access    public
 * @license   http://www.queentechsolutions.net/ BSD Licence
 * @version   CVS: 1.0.0
 * @link      http://www.queentechsolutions.net/
 */

namespace Skopenow\NameInfo\NameSplitter\Commands;

use Skopenow\NameInfo\NameSplitter\NameSplitterInterface;
use Skopenow\NameInfo\NameSplitter\Commands\NameSplitterCommandInterface;

/**
 * NameSplitterRemoveExtraNamesCommand
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

class NameSplitterRemoveExtraNamesCommand implements NameSplitterCommandInterface
{
    
    /**
     * execute
     * 
     * 
     * @access public
     * @param NameSplitterInterface $nameSplitter
     * @return void
     */
    public function execute(NameSplitterInterface $nameSplitter)
    {
        $nameSplitter->removeExtraNames();
    }
}