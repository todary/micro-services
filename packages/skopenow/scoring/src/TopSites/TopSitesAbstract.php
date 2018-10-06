<?php

/**
* TopSitesAbstract
*
* PHP version 7.0
* 
* @category Abstract
* @package  Scoring
* @author   Queen tech <info@queentechsolutions.net>
* @license  license http://www.queentechsolutions.net/
* @link     http://www.queentechsolutions.net/
*/
namespace Skopenow\Scoring\TopSites;

use Cache;

/**
* TopSitesAbstract
* 
* @category Abstract
* @package  Scoring
* @author   Queen tech <info@queentechsolutions.net>
* @license  license http://www.queentechsolutions.net/
* @link     http://www.queentechsolutions.net/
*/
abstract class TopSitesAbstract
{
    /**
     * [$topSites description]
     * 
     * @var array or null
     */
    protected $topSites = array();

    /**
     * [checkTopSites description]
     * 
     * @param string $url [description]
     * 
     * @return bool      [description]
     */
    public function checkTopSites(string $url)
    {
        $return = false;
        $this->getTopSites();
		$this->topSites = array_column($this->topSites, "domain");
        $host = parse_url(trim($url), PHP_URL_HOST);
        $host = ltrim($host, 'www.');

        $ret = Cache::get('#topSites_'.$host);

		if ($ret) {
            $return = $ret;
        }elseif (in_array($host, $this->topSites,true)) {
            $return = true;
        }

        $cacheTime = 60*60*4;
        Cache::put('#topSites_'.$host, $return, $cacheTime);

        return $return;
    }

    /**
     * [getTopSites description]
     * 
     * @return array [description]
     */
    abstract public function getTopSites();

}