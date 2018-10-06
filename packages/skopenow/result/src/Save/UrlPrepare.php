<?php

namespace Skopenow\Result\Save;

class UrlPrepare
{
	protected $urlInfo;

	public function __construct($urlInfo)
	{
		$this->urlInfo = $urlInfo;
	}

	public function prepareUrl($url,$isProfile)
	{
		$url =  $this->urlInfo->normalizeURL($url);

		//get url site name
		$title = $this->urlInfo->getSiteName($url);

		//check if url form facebook to prepare facebook url
        if ($isProfile && $title == "FACEBOOK") {
        	$url = $this->prepereFacebookUrl($url);
        }

		return $url;
	}

	protected function prepereFacebookUrl($url)
	{
		$url = urldecode($url);

        $url = preg_replace("#(\-|\w|\d|\.)+\.facebook#i", "www.facebook", $url);

        if (strpos($url, "profile.php") === false) {
            $url = preg_replace("#\?ref\=.+$#i", "", $url);
            $url = preg_replace("#\?fref\=.+$#i", "", $url);
        } else {
            $temp_parts = explode("&", $url);

            if (isset($temp_parts[0])) {
            	$url = $temp_parts[0];
            }
        }
        return $url;
	}


}