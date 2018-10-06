<?php
/**
 * Abstract Datapoint code
 *
 * PHP version 7.0
 *
 * @category Micro_Services-phase_2
 * @package  Datapoint
 * @author   Mahmoud AboElnouR <aboelnoor@queentecksolutions.com>
 * @license  QueenTechSolutions, Inc.
 * @link     http://www.queentecksolutions.com
 **/
namespace Skopenow\Datapoint\Classes;

use Illuminate\Support\Facades\Log;

/**
 * Abstract Datapoint class
 *
 * @category Micro_Services-phase_2
 * @package  Datapoint
 * @author   Mahmoud AboElnouR <aboelnoor@queentecksolutions.com>
 * @license  QueenTechSolutions, Inc.
 * @link     http://www.queentecksolutions.com
 **/
class AgeDatapoint extends Datapoint
{
    public $isQuable = false;

    public function addEntry($age)
    {
        if ($age['age'] == '' || $age['age'] < 0) {
            Log::warning("Invalid age $age, ignored.");
            return false;
        }

        $results['key'] = md5($this->resultId . $age['age']);
        $results['age'] = $age['age'];
        $results['assoc_profile'] = "res_$this->resultId";
        $results['res'] = $this->resultId;
        $results['parent_comb'] = $this->combinationId;

        $this->addDataPoint('age', $results, $age);
    }
}
