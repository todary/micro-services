<?php

/**
 * NameSplitterCommandList
 *
 * PHP version 7
 *
 * @package   NameSplitterCommandList
 * @author    Queen tech <info@queentechsolutions.net>
 * @copyright 2017-2018 Queen tech
 * @access    public
 * @license   http://www.queentechsolutions.net/ BSD Licence
 * @version   CVS: 1.0.0
 * @link      http://www.queentechsolutions.net/
 */

namespace Skopenow\NameInfo\NameSplitter\Commands;

use Skopenow\NameInfo\NameSplitter\Commands\NameSplitterCommandInterface;
use Skopenow\NameInfo\NameSplitter\Commands\NameSplitterCommandListInterface;
use Skopenow\NameInfo\NameSplitter\NameSplitterInterface;
/**
 * NameSplitterCommandList
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

class NameSplitterCommandList implements NameSplitterCommandListInterface
{

    /**
    * $commands
    *
    *
    * @var array
    */
    private $commands = [];

    /**
    * $nameSplitter
    *
    *
    * @var NameSplitterInterface
    */
    private $nameSplitter;


    /**
     * add Command
     * 
     * @access public
     * @param NameSplitterCommandInterface $command
     * @return void
     */
    public function addCommand(NameSplitterCommandInterface $command, NameSplitterInterface $nameSplitter)
    {
        array_push($this->commands, $command);
        $this->nameSplitter = $nameSplitter;
    }
    
    /**
     * get Total Count
     * 
     *
     * @access public
     * @return int $count
     */
    public function getTotalCount() : int
    {
        return count($this->commands);
    }

    /**
     * get List
     * 
     * 
     * @access public
     * @return array $commands
     */
    public function getList() : array
    {
        return $this->commands;
    }

    /**
     * run
     * 
     * 
     * @access public
     * @return array $commands
     */
    public function run()
    {
        foreach ($this->commands as $command) {
            $command->execute($this->nameSplitter);
        }
    }
}