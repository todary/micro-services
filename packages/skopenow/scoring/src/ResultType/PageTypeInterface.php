<?php

/**
 * PageTypeInterface
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
 * PageTypeInterface
 * 
 * @category Interface
 * @package  Scoring
 * @author   Queen tech <info@queentechsolutions.net>
 * @license  license http://www.queentechsolutions.net/
 * @link     http://www.queentechsolutions.net/
 */
interface PageTypeInterface
{

	/**
	 * [checkPageType description]
	 * 
	 * @param string $mainSource [description]
	 * @param string $link       [description]
	 * 
	 * @return string             [description]
	 */
    public function checkPageType(string $mainSource, string $link);
}

?>