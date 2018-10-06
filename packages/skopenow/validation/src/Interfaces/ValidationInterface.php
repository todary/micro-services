<?php
/**
 * Validation interface
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
 * Validation interface
 *
 * @category Micro_Services-phase_1
 * @package  Validation
 * @author   Mahmoud AboElnouR <aboelnoor@queentecksolutions.com>
 * @license  QueenTechSolutions, Inc.
 * @link     http://www.queentecksolutions.com
 **/
interface ValidationInterface
{
    /**
     * Validation constructor
     *
     * @param \Iterator $input input to validate
     *
     * @return type
     */
    public function __construct(\Iterator $input);

    /**
     * Validate input function implementation
     *
     * @return \Iterator
     */
    public function validate(): \Iterator;
}
