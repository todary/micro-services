<?php

/**
 * ScoreResultCount Class
 *
 * PHP version 7.0
 * 
 * @category Class
 * @package  Scoring
 * @author   Queen tech <info@queentechsolutions.net>
 * @license  license http://www.queentechsolutions.net/
 * @link     http://www.queentechsolutions.net/
 */
namespace Skopenow\Scoring\ListCount;

use Skopenow\Scoring\Helpers\FormatFormula;

/**
 * ScoreResultCount Class
 * 
 * @category Class
 * @package  Scoring
 * @author   Queen tech <info@queentechsolutions.net>
 * @license  license http://www.queentechsolutions.net/
 * @link     http://www.queentechsolutions.net/
 */
class ListCountScore
{

    use FormatFormula;

	protected $comparingScores ;
    /**
     * [__construct description]
     * 
     * @param int $countResults [description]
     */
    public function __construct(array $comparingScores)
    {
        $this->comparingScores = $comparingScores;
    }
    
    /**
     * [getScore description]
     * 
     * @param ScoreResultsCountDataInterface $dataObj [description]
     * 
     * @return float                                   [description]
     */
    public function getScore(int $countResults)
    {

        $returnScore = 1;
        // foreach ($this->comparingScores as $key => $score) {
        //     if ($countResults >= $score->from_num
        //         && $countResults < $score->to_num
        //     ) {
        //         $returnScore = $score->score;
        //     }
        // }

        if ($countResults > 1) {
            $formula = '1/log({n},1.8)';
            $formula = str_replace("{n}", $countResults, $formula);
            $returnScore = static::validateFormula($formula);
            // $returnScore = 
        }
        
        return $returnScore;
    }

}
?>