<?php

/**
 * PageTypeTest
 *
 * PHP version 7.0
 * 
 * @category PHPUnit
 * @package  Score
 * @author   Queen tech <info@queentechsolutions.net>
 * @license  license http://www.queentechsolutions.net/
 * @link     http://www.queentechsolutions.net/
 */

use Skopenow\Scoring\ResultType\PageType;

/**
 * PageTypeTest
 * 
 * @category PHPUnit
 * @package  Score
 * @author   Queen tech <info@queentechsolutions.net>
 * @license  license http://www.queentechsolutions.net/
 * @link     http://www.queentechsolutions.net/
 */
class PageTypeTest extends TestCase
{
	protected $pageType;

	public function setup()
	{
		$this->pageType = new PageType;
	}

	public function testCheckPageTypeFacebookAbout()
	{
		$mainSource = "facebook";
		$link = "https://www.facebook.com/user/about";
		$this->assertEquals("profile",$this->pageType->checkPageType($mainSource,$link));
	}

	public function testCheckPageTypeFacebookFriends()
	{
		$mainSource = "facebook";
		$link = "https://www.facebook.com/user/friends";
		$this->assertEquals("profile",$this->pageType->checkPageType($mainSource,$link));
	}

	public function testCheckPageTypeFacebookPhotos()
	{
		$mainSource = "facebook";
		$link = "https://www.facebook.com/user/photos";
		$this->assertEquals("profile",$this->pageType->checkPageType($mainSource,$link));
	}

	public function testCheckPageTypeFacebookEvents()
	{
		$mainSource = "facebook";
		$link = "https://www.facebook.com/user/events";
		$this->assertEquals("profile",$this->pageType->checkPageType($mainSource,$link));
	}

	public function testCheckPageTypeFacebookPhoto()
	{
		$mainSource = "facebook";
		$link = "https://www.facebook.com/user/photo.php";
		$this->assertEquals("photo",$this->pageType->checkPageType($mainSource,$link));
	}

	public function testCheckPageTypeFacebookVideo()
	{
		$mainSource = "facebook";
		$link = "https://www.facebook.com/user/video";
		$this->assertEquals("video",$this->pageType->checkPageType($mainSource,$link));
	}

	public function testCheckPageTypeFacebookPosts()
	{
		$mainSource = "facebook";
		$link = "https://www.facebook.com/user/posts";
		$this->assertEquals("comment",$this->pageType->checkPageType($mainSource,$link));
	}

	public function testCheckPageTypeFacebookNull()
	{
		$mainSource = "facebook";
		$link = "https://www.facebook.com/user";
		$this->assertEquals(null,$this->pageType->checkPageType($mainSource,$link));
	}

	public function testCheckPageTypeTwitterStatus()
	{
		$mainSource = "twitter";
		$link = "https://twitter.com/status/";
		$this->assertEquals('comment',$this->pageType->checkPageType($mainSource,$link));
	}

	public function testCheckPageTypeTwitterFollowing()
	{
		$mainSource = "twitter";
		$link = "https://twitter.com/following";
		$this->assertEquals('comment',$this->pageType->checkPageType($mainSource,$link));
	}

	public function testCheckPageTypeTwitterFollowers()
	{
		$mainSource = "twitter";
		$link = "https://twitter.com/followers";
		$this->assertEquals('comment',$this->pageType->checkPageType($mainSource,$link));
	}

	public function testCheckPageTypeTwitterFavorites()
	{
		$mainSource = "twitter";
		$link = "https://twitter.com/favorites";
		$this->assertEquals('comment',$this->pageType->checkPageType($mainSource,$link));
	}

	public function testCheckPageTypeTwitterLists()
	{
		$mainSource = "twitter";
		$link = "https://twitter.com/lists/";
		$this->assertEquals('comment',$this->pageType->checkPageType($mainSource,$link));
	}

	public function testCheckPageTypeTwitterNull()
	{
		$mainSource = "twitter";
		$link = "https://twitter.com/";
		$this->assertEquals(null,$this->pageType->checkPageType($mainSource,$link));
	}

	public function testCheckPageTypeInstagramPhoto()
	{
		$mainSource = "instagram";
		$link = "https://www.instagram.com/p/?hl=en";
		$this->assertEquals('photo',$this->pageType->checkPageType($mainSource,$link));
	}

	public function testCheckPageTypePinterestPhoto()
	{
		$mainSource = "pinterest";
		$link = "https://www.pinterest.com/pin/";
		$this->assertEquals('photo',$this->pageType->checkPageType($mainSource,$link));
	}

	public function testCheckPageTypeMyspaceVideo()
	{
		$mainSource = "myspace";
		$link = "https://myspace.com/video";
		$this->assertEquals('video',$this->pageType->checkPageType($mainSource,$link));
	}

	public function testCheckPageTypeMyspacePhoto()
	{
		$mainSource = "myspace";
		$link = "https://myspace.com/photo";
		$this->assertEquals('photo',$this->pageType->checkPageType($mainSource,$link));
	}

	public function testCheckPageTypeMyspaceNull()
	{
		$mainSource = "myspace";
		$link = "https://myspace.com/";
		$this->assertEquals(null,$this->pageType->checkPageType($mainSource,$link));
	}

	public function testCheckPageTypeYoutubeVideo()
	{
		$mainSource = "youtube";
		$link = "https://youtube.com/watch?v=";
		$this->assertEquals('video',$this->pageType->checkPageType($mainSource,$link));
	}

	public function testCheckPageTypeYoutubeNull()
	{
		$mainSource = "youtube";
		$link = "https://youtube.com/";
		$this->assertEquals(null,$this->pageType->checkPageType($mainSource,$link));
	}

	public function testCheckPageTypeGoogleplusPosts()
	{
		$mainSource = "googleplus";
		$link = "https://googleplus.com/posts";
		$this->assertEquals('comment',$this->pageType->checkPageType($mainSource,$link));
	}

	public function testCheckPageTypeGoogleplusPhotos()
	{
		$mainSource = "googleplus";
		$link = "https://googleplus.com/photos";
		$this->assertEquals('photo',$this->pageType->checkPageType($mainSource,$link));
	}

	public function testCheckPageTypeGoogleplusNull()
	{
		$mainSource = "googleplus";
		$link = "https://googleplus.com/";
		$this->assertEquals(null,$this->pageType->checkPageType($mainSource,$link));
	}

	public function testCheckPageTypeNull()
	{
		$mainSource = "nomainsource";
		$link = "https://skopenow.com/";
		$this->assertEquals(null,$this->pageType->checkPageType($mainSource,$link));
	}

	


}