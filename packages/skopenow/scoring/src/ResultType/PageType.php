<?php

/**
 * PageType
 *
 * PHP version 7.0
 * 
 * @category Class
 * @package  Scoring
 * @author   Queen tech <info@queentechsolutions.net>
 * @license  license http://www.queentechsolutions.net/
 * @link     http://www.queentechsolutions.net/
 */
namespace Skopenow\Scoring\ResultType;

/**
 * PageType
 * 
 * @category Class
 * @package  Scoring
 * @author   Queen tech <info@queentechsolutions.net>
 * @license  license http://www.queentechsolutions.net/
 * @link     http://www.queentechsolutions.net/
 */
class PageType implements PageTypeInterface
{

	/**
	 * [checkPageType description]
	 * 
	 * @param string $mainSource [description]
	 * @param string $link       [description]
	 * 
	 * @return string             [description]
	 */
	public function checkPageType(string $mainSource, string $link)
	{ 
		switch ($mainSource) {
			case 'facebook':
				$key = $this->getFacebookKey($link);
				break;
			
			case 'twitter':
				$key = $this->getTwitterKey($link);
				break;

			case 'instagram':
				$key = $this->getInstagramKey($link);
				break;

			case 'pinterest':
				$key = $this->getPinterestKey($link);
				break;

			case 'myspace':
				$key = $this->getMyspaceKey($link);
				break;

			case 'youtube':
				$key = $this->getYoutubeKey($link);
				break;

			case 'googleplus':
				$key = $this->getGoogleplusKey($link);
				break;

			default:
				$key = null;
				break;
		}

		return $key;
	}

	/**
	 * [getFacebookKey description]
	 * 
	 * @param string $link [description]
	 * 
	 * @return string       [description]
	 */
	protected function getFacebookKey(string $link)
	{
		switch ($link) {
			case strpos($link, '/about') !== false :
				$key = 'profile';
				break;
			
			case strpos($link, '/friends') !== false :
				$key = 'profile';
				break;

			case strpos($link, '/photos') !== false :
				$key = 'profile';
				break;

			case strpos($link, '/events') !== false :
				$key = 'profile';
				break;

			case strpos($link, 'photo.php') !== false :
				$key = 'photo';
				break;

			case strpos($link, 'video') !== false :
				$key = 'video';
				break;

			case strpos($link, 'posts') !== false :
				$key = 'comment';
				break;

			default:
				$key = null;
				break;
		}

		return $key;
	}

	/**
	 * [getTwitterKey description]
	 * 
	 * @param string $link [description]
	 * 
	 * @return string       [description]
	 */
	protected function getTwitterKey(string $link)
	{
		switch ($link) {
			case strpos($link, '/status/') !== false :
				$key = 'comment';
				break;
			
			case strpos($link, '/following') !== false :
				$key = 'comment';
				break;

			case strpos($link, '/followers') !== false :
				$key = 'comment';
				break;

			case strpos($link, '/favorites') !== false :
				$key = 'comment';
				break;

			case strpos($link, '/lists/') !== false :
				$key = 'comment';
				break;

			default:
				$key = null;
				break;
		}

		return $key;
	}

	/**
	 * [getInstagramKey description]
	 * 
	 * @param string $link [description]
	 * 
	 * @return string       [description]
	 */
	protected function getInstagramKey(string $link)
	{
		$key = null;
		if(strpos($link,'.com/p/') !== false){
			$key = 'photo';
		}

		return $key;
	}

	/**
	 * [getPinterestKey description]
	 * 
	 * @param string $link [description]
	 * 
	 * @return string       [description]
	 */
	protected function getPinterestKey(string $link)
	{
		$key = null;
		if(strpos($link,'pinterest.com/pin/')  !== false ){
			$key = 'photo';
		}

		return $key;
	}

	/**
	 * [getMyspaceKey description]
	 * 
	 * @param string $link [description]
	 * 
	 * @return string       [description]
	 */
	protected function getMyspaceKey(string $link)
	{
		switch ($link) {
			case strpos($link, '/video') !== false :
				$key = 'video';
				break;

			case strpos($link, '/photo') !== false :
				$key = 'photo';
				break;
			
			default:
				$key = null;
				break;
		}

		return $key;
	}
	
	/**
	 * [getYoutubeKey description]
	 * 
	 * @param string $link [description]
	 * 
	 * @return string       [description]
	 */
	protected function getYoutubeKey(string $link)
	{
		switch ($link) {
			case strpos($link, 'watch?v=') !== false :
				$key = 'video';
				break;

			default:
				$key = null;
				break;
		}

		return $key;
	}
	
	/**
	 * [getGoogleplusKey description]
	 * 
	 * @param string $link [description]
	 * 
	 * @return string       [description]
	 */
	protected function getGoogleplusKey(string $link)
	{
		switch ($link) {
			case strpos($link, '/posts') !== false :
				$key = 'comment';
				break;

			case strpos($link, '/photos') !== false :
				$key = 'photo';
				break;
			
			default:
				$key = null;
				break;
		}

		return $key;
	}

}