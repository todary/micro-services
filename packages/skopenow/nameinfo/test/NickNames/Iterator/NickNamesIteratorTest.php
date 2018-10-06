<?php

/**
 * This is the NickNamesIteratorTest class test
 * 
 * @package Test
 * @access public
 * @copyright 2017-2018 Queen tech
 * @author Queen tech <info@queentechsolutions.net>
 * @version 1.0.0
 */

namespace Skopenow\NameInfoTest;

use Skopenow\NameInfo\NickNames\Iterator\NickNamesIteratorInterface;
use Skopenow\NameInfo\NickNames\Iterator\NickNamesIterator;

class NickNamesIteratorTest extends \PHPUnit\Framework\TestCase
{
	public function testAddNickName()
	{
		$iterator = new NickNamesIterator();

		$nickNamesMock = $this
        ->getMockBuilder('\NameInfo\NickNames\Iterator\NickNamesIterator')
        ->setMethods(array('AddNickName'))
        ->disableOriginalConstructor()
        ->getMock();

		$nickNamesMock->expects($this->once())
        ->method('AddNickName')
        ->will($this->returnValue(true));

        $nickNamesMock->AddNickName(array(
                	"input" => "Rob Douglas",
                    "nickNames" => ["Rob", "Douglas", "RRR"]
                ));
	}

	public function testGetIterator()
	{
		$iterator = new NickNamesIterator();
		$iterator->addNickName(array(
                	"input" => "Rob Douglas",
                    "nickNames" => ["Rob", "Douglas", "RRR"]
                ));
		$iterator->addNickName(array(
                	"input" => "John Smith",
                    "nickNames" => ["john", "smith"]
                ));

		$expected = new \ArrayIterator([
                [
                	"input" => "Rob Douglas",
                    "nickNames" => ["Rob", "Douglas", "RRR"]
                ],
                [
                	"input" => "John Smith",
                    "nickNames" => ["john", "smith"]
                ]
]);

		$this->assertEquals($expected, $iterator->getIterator());

	}	 
}