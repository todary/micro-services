<?php

/**
 * TopSitesFile
 *
 * PHP version 7.0
 * 
 * @category Class
 * @package  Scoring
 * @author   Queen tech <info@queentechsolutions.net>
 * @license  license http://www.queentechsolutions.net/
 * @link     http://www.queentechsolutions.net/
 */
namespace Skopenow\Scoring\TopSites;

/**
 * TopSitesFile
 *
 * @category Class
 * @package  Scoring
 * @author   Queen tech <info@queentechsolutions.net>
 * @license  license http://www.queentechsolutions.net/
 * @link     http://www.queentechsolutions.net/
 */
class TopSitesFile extends TopSitesAbstract implements TopSitesInterface
{
    /**
     * [checkTopSites description]
     * 
     * @param string $url [description]
     * 
     * @return bool      [description]
     */
    public function checkTopSites(string $url)
    {
        return parent::checkTopSites($url);

    }

    /**
     * [getTopSites description]
     * 
     * @return array [description]
     */
    public function getTopSites()
    {
        if (!$this->topSites) {
			// require __DIR__."/../config/quantcastList.php";
			$this->topSites = loadData('topSites'); //config("quantcastList");
			

//            $pathTopSites = \Yii::getPathOfAlias('application')
//            . '/../automation/topSites.php';
//            if (file_exists($pathTopSites)) {
//                $this->topSites = require($pathTopSites);
//            }
        }

        return $this->topSites;
    }

}