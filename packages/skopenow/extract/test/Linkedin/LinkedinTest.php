<?php

/**
 * LinkedinTest
 * 
 * @package Test
 * @access public
 * @copyright 2017-2018 Queen tech
 * @author Queen tech <info@queentechsolutions.net>
 * @version 1.0.0
 */

use Skopenow\Extract\Linkedin\Linkedin;
use Skopenow\Extract\Linkedin\Iterator\LinkedinIterator;
use Skopenow\Extract\Linkedin\Extractor\ExtractorInterface;

class DummyExtractor2 implements ExtractorInterface
{
    
    private $data;
    public function __construct($data)
    {
        $this->data = $data;
    }
    public function Process() : array
    {
        return [[$this->data]];
    }
}

class LinkedinTest extends TestCase
{
    public function testExtract()
    {
        $t = new Linkedin(new LinkedinIterator());
        $res = $t->Extract(new DummyExtractor2("test"))->getResults();
       
        $expected = new \ArrayIterator([array("test")]);
        
        $this->assertEquals($expected, $res);
    }
}

