<?php
/**
 * NumericUsernameUrlTest
 * 
 * @package Test
 * @access public
 * @copyright 2017-2018 Queen tech
 * @author Queen tech <info@queentechsolutions.net>
 * @version 1.0.0
 */


use Skopenow\Extract\Facebook\Images\UserUrlStrategy\NumericUsernameUrl;

class NumericUsernameUrlTest extends TestCase 
{
    public function testGetProfileUrl()
    {
        $t = new NumericUsernameUrl("robdouglas");
        
        $this->assertEquals('https://www.facebook.com/profile.php?id=robdouglas', $t->getProfileUrl());
    }
    
    public function testGetPhotosUrl()
    {
        $t = new NumericUsernameUrl("robdouglas");
        
        $this->assertEquals('https://www.facebook.com/profile.php?id=robdouglas&sk=photos', $t->getPhotosUrl());
    }
    
    public function testGetAlbumUrl()
    {
        $t = new NumericUsernameUrl("robdouglas");
        
        $this->assertEquals('https://www.facebook.com/profile.php?id=robdouglas&sk=photos_albums', $t->getAlbumUrl());
    }
}