<?php

/**
 * NameSplitterIteratorTest
 * 
 * @package Test
 * @access public
 * @copyright 2017-2018 Queen tech
 * @author Queen tech <info@queentechsolutions.net>
 * @version 1.0.0
 */

namespace Skopenow\NameInfoTest;

use Skopenow\NameInfo\NameSplitter\NameSplitter;
use Skopenow\NameInfo\NameSplitter\Iterator\NameSplitterIterator;

class NameSplitterIteratorTest extends \PHPUnit\Framework\TestCase
{
    public function testAddName()
    {
        $name = ['firstName' => "Rob", "middleName" => "", "lastName" => "Douglas"];
        $iterator = new NameSplitterIterator();
        $iterator->addName($name);

        $expected = new \ArrayIterator([$name]);

        $this->assertEquals($expected, $iterator->getIterator());
    }

    public function testGetIterator()
    {
        $iterator = new NameSplitterIterator();
        $expected = new \ArrayIterator([]);
        $this->assertEquals($expected, $iterator->getIterator());
    }
}