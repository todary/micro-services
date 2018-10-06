<?php

/**
 * NameSplitterParserTest
 * 
 * @package Test
 * @access public
 * @copyright 2017-2018 Queen tech
 * @author Queen tech <info@queentechsolutions.net>
 * @version 1.0.0
 */

namespace Skopenow\NameInfoTest;

use Skopenow\NameInfo\NameSplitter\NameSplitter;
use Skopenow\NameInfo\NameSplitter\Parser\NameSplitterParser;
use Skopenow\NameInfo\NameSplitter\Commands\NameSplitterCommandList;
use Skopenow\NameInfo\NameSplitter\Iterator\NameSplitterIterator;

class NameSplitterParserTest extends \PHPUnit\Framework\TestCase
{
    public function testProcess()
    {
        $namesIterator = new \ArrayIterator([
            "Rob Douglas jr"
        ]);

        $commandList = new NameSplitterCommandList();
        $nameSplitterIterator = new NameSplitterIterator();


        $nameSplitterParser = new NameSplitterParser($namesIterator, $commandList, $nameSplitterIterator);
        $input = array(
        'input' => 'Rob Douglas jr',
            'splitted' => array
    (
        0 => array
        (
            'firstName' => "rob",
            'middleName' => "",
            'lastName' => "douglas"
        )

    )
        );
        $expected = new \ArrayIterator([$input]);

        $this->assertEquals($expected, $nameSplitterParser->process());
    }
}