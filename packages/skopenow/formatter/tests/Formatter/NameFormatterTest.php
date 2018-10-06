<?php

/**
 * NameFormatterTest Class to format array of full names
 *
 * @category Test Class
 * @package  MyPackage
 * @author   Queen tech <info@queentechsolutions.net>
 * @license  license http://www.queentechsolutions.net/
 * @link     http://www.queentechsolutions.net/
 */

use Skopenow\Formatter\Classes\NameFormatter;

/**
 * NameFormatter Class to format array of full names
 *
 * @category Test Class
 * @package  MyPackage
 * @author   Queen tech <info@queentechsolutions.net>
 * @license  license http://www.queentechsolutions.net/
 * @link     http://www.queentechsolutions.net/
 */
class NameFormatterTest extends TestCase
{
   /**
    * [testNameHasSpaces description]
    * @return [void] [description]
    */
   public function testNameHasSpaces()
   {
   		$names = new \ArrayIterator();
        $names->append("   some one<br> Name     ");

        $nameFormatter = new NameFormatter($names);
        $formatName = $nameFormatter->format();

        $expected = new \ArrayIterator;
        $expectedOutput=[
            "original"=>"   some one<br> Name     ",
            "formatted"=>"Some One Name"
            ];
        $expected->append($expectedOutput);
        $this->assertEquals($expected,$formatName);
   }

   /**
    * [testNameHasHtmlTags description]
    * 
    * @return [void] [description]
    */
   public function testNameHasHtmlTags()
   {
   		$names = new \ArrayIterator();
   		$names->append("<h3>Some one<br> Name</h3>");

   		$nameFormatter = new NameFormatter($names);
   		$formatName = $nameFormatter->format();

        $expected = new \ArrayIterator;
        $expectedOutput=[
            "original"=>"<h3>Some one<br> Name</h3>",
            "formatted"=>"Some One Name"
            ];
        $expected->append($expectedOutput);
   		$this->assertEquals($expected,$formatName);
   }

   /**
    * [testNamehasSpaceCode description]
    * 
    * @return [Void] [description]
    */
   public function testNamehasSpaceCode()
   {
		$names = new \ArrayIterator();
   		$names->append("Some&nbsp;one&nbsp;Name");

   		$nameFormatter = new NameFormatter($names);
   		$formatName = $nameFormatter->format();

        $expected = new \ArrayIterator;
        $expectedOutput=[
            "original"=>"Some&nbsp;one&nbsp;Name",
            "formatted"=>"Some One Name"
            ];
        $expected->append($expectedOutput);
   		$this->assertEquals($expected,$formatName);
   }
   
}
