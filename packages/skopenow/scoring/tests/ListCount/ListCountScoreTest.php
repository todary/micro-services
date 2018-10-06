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
use Skopenow\Scoring\ListCount\ListCountScore;
use Skopenow\Scoring\ListCount\ScoreResultsCountDataMysql;

/**
 * ScoreResultCountTest
 * 
 * @category PHPUnit
 * @package  Score
 * @author   Queen tech <info@queentechsolutions.net>
 * @license  license http://www.queentechsolutions.net/
 * @link     http://www.queentechsolutions.net/
 */
class ListCountScoreTest extends TestCase
{
    /**
     * [$scoreDataMoc description]
     * 
     * @var MockBuilder
     */
    protected $scoreDataMoc;

    /**
     * [setup description]
     * 
     * @return void
     */
	
	public $comparingScores = array();

	
	public function setup()
	{
		$data = array(
			(object) array('id' => '1','from_num' => NULL,'to_num' => '10','score' => '0'),
			(object) array('id' => '2','from_num' => '10','to_num' => '35','score' => '0.25'),
			(object) array('id' => '3','from_num' => '35','to_num' => '150','score' => '1'),
			(object) array('id' => '4','from_num' => '150','to_num' => '250','score' => '0.25'),
			(object) array('id' => '5','from_num' => '200','to_num' => '300','score' => '0')
		);
		$this->comparingScores = $data;
	}
    /**
     * [testScoreInrang description]
     * 
     * @return void
     */
    public function testScoreInrang()
    {
		
		
        $testData = 15;

        $scoreResultCount = new ListCountScore($this->comparingScores);
        $this->assertEquals(0.25, $scoreResultCount->getScore($testData));
    }

    /**
     * [testScoreNotInrang description]
     * 
     * @return void
     */
    public function testScoreNotInrang()
    {

        $testData = 100;

        $scoreResultCount = new ListCountScore($this->comparingScores);
	    $this->assertEquals(1, $scoreResultCount->getScore($testData));
    }
}