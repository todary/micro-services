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

/**
 * Abstract Datapoint class
 *
 * @category Micro_Services-phase_2
 * @package  Datapoint
 * @author   Mahmoud AboElnouR <aboelnoor@queentecksolutions.com>
 * @license  QueenTechSolutions, Inc.
 * @link     http://www.queentecksolutions.com
 **/
class SchoolDatapoint extends Datapoint
{
    public $isQuable = false;

    public function addEntry($input)
    {
        $data['name'] = $input->name;
        $data['start'] = $input->start;
        $data['end'] = $input->end;
        $data['key'] = md5($input->name . $input->start);
        $data['assoc_profile'] = "res_$this->resultId";
        $data['res'] = $this->resultId;
        $data['parent_comb'] = $this->combinationId;

        $this->addDataPoint('schools', $data, $input);
    }
}
