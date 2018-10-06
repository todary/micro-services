<?php

/**
 * Entry Point for the the scoring package .
 * $data[
 *  matchingData => [
 *      name    => [
 *      ],
 *      location => [
 *      ],
 *  ]
 *  resultsCount => int ,
 *  main_source  => string , 
 *  source  => string , 
 *  isProfile   => bool , 
 *  isRelative  => bool ,
 * ]
 * 
 * On Rescoring : 
 * $matchingData[
 *  matchingData => [
 *      name    => [
 *      ],
 *      location => [
 *      ],
 *  ]
 *  resultsCount => int ,
 *  mainSource   => string , 
 *  source  => string , 
 *  isProfile   => bool , 
 *  isRelative  => bool ,
 *   
 * ]
 * 
 * $oldScore [
 *  identityScore => float ,
 *  listCountScore => float ,
 *  sourceTypeScore => float ,
 *  resultTypeScore => float , 
 *  identities => [
 *      fn ,
 *      ln ,
 *      em ,
 *  ]
 * ]
 * 
 * @author Ahmed Samir <ahmedsamir732@gmail.com>
 */
namespace Skopenow\Scoring ;

use Skopenow\Scoring\ResultType\ResultTypeScore;
use Skopenow\Scoring\ResultType\KeyScore;
use Skopenow\Scoring\TopSites\TopSitesFile;
use Skopenow\Scoring\SourceType\SourceTypeScore;
use Skopenow\Scoring\SourceType\ScoresSources;
use Skopenow\Scoring\ResultType\PageType;
use App\Models\Result;
use Skopenow\Scoring\Helpers\Helpers;


class EntryPoint 
{

    use Helpers;

    protected $matchingData = array() ;
    
    protected $listCountScore = 0 ;
    
    protected $sourceTypeScore = 0 ;
    
    protected $resultTypeScore = 0 ;
    
    protected $identityScore = 0 ;
    
    protected $identities = array() ;
    
    protected $identifiers = array() ;
    
    protected $flags = 0 ;
    protected $matchingFlags = 0 ;
    protected $inputFlags = 0 ;
    protected $extraFlags = 0 ;


    public function __construct() 
    {
        require __DIR__.'/config/scoring.php';
    }

    public function init(array $matchingData) : array
    {
        $this->identities = array() ;
        $this->identifiers = array() ;
        $this->flags = 0 ;

        $this->matchingData = $matchingData;
        $this->listCountScore = $this->getListScore();
        $this->sourceTypeScore = $this->getSourceTypeScore();
        $this->resultTypeScore = $this->getResultTypeScore();
        $this->identityScore = $this->getIdentityScore();

        $scoreData = $this->formatScoreData($this);
        
        // $this->setToCache($scoreData, $this->matchingData['link']);
        
        return $scoreData ;
    }
    
    
    
    public function rescoree($matchingData , $oldScoreData , $matchFirst = true)
    {
        try{
            if($matchFirst)
                $newScoreData = $this->init($matchingData);
            else
                $newScoreData = $matchingData ;
            
            $rescoreObj = new Rescore\Rescoring();
            $data = $rescoreObj->resocre($newScoreData, $oldScoreData);
            return $data ;
        } catch (\Exception $ex) {
            throw $ex;
        }       
        
    }

    public function rescore(array $newScore, $resultUrl, bool $matchFirst = true)
    {
        try {
            if ($matchFirst)
                $newScore = $this->init($newScore);
            $oldScore = [];//$this->getOldScoreFromCache($resultUrl);
            if (!$oldScore) {
                $oldScore = $this->getOldScoreFromDB($resultUrl);
            }
            $score = $newScore;
            if(!empty($oldScore)){
                $rescoreObj = new Rescore\Rescoring();
                $score = $rescoreObj->resocre($newScore, $oldScore);
            }
            // $this->setToCache($score, $resultUrl);
            return $score;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function getFlags(array $matchingData)
    {
        $this->matchingData = $matchingData;
        $identityScore = $this->getIdentityScore();

        return $this->flags;
    }

    public function calculateFinalScore(EntryPoint $obj) : float
    {
        $finalScoreObj = new FinalScore\FinalScore(config("score_single_result"));
        
        $finalScore = $finalScoreObj->calculate(
                $obj->identityScore, 
                $obj->listCountScore, 
                $obj->sourceTypeScore, 
                $obj->resultTypeScore
        );
        
        return $finalScore ;
    }
    
}

