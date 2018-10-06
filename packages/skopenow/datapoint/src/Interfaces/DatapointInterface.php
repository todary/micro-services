<?php
/**
 * Datapoint interface
 *
 * PHP version 7.0
 *
 * @category Micro_Services-phase_2
 * @package  Datapoint
 * @author   Mahmoud AboElnouR <aboelnoor@queentecksolutions.com>
 * @license  QueenTechSolutions, Inc.
 * @link     http://www.queentecksolutions.com
 **/
namespace Skopenow\Datapoint\Interfaces;

use App\Libraries\DBCriteriaInterface;

/**
 * Datapoint interface
 *
 * @category Micro_Services-phase_2
 * @package  Datapoint
 * @author   Mahmoud AboElnouR <aboelnoor@queentecksolutions.com>
 * @license  QueenTechSolutions, Inc.
 * @link     http://www.queentecksolutions.com
 **/
interface DatapointInterface
{
    /**
     * Datapoint constructor
     *
     * @param \Iterator $input input to validate
     *
     * @return type
     */
    public function __construct(array $data, array $person, array $combination = null, array $extras = []);

    /**
     * Validate input function implementation
     *
     * @return \Iterator
     */
    public function add();
    public function loadData(DBCriteriaInterface $criteria);
    public function progressData(string $key, string $dbkey, array $val, array $rescanSetting = []);
    public function loadProgress(int $id, bool $use_cache = true, string $type = null, bool $as_object = false);
    public function updateProgress($id, $key, $val, $flag = false);
}
