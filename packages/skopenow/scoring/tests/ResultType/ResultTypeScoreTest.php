<?php

/**
 * ScoreResultCountTest
 *
 * PHP version 7.0
 * 
 * @category PHPUnit
 * @package  Score
 * @author   Queen tech <info@queentechsolutions.net>
 * @license  license http://www.queentechsolutions.net/
 * @link     http://www.queentechsolutions.net/
 */
use Skopenow\Scoring\ResultType\ResultTypeScore;
use Skopenow\Scoring\ResultType\KeyScore;
use Skopenow\Scoring\TopSites\TopSitesFile;
use Skopenow\Scoring\ResultType\PageType;

/**
 * ScoreResultCountTest
 * 
 * @category PHPUnit
 * @package  Score
 * @author   Queen tech <info@queentechsolutions.net>
 * @license  license http://www.queentechsolutions.net/
 * @link     http://www.queentechsolutions.net/
 */
class ResultTypeScoreTest extends TestCase
{
    /**
     * [$scoreDataMoc description]
     * 
     * @var MockBuilder
     */
    protected $keyScoreMoc;

    /**
     * [$topSitesMoc description]
     * 
     * @var MockBuilder
     */
    protected $topSitesMoc;

    /**
     * [$pageType description]
     * 
     * @var PageTypeInterface
     */
    protected $pageType;

    /**
     * [setup description]
     * 
     * @return void
     */
    public function setup()
    { 
        $this->keyScoreMoc = $this->getMockBuilder(KeyScore::class)->getMock();
        $this->keyScoreMoc->method("getScoreFromKey")->willReturn(0.25);

        
        $this->topSitesMoc = $this->getMockBuilder(TopSitesFile::class)->getMock();
        $this->topSitesMoc->method("checkTopSites")->willReturn(false);

        $this->pageType = new PageType();
    }

    /**
     * [testGetScoreTypeList description]
     * 
     * @return void
     */
    public function testGetScoreTypeList()
    {
        $source = [
            "mainSource"=>"",
            "source"=>"",
            "link"=>"",
            "type"=>"list",
            "isProfile"=>false,
            "isRelative"=>false
        ];

    	$resultTypeScore = new ResultTypeScore(
                $this->keyScoreMoc, 
                $this->topSitesMoc, 
                $this->pageType
            );

    	$this->assertEquals(0.25,$resultTypeScore->getScore($source));
    }

    /**
     * [testGetScoreTypeRelative description]
     * 
     * @return void [description]
     */
    public function testGetScoreTypeRelative()
    {
        $source = [
            "mainSource"=>"",
            "source"=>"",
            "link"=>"",
            "type"=>"result",
            "isProfile"=>false,
            "isRelative"=>true
        ];

        $resultTypeScore = new ResultTypeScore(
                $this->keyScoreMoc, 
                $this->topSitesMoc, 
                $this->pageType
            );

        $this->assertEquals(0.25,$resultTypeScore->getScore($source));
    }

    /**
     * [testGetScoreTypeResultGoogle description]
     * 
     * @return void
     */
    public function testGetScoreTypeResultGoogle()
    {
        $source = [
            "mainSource"=>"google",
            "source"=>"google",
            "link"=>"https://www.google.com.eg",
            "type"=>"result",
            "isProfile"=>false,
            "isRelative"=>false
        ];

        $resultTypeScore = new ResultTypeScore(
                $this->keyScoreMoc, 
                $this->topSitesMoc, 
                $this->pageType
            );

        $this->assertEquals(0.25,$resultTypeScore->getScore($source));
    }

    /**
     * [testGetScoreTypeResultGoogleTopSite description]
     * 
     * @return void
     */
    public function testGetScoreTypeResultGoogleTopSite()
    {
        $source = [
            "mainSource"=>"google",
            "source"=>"google",
            "link"=>"https://www.google.com.eg",
            "type"=>"result",
            "isProfile"=>false,
            "isRelative"=>false
        ];

        $topSitesMoc = $this->getMockBuilder(TopSitesFile::class)->getMock();
        $topSitesMoc->method("checkTopSites")->willReturn(true);

        $resultTypeScore = new ResultTypeScore(
                $this->keyScoreMoc, 
                $topSitesMoc, 
                $this->pageType
            );

        $this->assertEquals(0.25,$resultTypeScore->getScore($source));
    }

    /**
     * [testGetScoreTypeResultGoogleHtml description]
     * 
     * @return void
     */
    public function testGetScoreTypeResultGoogleHtml()
    {
        $source = [
            "mainSource"=>"google",
            "source"=>"google",
            "link"=>"html",
            "type"=>"result",
            "isProfile"=>false,
            "isRelative"=>false
        ];

        $resultTypeScore = new ResultTypeScore(
                $this->keyScoreMoc, 
                $this->topSitesMoc, 
                $this->pageType
            );

        $this->assertEquals(0.25,$resultTypeScore->getScore($source));
    }
     
     /**
      * [testGetScoreTypeResultUsername description]
      * 
      * @return void
      */
    public function testGetScoreTypeResultUsername()
    {
        $source = [
            "mainSource"=>"username",
            "source"=>"username",
            "link"=>"html",
            "type"=>"result",
            "isProfile"=>false,
            "isRelative"=>false
        ];

        $resultTypeScore = new ResultTypeScore(
                $this->keyScoreMoc, 
                $this->topSitesMoc, 
                $this->pageType
            );

        $this->assertEquals(0.25,$resultTypeScore->getScore($source));
    }

    /**
     * [testGetScoreTypeResultProfile description]
     * 
     * @return void
     */
    public function testGetScoreTypeResultProfile()
    {
        $source = [
            "mainSource"=>"facebook",
            "source"=>"facebook",
            "link"=>"https://www.facebook.com/kh.Elkhamisy",
            "type"=>"result",
            "isProfile"=>true,
            "isRelative"=>false
        ];

        $resultTypeScore = new ResultTypeScore(
                $this->keyScoreMoc, 
                $this->topSitesMoc, 
                $this->pageType
            );

        $this->assertEquals(0.25,$resultTypeScore->getScore($source));
    }
    
    /**
     * [testGetScoreTypeResultNotProfile description]
     * 
     * @return void
     */
    public function testGetScoreTypeResultNotProfile()
    {
        $source = [
            "mainSource"=>"facebook",
            "source"=>"facebook",
            "link"=>"https://www.facebook.com/posts",
            "type"=>"result",
            "isProfile"=>false,
            "isRelative"=>false
        ];

        $resultTypeScore = new ResultTypeScore(
                $this->keyScoreMoc, 
                $this->topSitesMoc, 
                $this->pageType
            );

        $this->assertEquals(0.25,$resultTypeScore->getScore($source));
    }

    /**
     * [testGetScoreTypeNull description]
     * 
     * @return void
     */
    public function testGetScoreTypeNull()
    {
        $source = [
            "mainSource"=>"facebook",
            "source"=>"facebook",
            "link"=>"https://www.facebook.com/posts",
            "type"=>"sssss",
            "isProfile"=>false,
            "isRelative"=>false
        ];

        $resultTypeScore = new ResultTypeScore(
                $this->keyScoreMoc, 
                $this->topSitesMoc, 
                $this->pageType
            );

        $this->assertEquals(0,$resultTypeScore->getScore($source));
    }
    
}
