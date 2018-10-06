<?php

/**
 * SourceTypeScore
 *
 * PHP version 7.0
 * 
 * @category Class
 * @package  Scoring
 * @author   Queen tech <info@queentechsolutions.net>
 * @license  license http://www.queentechsolutions.net/
 * @link     http://www.queentechsolutions.net/
 */
namespace Skopenow\Scoring\SourceType;

use Skopenow\Scoring\TopSites\TopSitesInterface;

/**
 * SourceTypeScore
 * 
 * @category Class
 * @package  Scoring
 * @author   Queen tech <info@queentechsolutions.net>
 * @license  license http://www.queentechsolutions.net/
 * @link     http://www.queentechsolutions.net/
 */
class SourceTypeScore
{
    /**
     * [$source description]
     * 
     * @var string
     */
    protected $source;

    /**
     * [$mainSource description]
     * 
     * @var string
     */
    protected $mainSource;

    /**
     * [$link description]
     * 
     * @var string
     */
    protected $link;

    /**
     * [$isProfile description]
     * 
     * @var string
     */
    protected $isProfile;

    /**
     * [$scoresSources description]
     * 
     * @var ScoresSourcesInterface
     */
    protected $scoresSources;

    /**
     * [$topSite description]
     * 
     * @var TopSitesInterface
     */
    protected $topSite;

    /**
     * [__construct description]
     * 
     * @param ScoresSourcesInterface $scoresSources [description]
     * @param TopSitesInterface      $topSite       [description]
     */
    public function __construct($scoresSources, TopSitesInterface $topSite) 
	{
        $this->scoresSources = $scoresSources;
        $this->topSite = $topSite;
    }

    /**
     * [getScore description]
     * 
     * @param string $source     [description]
     * @param string $mainSource [description]
     * @param string $link       [description]
     * @param bool   $isProfile  [description]
     * 
     * @return float             [description]
     */
    public function getScore(
        string $source, 
        string $mainSource, 
        string $link, 
        bool $isProfile
    ) 
	{
        $this->source = $source;
        $this->mainSource = $mainSource;
        $this->link = $link;
        $this->isProfile = $isProfile;

        if($this->mainSource == null){
            return  0;
        }

        $getScoresSources = $this->scoresSources;
        $getAllSources = array_flip(array_column($getScoresSources, 'source'));

        switch (true) {
            case $this->source == 'username' :
                $sourceType ='lookup_list';
                break;
            
            case $this->mainSource == 'google' :
                $sourceType ='google_not_listed';
                break;

            default:
                $sourceType = $this->mainSource;
                break;
        }

        if($this->isProfile && !array_key_exists($this->mainSource, $getAllSources)){
            $sourceType = 'profile_not_listed';
        }

        if($this->topSite->checkTopSites($this->link) 
            && !array_key_exists($mainSource, $getAllSources)
        ) {
            $sourceType = 'top_sites';
        }

        $currentSource = null;
        if (array_key_exists($sourceType, $getAllSources)) {
            $currentSource = $getScoresSources[$getAllSources[$sourceType]];
        }

        if ($currentSource) {
            return $currentSource->score;
        }

        return 0;
    }

}