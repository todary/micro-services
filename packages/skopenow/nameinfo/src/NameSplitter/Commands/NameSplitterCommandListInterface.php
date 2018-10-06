<?php

/**
 * NameSplitterCommandListInterface
 *
 * PHP version 7
 *
 * @package   NameSplitterCommandListInterface
 * @author    Queen tech <info@queentechsolutions.net>
 * @copyright 2017-2018 Queen tech
 * @access    public
 * @license   http://www.queentechsolutions.net/ BSD Licence
 * @version   CVS: 1.0.0
 * @link      http://www.queentechsolutions.net/
 */

namespace Skopenow\NameInfo\NameSplitter\Commands;

use Skopenow\NameInfo\NameSplitter\Commands\NameSplitterCommandInterface;
use Skopenow\NameInfo\NameSplitter\NameSplitterInterface;

/**
 * NameSplitterCommandListInterface
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

interface NameSplitterCommandListInterface
{
    /**
     * add Command
     * 
     * @access public
     * @param NameSplitterCommandInterface $command
     * @return void
     */
    public function addCommand(NameSplitterCommandInterface $command, NameSplitterInterface $nameSplitter);
    
    /**
     * get Total Count
     * 
     *
     * @access public
     * @return int $count
     */
    public function getTotalCount() : int;

    /**
     * get List
     * 
     * 
     * @access public
     * @return array $commands
     */
    public function getList() : array;

    /**
     * run
     * 
     * 
     * @access public
     * @return void
     */
    public function run();
}