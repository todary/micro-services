<?php

/**
 * ResultTypeScore
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

use Skopenow\Scoring\TopSites\TopSitesInterface;

/**
 * ResultTypeScore
 * 
 * @category Class
 * @package  Scoring
 * @author   Queen tech <info@queentechsolutions.net>
 * @license  license http://www.queentechsolutions.net/
 * @link     http://www.queentechsolutions.net/
 */
class ResultTypeScore
{
    /**
     * [$mainSource description]
     * 
     * @var string
     */
    protected $mainSource;

    /**
     * [$source description]
     * 
     * @var string
     */
    protected $source;

    /**
     * [$link description]
     * 
     * @var string
     */
    protected $link;

    /**
     * [$type description]
     * 
     * @var string
     */
    protected $type;

    /**
     * [$isProfile description]
     * 
     * @var boolean
     */
    protected $isProfile;

    /**
     * [$isRelative description]
     * 
     * @var boolean
     */
    protected $isRelative;

    /**
     * [$keyScore description]
     * 
     * @var KeyScoreInterface
     */
    protected $keyScore;

    /**
     * [$topSite description]
     * 
     * @var TopSitesInterface
     */
    protected $topSite;

    /**
     * [$pageType description]
     * 
     * @var PageTypeInterface
     */
    protected $pageType;



    /**
     * [__construct description]
     * 
     * @param string  $link       [description]
     * @param string  $type       [description]
     * @param boolean $isProfile  [description]
     * @param boolean $isRelative [description]
     */
    public function __construct(
        KeyScoreInterface $keyScore, 
        TopSitesInterface $topSite,
        PageTypeInterface $pageType
    ) {
        $this->keyScore = $keyScore;
        $this->topSite = $topSite;
        $this->pageType = $pageType;
    }

   /**
    * [getScore description]
    * 
    * @param array  $source 
    * [keys of source are mainSource, source, link, type, isProfile, isRelative]
    * 
    * @return float         [description]
    */
    public function getScore(array $source) 
    {
        if(isset($source["main_source"]))
            $this->mainSource = $source["main_source"];

        if(isset($source["source"]))
            $this->source = $source["source"];

        if(isset($source["link"]))
            $this->link = $source["link"];

        if(isset($source["type"]))
            $this->type = $source["type"];

        if(isset($source["isProfile"]))
            $this->isProfile = $source["isProfile"];

        if(isset($source["isRelative"]))
            $this->isRelative = $source["isRelative"];


        $score = 0;
        //get key 
        $key = $this->getKey();

		//get score from genrated key
        if ($key) {
            $score = $this->keyScore->getScoreFromKey($key);
        }

        return $score;
    }

    /**
     * [getKey description]
     * 
     * @return string [description]
     */
    public function getKey()
    {
        switch (true) {
            case $this->isList():
                $key = 'list';  
                break;

            case $this->isRelative:
                $key = 'relative';  
                break;

            case $this->isResult():
                $key = $this->getResultKey();  
                break;
            
            default:
                $key = null;
                break;
        }

        return $key;
    }

    /**
     * [isList description]
     * 
     * @return boolean [description]
     */
    protected function isList()
    {
        if ($this->type == 'list') {
            return true;
        }
        return false;
    }

    /**
     * [isResult description]
     * 
     * @return boolean [description]
     */
    protected function isResult()
    {
        if ($this->type == 'result') {
            return true;
        }
        return false;
    }

    /**
     * [getResultKey description]
     * 
     * @return string [description]
     */
    protected function getResultKey()
    {
        if (($this->mainSource == 'google' 
            || strpos($this->source, 'google') !== false )
            && strpos($this->source, 'googleplus') === false 
        ) {

            $key = "dir_list";

            if ($this->topSite->checkTopSites($this->link)) {
                $key = 'comment';
            }
        
            if(preg_match('#html$#i',$this->link)){
                $key = 'html';
            }
        } elseif ($this->mainSource == 'username') {
            $key = 'dir_list';

        } elseif ($this->isProfile) {
            $key = 'profile';

        } else {
            $key = $this->pageType->checkPageType($this->mainSource, $this->link);
        }

        return $key;
    }

}

?>