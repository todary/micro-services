<?php

/**
 * This is the NickNamesTest class test
 * 
 * @package Test
 * @access public
 * @copyright 2017-2018 Queen tech
 * @author Queen tech <info@queentechsolutions.net>
 * @version 1.0.0
 */

namespace Skopenow\NameInfoTest;

use Skopenow\NameInfo\NickNames\NickNames;
use Skopenow\NameInfo\NickNames\Iterator\NickNamesIterator;

class NickNamesTest extends \PHPUnit\Framework\TestCase
{
    public function  testSearchDynamoDB()
    {
        
//        $data = new \ArrayIterator([
//        "rob"
//    ]);
//        $nickNamesIterator = new NickNamesIterator();
//
//
//        $nickNames = new NickNames($data, $nickNamesIterator);
//        $nickNames->search();
        
        $nickNamesIterator = new NickNamesIterator();
        $input = new \ArrayIterator(["rob"]);
        $Mock = $this->getMockBuilder(NickNames::class)
             ->setConstructorArgs([$input, $nickNamesIterator])
             ->setMethods(array('fetchData'))
             ->getMock();
        
        $expected = new \ArrayIterator([
            
            [
                 "input" => "rob",
                 "nickNames" => [
                     0 => "bob",
  1 => "bobby",
  2 => "dob",
  3 => "dobbin",
  4 => "rob",
  5 => "robert",
  6 => "roberta",
  7 => "robin",
  8 => "rupert"
                 ]
            ]
  
]);
        
        $Mock->method('fetchData')
             ->will($this->returnValue($expected));
        
        $Mock->search();
        $this->assertEquals($expected, $Mock->getNickNames());
        
    }

    public function testGetNames()
    {
        $names = new \ArrayIterator([
            "Rob Douglas",
            "Kazi Anwarul Mamun",
            "Mohnish Magarde",
            "David Will"
        ]);

        $nickNamesIterator = new NickNamesIterator();


        $nickNames = new NickNames($names, $nickNamesIterator);
        $this->assertEquals($names, $nickNames->getNames());
    }
}