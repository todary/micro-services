<?php

/**
 * SourceTypeScoreTest
 *
 * PHP version 7.0
 * 
 * @category PHPUnit
 * @package  Score
 * @author   Queen tech <info@queentechsolutions.net>
 * @license  license http://www.queentechsolutions.net/
 * @link     http://www.queentechsolutions.net/
 */
use Skopenow\Scoring\SourceType\SourceTypeScore;
use Skopenow\Scoring\SourceType\ScoresSources;
use Skopenow\Scoring\TopSites\TopSitesFile;

/**
 * SourceTypeScoreTest
 * 
 * @category PHPUnit
 * @package  Score
 * @author   Queen tech <info@queentechsolutions.net>
 * @license  license http://www.queentechsolutions.net/
 * @link     http://www.queentechsolutions.net/
 */
class SourceTypeScoreTest extends TestCase
{
    /**
     * [$scoresSourcesMoc description]
     * 
     * @var MockBuilder
     */
    protected $scoresSourcesMoc;

    /**
     * [$topSitesMoc description]
     * 
     * @var MockBuilder
     */
    protected $topSitesMoc;

    /**
     * [setup description]
     * 
     * @return void
     */
    public function setup()
    {
        $data = [
            (object) ["source"=>"top_sites", "score" => 18],
            (object) ["source"=>"facebook", "score" => 20],
            (object) ["source"=>"lookup_list", "score" => 23],
            (object) ["source"=>"google_not_listed", "score" => 4],
            (object) ["source"=>"profile_not_listed", "score" => 13]
        ];

		$this->scoresSourcesMoc = $data ;
//        $this->scoresSourcesMoc = $this->getMockBuilder(ScoresSources::class)->getMock();
//        $this->scoresSourcesMoc->method("getScoresSources")->willReturn($data);

        $this->topSitesMoc = $this->getMockBuilder(TopSitesFile::class)->getMock();
        $this->topSitesMoc->method("checkTopSites")->willReturn(false);
    }

    public function testGetScoreEmptyMainSource()
    {
        $sourceTypeScore = new SourceTypeScore(
            $this->scoresSourcesMoc, 
            $this->topSitesMoc
        );

        $this->assertEquals(
            0,
            $sourceTypeScore->getScore(
                "",
            "",
            "facebook.com/kh.",
            false
            )
        );
    }

    public function testGetScoreUsernameSource()
    {
        $sourceTypeScore = new SourceTypeScore(
            $this->scoresSourcesMoc, 
            $this->topSitesMoc
        );

        $this->assertEquals(
            23,
            $sourceTypeScore->getScore(
                "username",
                "facebook",
                "facebook.com/kh.",
                false
            )
        );
    }

    public function testGetScoreGoogleSource()
    {
        $sourceTypeScore = new SourceTypeScore(
            $this->scoresSourcesMoc, 
            $this->topSitesMoc
        );

        $this->assertEquals(
            4,
            $sourceTypeScore->getScore(
                "",
                "google",
                "facebook.com/kh.",
                false
            )
        );
    }

    public function testGetScoreIsProfile()
    {
        $sourceTypeScore = new SourceTypeScore(
            $this->scoresSourcesMoc, 
            $this->topSitesMoc
        );

        $this->assertEquals(
            13,
            $sourceTypeScore->getScore(
                "",
                "facebooksss",
                "sss",
                true
            )
        );
    }
    
    public function testGetScoreTopsites()
    {
        $topSitesMoc = $this->getMockBuilder(TopSitesFile::class)->getMock();
        $topSitesMoc->method("checkTopSites")->willReturn(true);

        $sourceTypeScore = new SourceTypeScore(
            $this->scoresSourcesMoc, 
            $topSitesMoc
        );

        $this->assertEquals(
            18,
            $sourceTypeScore->getScore(
                "",
                "facebooksss",
                "facebook",
                false
            )
        );
    }

    public function testGetScoreNothing()
    {
        $sourceTypeScore = new SourceTypeScore(
            $this->scoresSourcesMoc, 
            $this->topSitesMoc
        );
        
        $this->assertEquals(
            0,
            $sourceTypeScore->getScore(
                "",
                "facebooksss",
                "",
                false
            )
        );
    }

}