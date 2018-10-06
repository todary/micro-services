<?php

/**
 * UserImagesTest
 * 
 * @package Test
 * @access public
 * @copyright 2017-2018 Queen tech
 * @author Queen tech <info@queentechsolutions.net>
 * @version 1.0.0
 */


use Skopenow\Extract\Facebook\Images\UserImages;
use Skopenow\Extract\Facebook\Images\Iterator\ImageIterator;

class UserImagesTest extends TestCase 
{
    public function testExtract()
    {
        require_once dirname(__FILE__) . '/../../../../../../../proxy_code/reg_actions.php';
        
        $link = "https://www.facebook.com/rob.douglas.7923";
        
        $mock = $this->getMockBuilder(UserImages::class)
                     ->setMethods(array('sendRequest', 'runRequestLoop'))
                     ->setConstructorArgs(array($link, new ImageIterator()))
                     ->getMock();
        
        $result = file_get_contents(dirname(__FILE__).'/../../../facebook-userimages-response.html');
        
        $mock->method('sendRequest')
                ->will($this->returnValue(['body' => $result]));
        
        $loop_result = file_get_contents(dirname(__FILE__).'/../../../facebook-userimages-result.json');
        
        $mock->method('runRequestLoop')
                ->will($this->returnValue(json_decode($loop_result, true)));
        $mock->Extract();
        
        $result_iterator = file_get_contents(dirname(__FILE__).'/../../../facebook-userimages-res-iterator.json');
        
        $this->assertEquals(new \ArrayIterator(json_decode($result_iterator, true)), $mock->getResults());
    }
    
    public function testSetSessId()
    {
        $link = "https://www.facebook.com/rob.douglas.7923";
        $post = new UserImages($link, new ImageIterator());
        
        $this->assertInstanceOf(UserImages::class, $post->setSessId("ddddd"));
    }
    
    public function testSetRequestOptions()
    {
        $link = "https://www.facebook.com/rob.douglas.7923";
        $post = new UserImages($link, new ImageIterator());
        
        $this->assertInstanceOf(UserImages::class, $post->setRequestOptions([]));
    }
    
    public function testSetLimit()
    {
        $link = "https://www.facebook.com/rob.douglas.7923";
        $post = new UserImages($link, new ImageIterator());
        
        $this->assertInstanceOf(UserImages::class, $post->setLimit(30));
    }
    
    public function testSetExtractLevel()
    {
        $link = "https://www.facebook.com/rob.douglas.7923";
        $post = new UserImages($link, new ImageIterator());
        
        $this->assertInstanceOf(UserImages::class, $post->setExtractLevel(30));
    }
    
    public function testSetOldResult()
    {
        $link = "https://www.facebook.com/rob.douglas.7923";
        $post = new UserImages($link, new ImageIterator());
        
        $this->assertInstanceOf(UserImages::class, $post->setOldResult([]));
    }
    
    public function testGetRequestUrl()
    {
        require_once dirname(__FILE__) . '/../../../../../../../proxy_code/reg_actions.php';
        $link = "https://www.facebook.com/rob.douglas.7923?get=1";
        $post = new UserImages($link, new ImageIterator());
        
        $this->assertEquals("https://www.facebook.com/rob.douglas.7923?__sid=&get=1", $post->getRequestUrl($link));
    }
    
    public function testGetFacebookUsernameUrls()
    {
        require_once dirname(__FILE__) . '/../../../../../../../proxy_code/reg_actions.php';
        $link = "https://www.facebook.com/profile.php?id=7923";
        $post = new UserImages($link, new ImageIterator());
        
        $this->assertEquals("https://www.facebook.com/profile.php?id=7923", $post->getFacebookUsernameUrls($link)->getProfileUrl());
    }
    
    public function testSendRequest()
    {
        require_once dirname(__FILE__) . '/../../../../../../../proxy_code/reg_actions.php';
        $link = "https://www.facebook.com/rob.douglas.7923";
        $post = new UserImages($link, new ImageIterator());
        
        $urlStrategy = $post->getFacebookUsernameUrls($link);
        $albumsURL = $urlStrategy->getAlbumUrl();
        $options = $post->getCurlOptions();
        $requestUrl = $post->getRequestUrl($albumsURL);
        $response = $post->sendRequest($requestUrl, $options);
        
        $this->assertTrue(isset($response["body"]));
    }
    
    public function testGetFacebookUsername()
    {
        require_once dirname(__FILE__) . '/../../../../../../../proxy_code/reg_actions.php';
        $link = "https://www.facebook.com/photo.php?id=7883";
        $post = new UserImages($link, new ImageIterator());
        
        $this->assertEquals("", $post->getFacebookUsername($link));
    }
}
