<?php
/**
 * YoutubeIteratorTest
 * 
 * @package Test
 * @access public
 * @copyright 2017-2018 Queen tech
 * @author Queen tech <info@queentechsolutions.net>
 * @version 1.0.0
 */


use Skopenow\Extract\Youtube\Iterator\YoutubeIterator;

class YoutubeIteratorTest extends TestCase 
{
    public function testGetIterator()
    {
        $iterator = new YoutubeIterator;
        
        $iterator->addResult(array
                (
                    'title' => '40 kg Bulldog Kb push press',
                    'url' => 'https://www.youtube.com/watch?v=pPl5EOscRGc',
                    'image' => 'https://i.ytimg.com/vi/pPl5EOscRGc/hqdefault.jpg',
                    'type' => 'video'
                ));
        
        $expected = new \ArrayIterator(array(array
                (
                    'title' => '40 kg Bulldog Kb push press',
                    'url' => 'https://www.youtube.com/watch?v=pPl5EOscRGc',
                    'image' => 'https://i.ytimg.com/vi/pPl5EOscRGc/hqdefault.jpg',
                    'type' => 'video'
                )));
        
        $this->assertEquals($expected , $iterator->getIterator());
    }
}
