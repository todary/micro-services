<?php<?php

/**
 * ScoreResultsCountDataMysqlTest
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
 * ScoreResultsCountDataMysqlTest
 * 
 * @category PHPUnit
 * @package  Score
 * @author   Queen tech <info@queentechsolutions.net>
 * @license  license http://www.queentechsolutions.net/
 * @link     http://www.queentechsolutions.net/
 */
class ScoreResultsCountDataMysqlTest extends TestCase
{
    /**
     * [$data description]
     * 
     * @var array
     */
    protected $data;

    /**
     * [setup description]
     * 
     * @return void
     */
    public function setup()
    {
        $this->data = [
            [
                "id"=>1,
                "from_num"=>null,
                "to_num"=>10,
                "score"=>0
            ],
            [
                "id"=>2,
                "from_num"=>10,
                "to_num"=>35,
                "score"=>0.25
            ],
            [
                "id"=>3,
                "from_num"=>35,
                "to_num"=>150,
                "score"=>1
            ],
            [
                "id"=>4,
                "from_num"=>150,
                "to_num"=>250,
                "score"=>0.25
            ],
            [
                "id"=>5,
                "from_num"=>250,
                "to_num"=>300,
                "score"=>0
            ]
        ];
    }

    /**
     * [testScoreResultsCount description]
     * 
     * @return void
     */
    public function testScoreResultsCount()
    {
        $scoreResultsData = new ScoreResultsCountDataMysql;
        $this->assertEquals($this->data, $scoreResultsData->getScoreResultsCount());
    }

}