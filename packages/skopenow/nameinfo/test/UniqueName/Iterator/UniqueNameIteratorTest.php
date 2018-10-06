<?php

/**
 * This is the UniqueNameIteratorTest class test
 * 
 * @package Test
 * @access public
 * @copyright 2017-2018 Queen tech
 * @author Queen tech <info@queentechsolutions.net>
 * @version 1.0.0
 */

namespace Skopenow\NameInfoTest;

use Skopenow\NameInfo\UniqueName\Iterator\UniqueNameIteratorInterface;
use Skopenow\NameInfo\UniqueName\Iterator\UniqueNameIterator;

class UniqueNameIteratorTest extends \PHPUnit\Framework\TestCase
{
	public function testAddUniqueName()
	{
		$iterator = new UniqueNameIterator();

		$uniqueNamesMock = $this
        ->getMockBuilder('\NameInfo\UniqueName\Iterator\UniqueNameIterator')
        ->setMethods(array('addUniqueName'))
        ->disableOriginalConstructor()
        ->getMock();

		$uniqueNamesMock->expects($this->once())
        ->method('addUniqueName')
        ->will($this->returnValue(true));

        $uniqueNamesMock->addUniqueName(["wael", "rob", "elgebaly"]);
	}

	public function testGetIterator()
	{
		$iterator = new UniqueNameIterator();
		$iterator->addUniqueName(["wael", "rob", "elgebaly"]);
		$iterator->addUniqueName(["yasser", "adham", "waleed"]);

		$expected = new \ArrayIterator(array(
                   ["wael", "rob", "elgebaly"],
                   ["yasser", "adham", "waleed"]
 				));

		$this->assertEquals($expected, $iterator->getIterator());

	}	 
}