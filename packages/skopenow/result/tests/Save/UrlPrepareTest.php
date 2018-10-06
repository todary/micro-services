<?php

use Skopenow\Result\Save\UrlPrepare;

class UrlPrepareTest extends TestCase
{
	protected $urlInfo;
	protected $urlPrepare;

	public function setup()
	{
		$this->urlInfo = loadService('UrlInfo'); 
		$this->urlPrepare = new UrlPrepare($this->urlInfo); 
	}

	public function testPrepareUrl()
	{
		$isProfile = true;
		$url = "http://facebook.com/kh.elkhamisy";
		$this->assertEquals($url, $this->urlPrepare->prepareUrl($url, $isProfile));
	}

	public function testPrepareUrlProfile()
	{
		$isProfile = true;
		$url = "http://facebook.com/kh.elkhamisy/profile.php";
		$exurl = "http://facebook.com/kh.elkhamisy";
		$this->assertEquals($url, $this->urlPrepare->prepareUrl($url, $isProfile));
	}
}