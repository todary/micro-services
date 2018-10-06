<?php
/**
 * Location Validation Interface
 *
 * PHP version 7.0
 *
 * @category Micro_Services-phase_1
 * @package  Validation
 * @author   Mahmoud AboElnouR <aboelnoor@queentecksolutions.com>
 * @license  QueenTechSolutions, Inc.
 * @link     http://www.queentecksolutions.com
 **/
namespace Skopenow\Validation\Interfaces;

/**
 * Location Validation Interface
 *
 * @category Micro_Services-phase_1
 * @package  Validation
 * @author   Mahmoud AboElnouR <aboelnoor@queentecksolutions.com>
 * @license  QueenTechSolutions, Inc.
 * @link     http://www.queentecksolutions.com
 **/
interface LocationValidationInterface extends ValidationInterface
{
    /**
     * Comma seperate location city
     *
     * @param string $location location to seperate comma
     *
     * @return string
     */
    public function commaLocation(string &$location): string;

    /**
     * Get state from the location
     *
     * @param string $location location to get state
     *
     * @return string
     */
    public function getState(string $location): string;
}
