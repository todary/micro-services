<?php

/**
 * KeyScore
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

/**
 * KeyScore
 * 
 * @category Class
 * @package  Scoring
 * @author   Queen tech <info@queentechsolutions.net>
 * @license  license http://www.queentechsolutions.net/
 * @link     http://www.queentechsolutions.net/
 */
class KeyScore implements KeyScoreInterface
{
    /**
     * [getScoreFromKey description]
     * 
     * @param string $key [description]
     * 
     * @return float      [description]
     */
    public function getScoreFromKey(string $key)
    {
        $score = 0;
        $score_types = config("score_type");
		$score_types = $score_types;
		
		foreach ($score_types as $scoreType){
			if(!empty($scoreType->type) && $scoreType->type == $key){
				$score = $scoreType->score;
				break;
			}
		}

        return $score;
    }
}

?>