<?php

/**
 * KeyScoreInterface
 *
 * PHP version 7.0
 * 
 * @category Interface
 * @package  Scoring
 * @author   Queen tech <info@queentechsolutions.net>
 * @license  license http://www.queentechsolutions.net/
 * @link     http://www.queentechsolutions.net/
 */
namespace Skopenow\Scoring\ResultType;

/**
 * KeyScoreInterface
 * 
 * @category Interface
 * @package  Scoring
 * @author   Queen tech <info@queentechsolutions.net>
 * @license  license http://www.queentechsolutions.net/
 * @link     http://www.queentechsolutions.net/
 */
interface KeyScoreInterface
{
    /**
     * [getScoreFromKey description]
     * 
     * @param string $key [description]
     * 
     * @return float      [description]
     */
    public function getScoreFromKey(string $key);
}

?>