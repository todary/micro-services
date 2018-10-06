<?php

/**
 * This is the NameSplitterCommandListTest class test
 * 
 * @package Test
 * @access public
 * @copyright 2017-2018 Queen tech
 * @author Queen tech <info@queentechsolutions.net>
 * @version 1.0.0
 */

namespace Skopenow\NameInfoTest;

use Skopenow\NameInfo\NameSplitter\Commands\NameSplitterCommandList;
use Skopenow\NameInfo\NameSplitter\Commands\NameSplitterCombinePartsCommand;
use Skopenow\NameInfo\NameSplitter\Commands\NameSplitterExtractNamePartsCommand;
use Skopenow\NameInfo\NameSplitter\Commands\NameSplitterRemoveExtraNamesCommand;
use Skopenow\NameInfo\NameSplitter\Commands\NameSplitterPrepareNameInputCommand;
use Skopenow\NameInfo\NameSplitter\Commands\NameSplitterHonorificNickNamesCommand;
use Skopenow\NameInfo\NameSplitter\NameSplitter;
use Skopenow\NameInfo\NameSplitter\Commands\NameSplitterCommandInterface;

class NameSplitterCommandListTest extends \PHPUnit\Framework\TestCase
{
    public function testAddCommand()
    {
        $nameSplitter = new NameSplitter("Rob Douglas");
        $commandList = new NameSplitterCommandList();
        $commandList->addCommand(new NameSplitterPrepareNameInputCommand(), $nameSplitter);
        $commandList->addCommand(new NameSplitterHonorificNickNamesCommand(), $nameSplitter);
        
        $this->assertEquals(2, $commandList->getTotalCount());
    }

    public function testGetTotalCount()
    {
        $nameSplitter = new NameSplitter("Rob Douglas");
        $commandList = new NameSplitterCommandList();
        $commandList->addCommand(new NameSplitterPrepareNameInputCommand(), $nameSplitter);
        $commandList->addCommand(new NameSplitterHonorificNickNamesCommand(), $nameSplitter);
        $commandList->addCommand(new NameSplitterExtractNamePartsCommand(), $nameSplitter);
        $commandList->addCommand(new NameSplitterRemoveExtraNamesCommand(), $nameSplitter);
        $this->assertEquals(4, $commandList->getTotalCount());
    }

    public function testGetList()
    {
        $nameSplitter = new NameSplitter("Rob Douglas");
        $commandList = new NameSplitterCommandList();
        $commandList->addCommand(new NameSplitterPrepareNameInputCommand(), $nameSplitter);

        $this->assertEquals(1, count($commandList->getList()));
    }

    public function testRun()
    {
        $nameSplitter = new NameSplitter("Rob Douglas rob@gmail.com");
        $commandList = new NameSplitterCommandList();
        $commandList->addCommand(new NameSplitterPrepareNameInputCommand(), $nameSplitter);
        $commandList->addCommand(new NameSplitterHonorificNickNamesCommand(), $nameSplitter);

        $commandList->run();

        $expected = "Rob Douglas";

        $this->assertEquals($expected, $nameSplitter->getProcessedName());
    }
}