<?php

/**
 * PageImagesTest
 * 
 * @package Test
 * @access public
 * @copyright 2017-2018 Queen tech
 * @author Queen tech <info@queentechsolutions.net>
 * @version 1.0.0
 */


use Skopenow\Extract\Facebook\Images\PageImages;
use Skopenow\Extract\Facebook\Images\Iterator\ImageIterator;

class PageImagesTest extends TestCase 
{
    public function testExtract()
    {
        require_once dirname(__FILE__) . '/../../../../../../../proxy_code/reg_actions.php';
        $link = "https://www.facebook.com/cairopost";
        
        $mock = $this->getMockBuilder(PageImages::class)
                    ->setMethods(array('sendRequest'))
                    ->setConstructorArgs(array($link, new ImageIterator()))
                    ->getMock();
        
        $response = file_get_contents(dirname(__FILE__).'/../../../facebook-pageimage-reponse.html');
        
        $mock->method('sendRequest')
                ->will($this->returnValue(['body' => $response]));
        
//        $result = file_get_contents(dirname(__FILE__).'/../../../facebook-pageimage-result.json');
        
        $this->assertEquals(9, count($mock->Extract()->getResults()));
    }
    
    public function testSetSessId()
    {
        $link = "https://www.facebook.com/rob.douglas.7923";
        $post = new PageImages($link, new ImageIterator());
        
        $this->assertInstanceOf(PageImages::class, $post->setSessId("ddddd"));
    }
    
    public function testSetRequestOptions()
    {
        $link = "https://www.facebook.com/rob.douglas.7923";
        $post = new PageImages($link, new ImageIterator());
        
        $this->assertInstanceOf(PageImages::class, $post->setRequestOptions([]));
    }
    
    public function testSetOldResult()
    {
        $link = "https://www.facebook.com/rob.douglas.7923";
        $post = new PageImages($link, new ImageIterator());
        
        $this->assertInstanceOf(PageImages::class, $post->setOldResult([]));
    }
    
    public function testGetRequestUrl()
    {
        require_once dirname(__FILE__) . '/../../../../../../../proxy_code/reg_actions.php';
        $link = "https://www.facebook.com/rob.douglas.7923?get=1";
        $post = new PageImages($link, new ImageIterator());
        
        $this->assertEquals("https://www.facebook.com/rob.douglas.7923?__sid=&get=1/photos", $post->getRequestUrl($link));
    }
    
    public function testGetPageUrlStrategy()
    {
        $link = "https://www.facebook.com/rob.douglas.7923?get=1";
        $post = new PageImages($link, new ImageIterator());
        $this->assertEquals("https://m.facebook.com/rob.douglas.7923", $post->getPageUrlStrategy("https://facebook.com/rob.douglas.7923")->getUrl());
    }
    
    public function testGetPageUrlStrategy2()
    {
        $link = "https://www.facebook.com/rob.douglas.7923?get=1";
        $post = new PageImages($link, new ImageIterator());
        $this->assertEquals("https://m.facebook.com/rob.douglas.7923", $post->getPageUrlStrategy("https://m.facebook.com/rob.douglas.7923")->getUrl());
    }
    
    public function testSendRequest()
    {
        require_once dirname(__FILE__) . '/../../../../../../../proxy_code/reg_actions.php';
        $link = "https://www.facebook.com/rob.douglas.7923";
        $post = new PageImages($link, new ImageIterator());
        
        $pageUrl = $post->getPageUrlStrategy($link);
        $url = $pageUrl->getUrl();
        
        $requestUrl = $post->getRequestUrl($url);
        $options = $post->getCurlOptions();
        $response = $post->sendRequest($requestUrl, $options);
        
        $this->assertTrue(isset($response["body"]));
    }
    
    public function testGetMoreLinksEmpty()
    {
        require_once dirname(__FILE__) . '/../../../../../../../proxy_code/reg_actions.php';
        $link = "https://www.facebook.com/rob.douglas.7923";
        $post = new PageImages($link, new ImageIterator());
        
        $options = $post->getCurlOptions();
        
        $pageUrl = $post->getPageUrlStrategy($link);
        $url = $pageUrl->getUrl();
        
        $requestUrl = $post->getRequestUrl($url);
        
        $moreLinks = $post->getMoreLinks($requestUrl, $options);
        
        $this->assertEquals([], $moreLinks);
    }
    
    public function testGetMoreLinksNotEmpty()
    {
        require_once dirname(__FILE__) . '/../../../../../../../proxy_code/reg_actions.php';
        $link = "https://www.facebook.com/cairopost";
        
        $mock = $this->getMockBuilder(PageImages::class)
                    ->setMethods(array('sendRequest'))
                    ->setConstructorArgs(array($link, new ImageIterator()))
                    ->getMock();
        
        $response = file_get_contents(dirname(__FILE__).'/../../../facebooktestmorelinks.html');
        
        $mock->method('sendRequest')
                ->will($this->returnValue(['body' => $response]));
                
        $this->assertEquals([], $mock->getMoreLinks($link, []));
    }
}
