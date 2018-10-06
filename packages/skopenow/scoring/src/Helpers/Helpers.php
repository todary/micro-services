<?php
namespace Skopenow\Scoring\Helpers;

use Skopenow\Scoring\ResultType\ResultTypeScore;
use Skopenow\Scoring\ResultType\KeyScore;
use Skopenow\Scoring\TopSites\TopSitesFile;
use Skopenow\Scoring\ListCount\ListCountScore;
use Skopenow\Scoring\SourceType\SourceTypeScore;
use Skopenow\Scoring\SourceType\ScoresSources;
use Skopenow\Scoring\Identity\IdentityScore;
use Skopenow\Scoring\ResultType\PageType;
use App\Models\Result;

trait Helpers
{
    /**
     * 
     * @return $this
     */
    protected function getListScore()
    {
        $listScore = 0 ;
        if(!empty($this->matchingData['resultsCount'])){
            $ComparingScores = config("score_results_count");
            $listCount = new ListCountScore($ComparingScores);
            $listScore = $listCount->getScore($this->matchingData['resultsCount']);
        }
        return $listScore ;
    }
    
    protected function getSourceTypeScore()
    {
        $sourceTypeScore = 0 ;
        if(isset($this->matchingData['link'],$this->matchingData['isProfile'],
                $this->matchingData['source'],$this->matchingData['main_source'])){
            $comparingScores = config("score_sources");
            $topSites = new TopSitesFile();
            $sourceTypeObj = new SourceTypeScore($comparingScores, $topSites);
            $sourceTypeScore = $sourceTypeObj->getScore($this->matchingData['source'], $this->matchingData['main_source'], $this->matchingData['link'], $this->matchingData['isProfile']);
        }
        return $sourceTypeScore ;
    }
    
    protected function getResultTypeScore()
    {
        $resultTypeScore = 0 ;
        
        $keyScore = new KeyScore();
        $pageType = new PageType();
        $topSites = new TopSitesFile();
        $resultType = new ResultTypeScore($keyScore, $topSites, $pageType);
        $resultTypeScore = $resultType->getScore($this->matchingData);
        return $resultTypeScore ;
    }
    
    protected function getIdentityScore()
    {
        $identityScore = 0 ;
        if(!empty($this->matchingData['matchingData'])){
            $identityComparingScores = config("scoreIdentities");
            $identityScoreObj = new IdentityScore($identityComparingScores);
            if (!empty($this->matchingData['resultsCount']) && $this->matchingData['resultsCount'] == 1) {
                $identityScoreObj->setOnlyOne(true);
            }
            if(!empty($this->matchingData['isRelative']) && $this->matchingData['isRelative']) {
                $identityScoreObj->setIsRelative(true);
            }
            if (!empty($this->matchingData['identitiesShouldHave']) && $this->matchingData['identitiesShouldHave']) {
                $identityScoreObj->setIdentitiesShouldHave($this->matchingData['identitiesShouldHave']);
            }
            if (!empty($this->matchingData['additionalIdentifiers'])) {
                $identityScoreObj->setAdditionalIdentifiers($this->matchingData['additionalIdentifiers']);
            }
            $scoreData = $identityScoreObj->init($this->matchingData['matchingData']) ;
            $identityScore = $scoreData['score'];
            $this->identities = $scoreData['identities'];
            $this->identifiers = $scoreData['identifiers'];
            $this->flags = $scoreData['flags'];
            $this->matchingFlags = $scoreData['matching_flags'];
            $this->inputFlags = $scoreData['input_flags'];
            $this->extraFlags = $scoreData['extra_flags'];
        }
        
        return $identityScore ;
    }

    protected function getOldScoreFromCache($resultUrl)
    {
        $urlInfo = loadService("urlInfo");
        $url = $urlInfo->prepareContent($resultUrl);
        $report_id = config("state.report_id");
        $key = md5($report_id.$url);
        $oldScore = \Cache::get($key);
        return $oldScore;
    }

    protected function getOldScoreFromDB ($resultUrl)
    {
        $urlInfo = loadService("urlInfo");
        $url = $urlInfo->prepareContent($resultUrl);
        $report_id = config("state.report_id");
        $result = Result::where("report_id",$report_id)->where("unique_content",$url)->first();
        $oldScore = [];
        if(!empty($result)){
            $oldScore = [
                "listCountScore"    =>  $result->score_result_count ,
                "resultTypeScore"   =>  $result->score_source_type,
                "sourceTypeScore"   =>  $result->score_source,
                "identities"        =>  array_values(json_decode($result->score_identity, true)??[]),
                "identifiers"       =>  array_values(json_decode($result->identifiers, true)??[]),
                "flags"             =>  $result->flags,
                "matching_flags"    =>  $result->matching_flags,
                "input_flags"             =>  $result->input_flags,
                "extra_flags"             =>  $result->extra_flags,
            ];
        }
        return $oldScore;
    }
    
    protected function formatScoreData(self $obj) : array
    {
        $scoreData = array();
        $scoreData['listCountScore'] = $obj->listCountScore ;
        $scoreData['resultTypeScore'] = $obj->resultTypeScore ;
        $scoreData['sourceTypeScore'] = $obj->sourceTypeScore ;
        $scoreData['identityScore'] = $obj->identityScore ;
        $scoreData['identities'] = array_values($obj->identities);
        $scoreData['identifiers'] = array_values(array_filter(array_unique($obj->identifiers)));
        $scoreData['flags'] = $obj->flags ;
        $scoreData['matching_flags'] = $obj->matchingFlags ;
        $scoreData['input_flags'] = $obj->inputFlags ;
        $scoreData['extra_flags'] = $obj->extraFlags ;
        
        ## calculate Final Score ..
        $finalScore = $this->calculateFinalScore($obj) ;
        $scoreData['finalScore'] = $finalScore ;

        return $scoreData ;
    }
        
    protected function setToCache($scoreData, $link): bool
    {
        $urlInfo = loadService("urlInfo");
        $url = $urlInfo->prepareContent($link);
        $report_id = config("state.report_id");
        $key = md5($report_id.$url);
        \Cache::put($key , $scoreData, 120);
        return true;
    }
}