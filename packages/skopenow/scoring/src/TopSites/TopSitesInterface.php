<?php

/**
 * TopSitesFile
 *
 * PHP version 7.0
 * 
 * @category Interface
 * @package  Scoring
 * @author   Queen tech <info@queentechsolutions.net>
 * @license  license http://www.queentechsolutions.net/
 * @link     http://www.queentechsolutions.net/
 */
namespace Skopenow\Scoring\TopSites;

/**
 * TopSitesFile
 * 
 * @category Interface
 * @package  Scoring
 * @author   Queen tech <info@queentechsolutions.net>
 * @license  license http://www.queentechsolutions.net/
 * @link     http://www.queentechsolutions.net/
 */
interface TopSitesInterface
{
    /**
     * [checkTopSites description]
     * 
     * @param string $url [description]
     * 
     * @return bool      [description]
     */
    public function checkTopSites(string $url);

    /**
     * [getTopSites description]
     * 
     * @return array [description]
     */
    public function getTopSites();

}