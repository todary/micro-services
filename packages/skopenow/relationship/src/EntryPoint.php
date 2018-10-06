<?php
/**
 * Relationship entry point
 *
 * PHP version 7.0
 *
 * @category Micro_Services-phase_1
 * @package  Relationship
 * @author   Mahmoud AboElnouR <aboelnoor@queentecksolutions.com>
 * @license  QueenTechSolutions, Inc.
 * @link     http://www.queentecksolutions.com
 **/
namespace Skopenow\Relationship;

use Skopenow\Relationship\Classes\Insert;
use Skopenow\Relationship\Classes\Retrieve;
use Skopenow\Relationship\Classes\Update;

/**
 * Relationship entry point
 *
 * @category Micro_Services-phase_1
 * @package  Relationship
 * @author   Mahmoud AboElnouR <aboelnoor@queentecksolutions.com>
 * @license  QueenTechSolutions, Inc.
 * @link     http://www.queentecksolutions.com
 **/
class EntryPoint
{
    /**
     * EntryPoint client function
     *
     * @return void
     */
    public function retrieve(): Retrieve
    {
        return new Retrieve;
    }

    /**
     * EntryPoint client function
     *
     * @return void
     */
    public function update(): Update
    {
        return new Update($this->retrieve());
    }

    /**
     * EntryPoint client function
     *
     * @return void
     */
    public function insert(): Insert
    {
        return new Insert($this->retrieve(), $this->update());
    }
}
