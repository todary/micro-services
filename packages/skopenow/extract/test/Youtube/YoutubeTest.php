<?php

/**
 * YoutubeTest
 * 
 * @package Test
 * @access public
 * @copyright 2017-2018 Queen tech
 * @author Queen tech <info@queentechsolutions.net>
 * @version 1.0.0
 */


use Skopenow\Extract\Youtube\Youtube;
use Skopenow\Extract\Youtube\Iterator\YoutubeIterator;

class YoutubeTest extends TestCase 
{
    protected static function getMethod($name) {
        $class = new \ReflectionClass(Youtube::class);
        $method = $class->getMethod($name);
        $method->setAccessible(true);
        return $method;
    }
    
    public function testGetRequestUrl()
    {
        $link = "https://www.youtube.com/user/wishniecompany";
        $youtube = new Youtube($link, new YoutubeIterator());
        $method = self::getMethod('getRequestUrl');
        
        $this->assertEquals("https://www.youtube.com/user/wishniecompany/videos", $method->invokeArgs($youtube, array($link, "videos")));
    }
    
    public function testExtract()
    {
        require_once(dirname(__FILE__).'/../../../../../../apis/simple_html_dom.php');
        $link = "https://www.youtube.com/user/wishniecompany";
        $requestUrl = "https://www.youtube.com/user/wishniecompany/videos";
//        $method = self::getMethod('sendRequest');
        
        //$response = $method->invokeArgs($youtube, array($requestUrl, []));
        $mock = $this->getMockBuilder(Youtube::class)
            ->setConstructorArgs([$link, new YoutubeIterator()])
            ->setMethods(array('sendRequest'))
            ->getMock();
        
        $result = file_get_contents(dirname(__DIR__) . "/../result.html");
        
        $mock->method('sendRequest')
                ->will($this->returnValue(['body' => $result]));
        $result = new \ArrayIterator(
                    array
        (
            0 => array
                (
                    'title' => '40 kg Bulldog Kb push press',
                    'url' => 'https://www.youtube.com/watch?v=pPl5EOscRGc',
                    'image' => 'https://i.ytimg.com/vi/pPl5EOscRGc/hqdefault.jpg',
                    'type' => 'video'
                ),

            1 => array
                (
                    'title' => 'Beast Swings',
                    'url' => 'https://www.youtube.com/watch?v=gMn9P5saBG0',
                    'image' => 'https://i.ytimg.com/vi/gMn9P5saBG0/hqdefault.jpg',
                    'type' => 'video'
                ),

            2 => array
                (
                    'title' => '90# push press fail 11-17-14',
                    'url' => 'https://www.youtube.com/watch?v=7zeR4xN-cK0',
                    'image' => 'https://i.ytimg.com/vi/7zeR4xN-cK0/hqdefault.jpg',
                    'type' => 'video'
                ),

            3 => array
                (
                    'title' => '315 pound Deadlift',
                    'url' => 'https://www.youtube.com/watch?v=5ZEve0Vr4oo',
                    'image' => 'https://i.ytimg.com/vi/5ZEve0Vr4oo/hqdefault.jpg',
                    'type' => 'video'
                ),

            4 => array
                (
                    'title' => 'Deadlift 315lbs from 4 inched off floor',
                    'url' => 'https://www.youtube.com/watch?v=ATDoryd2sNo',
                    'image' => 'https://i.ytimg.com/vi/ATDoryd2sNo/hqdefault.jpg',
                    'type' => 'video'
                )
                )
                );
        $expected = $result->offsetGet(1);
        $actual = $mock->Extract()->getResults();
        $this->assertEquals($expected['url'], $actual->offsetGet(1)['url']);
    }
    
    public function testSendRequest()
    {
        $link = "https://www.youtube.com/user/wishniecompany";
        $requestUrl = "https://www.youtube.com/user/wishniecompany/videos";
        $youtube = new Youtube($link, new YoutubeIterator());
        $data = $youtube->sendRequest($requestUrl, []);
        
        $this->assertArrayHasKey("body", $data);
    }
}
