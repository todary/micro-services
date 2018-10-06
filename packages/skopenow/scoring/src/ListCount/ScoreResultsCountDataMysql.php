<?php 

/**
 * ScoreResultsCountDataMysql
 * 
 * PHP version 7.0
 * 
 * @category Class
 * @package  Score
 * @author   Queen tech <info@queentechsolutions.net>
 * @license  license http://www.queentechsolutions.net/
 * @link     http://www.queentechsolutions.net/
 */
namespace Skopenow\Scoring\ListCount;

/**
 * ScoreResultsCountDataMysql
 * 
 * @category Class
 * @package  Score
 * @author   Queen tech <info@queentechsolutions.net>
 * @license  license http://www.queentechsolutions.net/
 * @link     http://www.queentechsolutions.net/
 */
class ScoreResultsCountDataMysql implements ScoreResultsCountDataInterface
{
    /**
     * [getScoreResultsCount description]
     * 
     * @return array [description]
     */
    public function getScoreResultsCount()
    {
        return [
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
}

?>