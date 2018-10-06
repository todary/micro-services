<?php

/**
 * NameFormatterTest Class to format array of full names
 *
 * @category Test Class EmailFormatterController
 * @package  MyPackage
 * @author   Queen tech <info@queentechsolutions.net>
 * @license  license http://www.queentechsolutions.net/
 * @link     http://www.queentechsolutions.net/
 */

use Skopenow\Formatter\Classes\EmailFormatter;

/**
 * NameFormatter Class to format array of full names
 *
 * @category Test Class EmailFormatterController
 * @package  MyPackage
 * @author   Queen tech <info@queentechsolutions.net>
 * @license  license http://www.queentechsolutions.net/
 * @link     http://www.queentechsolutions.net/
 */
class EmailFormatterTest extends TestCase
{
   /**
    * [testEmailHasUperCase description]
    * 
    * @return [type] [description]
    */
   public function testEmailHasUpperCase()
   {
   		$emails = new \ArrayIterator();
   		$emails->append("KHALED@GMAIL.COM");

   		$emailFormatter = new EmailFormatter($emails);
   		$formatEmail = $emailFormatter->format();

        $expected = new \ArrayIterator;
        $expectedOutput=[
            "original"=>"KHALED@GMAIL.COM",
            "formatted"=>"khaled@gmail.com"
            ];
        $expected->append($expectedOutput);
   		$this->assertEquals($expected,$formatEmail);
   }

   /**
    * [testEmailHasSpacesInEnd description]
    * 
    * @return [type] [description]
    */
   public function testEmailHasSpacesInEnd()
   {
        $emails = new \ArrayIterator();
        $emails->append("  khaled@gmail.com  ");

        $emailFormatter = new EmailFormatter($emails);
        $formatEmail = $emailFormatter->format();

        $expected = new \ArrayIterator;
        $expectedOutput=[
            "original"=>"  khaled@gmail.com  ",
            "formatted"=>"khaled@gmail.com"
            ];
        $expected->append($expectedOutput);
        $this->assertEquals($expected,$formatEmail);
   }
}
