<?php
namespace App\Libraries\Screenshots;

class CaptureScreenshot
{

	public function __construct()
	{

	}

	public function capture(string $capture_url)
	{
		dump('Capturing screenshot: ('.$capture_url.')');
	}
}