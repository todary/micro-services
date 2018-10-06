<?php

/**
 * WebsiteFormatterTest Class to test formatign of website
 *
 * @category Test Class EmailFormatterController
 * @package  MyPackage
 * @author   Queen tech <info@queentechsolutions.net>
 * @license  license http://www.queentechsolutions.net/
 * @link     http://www.queentechsolutions.net/
 */

use Skopenow\Formatter\Classes\WebsiteFormatter;
/**
 * WebsiteFormatterTest Class to test formatign of website
 *
 * @category Test Class EmailFormatterController
 * @package  MyPackage
 * @author   Queen tech <info@queentechsolutions.net>
 * @license  license http://www.queentechsolutions.net/
 * @link     http://www.queentechsolutions.net/
 */
class WebsiteFormatterTest extends TestCase
{
    /**
     * [testWebsiteHasHttps description]
     * 
     * @return [void] [description]
     */
    public function testWebsiteHasHttps()
    {
        $website = new \ArrayIterator();
        $website->append("https://facebook.com");

        $websiteFormatter = new WebsiteFormatter($website);
        $formatwebsite = $websiteFormatter->format();

        $expected = new \ArrayIterator;
        $expectedWebsites=[
            "original"=>"https://facebook.com",
            "formatted"=>"https://facebook.com"
            ];
        $expected->append($expectedWebsites);
        $this->assertEquals($expected,$formatwebsite);
    }

    /**
     * [testWebsiteHasHttp description]
     * 
     * @return [void] [description]
     */
    public function testWebsiteHasHttp()
    {
        $website = new \ArrayIterator();
        $website->append("http://facebook.com");

        $websiteFormatter = new WebsiteFormatter($website);
        $formatwebsite = $websiteFormatter->format();

        $expected = new \ArrayIterator;
        $expectedWebsites=[
            "original"=>"http://facebook.com",
            "formatted"=>"http://facebook.com"
            ];
        $expected->append($expectedWebsites);
        $this->assertEquals($expected,$formatwebsite);

    }

    /**
     * [testWebsiteHasNotHttpOrHttps description]
     * 
     * @return [Void] [description]
     */
    public function testWebsiteHasNotHttpOrHttps()
    {
        $website = new \ArrayIterator();
        $website->append("facebook.com");

        $websiteFormatter = new WebsiteFormatter($website);
        $formatwebsite = $websiteFormatter->format();

        $expected = new \ArrayIterator;
        $expectedWebsites=[
            "original"=>"facebook.com",
            "formatted"=>"http://facebook.com"
            ];
        $expected->append($expectedWebsites);
        $this->assertEquals($expected,$formatwebsite);
    }

   
}
