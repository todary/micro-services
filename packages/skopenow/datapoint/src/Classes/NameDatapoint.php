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
class NameDatapoint extends Datapoint
{
    public $isQuable = false;

    public function addEntry($name)
    {
        $input = ucwords($name->full_name);

        $results['key'] = md5($this->resultId . $input);
        $results['name'] = htmlspecialchars_decode($this->formatData('names', $input), ENT_QUOTES);

        if (isset($name['profile_image'])) {
            $results['profile_image'] = $name['profile_image'];
        }

        if (isset($name['source'])) {
            $results['source'] = $name['source'];
        }

        if (!empty($this->other_name)) {
            $results['other_name'] = $this->other_name;
        }

        if (isset($name['extractedFromProfile'])) {
            $results['extractedFromProfile'] = $name['extractedFromProfile'];
        }

        $results['assoc_profile'] = "res_$this->resultId";
        $results['res'] = $this->resultId;
        $results['parent_comb'] = $this->combinationId;

        $this->addDataPoint('names', $results, $name);
    }
}
