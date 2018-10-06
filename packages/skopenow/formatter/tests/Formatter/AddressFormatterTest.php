<?php

/**
 * AddressFormatterTest Class to format array of full names
 *
 * @category Test Class EmailFormatterController
 * @package  MyPackage
 * @author   Queen tech <info@queentechsolutions.net>
 * @license  license http://www.queentechsolutions.net/
 * @link     http://www.queentechsolutions.net/
 */
namespace Skopenow\Formatter\tests\Formatter;

use Skopenow\Formatter\Classes\AddressFormatter;

/**
 * NameFormatter Class to format array of full names
 *
 * @category Test Class EmailFormatterController
 * @package  MyPackage
 * @author   Queen tech <info@queentechsolutions.net>
 * @license  license http://www.queentechsolutions.net/
 * @link     http://www.queentechsolutions.net/
 */
class AddressFormatterTest extends \TestCase
{
   /**
    * [testEmailFormattering description]
    *
    * @return [type] [description]
    */
    public function testCityFoundInAddress()
    {
        $addess = new \ArrayIterator();
        $data['full_address'] = "15 Jonkendy lane, New York, NY";
        $data['city'] = "New York, NY";
        $data['country'] = "USA";
        $addess->append($data);

        $emailFormatter = new AddressFormatter($addess);
        $fullAddress = $emailFormatter->format();

        $expected = new \ArrayIterator();
        $expectedOutput=[
            "original"=>$data,
            "formatted"=>"15 Jonkendy ln, New York, NY"
            ];
        $expected->append($expectedOutput);
        $this->assertEquals($expected, $fullAddress);
    }

    /**
    * [testEmailFormattering description]
    *
    * @return [type] [description]
    */
    public function testNYC()
    {
        $addess = new \ArrayIterator();
        $data['full_address'] = "nyc";
        $data['city'] = "";
        $data['country'] = "";
        $addess->append($data);

        $emailFormatter = new AddressFormatter($addess);
        $fullAddress = $emailFormatter->format();

        $expected = new \ArrayIterator();
        $expectedOutput=[
            "original"=>$data,
            "formatted"=>"New York, NY"
            ];
        $expected->append($expectedOutput);
        $this->assertEquals($expected, $fullAddress);
    }

   /**
    * [testAllAddressSent description]
    *
    * @return [type] [description]
    */
    public function testAllAddressSent()
    {
        $addess = new \ArrayIterator();
        $data['full_address'] = "15 John kennedy lane";
        $data['city'] = "New York, NY";
        $data['zpc'] = "10001";
        $data['country'] = "USA";

        $addess->append($data);

        $emailFormatter = new AddressFormatter($addess);
        $fullAddress = $emailFormatter->format();

        $expected = new \ArrayIterator();
        $expectedOutput=[
            "original"=>$data,
            "formatted"=>"15 John kennedy ln"
            ];
        $expected->append($expectedOutput);
        $this->assertEquals($expected, $fullAddress);
    }

   /**
    * [testAllAddressProbertesFoundInAdd description]
    *
    * @return [type] [description]
    */
    public function testAllAddressProbertesFoundInAdd()
    {
        $addess = new \ArrayIterator();
        $data['full_address'] = "15 John kennedy ln, New York, NY 10001, USA";
        $data['city'] = "New York, NY";
        $data['zpc'] = "10001";
        $data['country'] = "USA";

        $addess->append($data);

        $emailFormatter = new AddressFormatter($addess);
        $fullAddress = $emailFormatter->format();

        $expected = new \ArrayIterator();
        $expectedOutput=[
            "original"=>$data,
            "formatted"=>"15 John kennedy ln, New York, NY 10001, USA"
            ];
        $expected->append($expectedOutput);
        $this->assertEquals($expected, $fullAddress);
    }

    public function testCommaFoundInEndOfTheAddress()
    {
        $addess = new \ArrayIterator();
        $data['full_address'] = "15 John kennedy ln, New York, NY 10001, USA,";
        $data['city'] = "New York, NY";
        $data['zpc'] = "10001";
        $data['country'] = "USA";

        $addess->append($data);

        $emailFormatter = new AddressFormatter($addess);
        $fullAddress = $emailFormatter->format();

        $expected = new \ArrayIterator();
        $expectedOutput=[
            "original"=>$data,
            "formatted"=>"15 John kennedy ln, New York, NY 10001, USA"
            ];
        $expected->append($expectedOutput);
        $this->assertEquals($expected, $fullAddress);
    }
}
