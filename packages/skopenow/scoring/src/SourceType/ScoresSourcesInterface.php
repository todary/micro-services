<?php

/**
 * ScoresSourcesInterface
 *
 * PHP version 7.0
 * 
 * @category Interface
 * @package  Scoring
 * @author   Queen tech <info@queentechsolutions.net>
 * @license  license http://www.queentechsolutions.net/
 * @link     http://www.queentechsolutions.net/
 */
namespace Skopenow\Scoring\SourceType;

/**
 * ScoresSourcesInterface
 * 
 * @category Interface
 * @package  Scoring
 * @author   Queen tech <info@queentechsolutions.net>
 * @license  license http://www.queentechsolutions.net/
 * @link     http://www.queentechsolutions.net/
 */
interface ScoresSourcesInterface
{
    /**
     * [getScoresSources description]
     * 
     * @return array [description]
     */
    public function getScoresSources();
}