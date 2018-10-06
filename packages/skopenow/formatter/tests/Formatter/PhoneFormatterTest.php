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

use Skopenow\Formatter\Classes\PhoneFormatter;

/**
 * NameFormatter Class to format array of full names
 *
 * @category Test Class EmailFormatterController
 * @package  MyPackage
 * @author   Queen tech <info@queentechsolutions.net>
 * @license  license http://www.queentechsolutions.net/
 * @link     http://www.queentechsolutions.net/
 */
class PhoneFormatterTest extends TestCase
{
   /**
    * [testValidPhone description]
    * 
    * @return [void] [description]
    */
   public function testValidPhone()
   {
        $phones = new \ArrayIterator();
        $phones->append("00201099999999");

        $phoneFormatter = new PhoneFormatter($phones);
        $formatphone = $phoneFormatter->format();

        $expected = new \ArrayIterator;
        $expectedOutput=[
            "original"=>"00201099999999",
            "formatted"=>"(002) 010-99999999"
            ];
        $expected->append($expectedOutput);
        $this->assertEquals($expected,$formatphone);
   }

   /**
    * [testPhoneHasChar description]
    * 
    * @return [void] [description]
    */
   public function testPhoneHasChar()
   {
        $phones = new \ArrayIterator();
        $phones->append("+201099999999");

        $phoneFormatter = new PhoneFormatter($phones);
        $formatphone = $phoneFormatter->format();

        $expected = new \ArrayIterator;
        $expectedOutput=[
            "original"=>"00201099999999",
            "formatted"=>"(201) 099-999999"
            ];
        $expected->append($expectedOutput);
        $this->assertEquals($expected,$formatphone);
   }

   /**
    * [testPhoneHasString description]
    * 
    * @return [Void] [description]
    */
   public function testPhoneHasString()
   {
        $phones = new \ArrayIterator();
        $phones->append("Tele:00201099999999");

        $phoneFormatter = new PhoneFormatter($phones);
        $formatphone = $phoneFormatter->format();

        $expected = new \ArrayIterator;
        $expectedOutput=[
            "original"=>"Tele:00201099999999",
            "formatted"=>"(002) 010-99999999"
            ];
        $expected->append($expectedOutput);
        $this->assertEquals($expected,$formatphone);
   }

   /**
    * [testPhoneStartWith1 description]
    * 
    * @return [Void] [description]
    */
   public function testPhoneStartWith1()
   {
        $phones = new \ArrayIterator();
        $phones->append("100201099999999");

        $phoneFormatter = new PhoneFormatter($phones);
        $formatphone = $phoneFormatter->format();

        $expected = new \ArrayIterator;
        $expectedOutput=[
            "original"=>"100201099999999",
            "formatted"=>"(002) 010-99999999"
            ];
        $expected->append($expectedOutput);
        $this->assertEquals($expected,$formatphone);
   }
}
