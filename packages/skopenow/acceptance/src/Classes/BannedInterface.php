<?php

/**
 * BannedInterface
 *
 * PHP version 7.0
 * 
 * @category interface
 * @package  Acceptance
 * @author   Queen tech <info@queentechsolutions.net>
 * @license  license http://www.queentechsolutions.net/
 * @link     http://www.queentechsolutions.net/
 */
namespace Skopenow\Acceptance\Classes;

/**
 * BannedInterface
 * 
 * @category interface
 * @package  Acceptance
 * @author   Queen tech <info@queentechsolutions.net>
 * @license  license http://www.queentechsolutions.net/
 * @link     http://www.queentechsolutions.net/
 */
interface BannedInterface
{
	/**
	 * [getUserBanned description]
	 * 
	 * @return array [description]
	 */
	public function getUserBanned();

	/**
	 * [getBannedDomains description]
	 * 
	 * @return array [description]
	 */
	public function getBannedDomains();

}