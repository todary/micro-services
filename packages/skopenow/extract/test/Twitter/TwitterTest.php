<?php

/**
 * TwitterTest
 * 
 * @package Test
 * @access public
 * @copyright 2017-2018 Queen tech
 * @author Queen tech <info@queentechsolutions.net>
 * @version 1.0.0
 */

use Skopenow\Extract\Twitter\Twitter;
use Skopenow\Extract\Twitter\Iterator\TwitterIterator;
use Skopenow\Extract\Twitter\Extractor\PostsExtractor;
use Skopenow\Extract\Twitter\Extractor\ExtractorInterface;

class DummyExtractor implements ExtractorInterface
{
    
    private $data;
    public function __construct(string $data)
    {
        $this->data = $data;
    }
    public function Process() : array
    {
        return [$this->data];
    }
}

class TwitterTest extends TestCase
{
    public function testExtract()
    {
        $twitter = new Twitter(new TwitterIterator());
        $res = $twitter->Extract(new DummyExtractor("test"))->getResults();
       
        $expected = new \ArrayIterator(array("test"));
        
        $this->assertEquals($expected, $res);
    }
}

