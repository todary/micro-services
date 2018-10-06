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
class RelativesDatapoint extends Datapoint
{
    public $isQuable = false;

    public function addEntry($relatives)
    {
        Log::info('add relatives start');
        $debugMsg = "Person: {$this->report['id']},
                    Combination: $this->combinationId,
                    Saving relative: {$relatives['fn']} {$relatives['mn']} {$relatives['ln']} \n";

        Log::debug($debugMsg);

        $url = '/home/save_relative?' . http_build_query(array(
            'fn' => $relatives->first_name,
            'ln' => $relatives->last_name,
            'mn' => $relatives->middle_name,
            'location' => $relatives->city,
            'address' => $relatives->address ?? '',
            'zip' => $relatives->zip ?? '',
        ));

        // send relative
        $rt = array(
            'key' => md5(strtolower($relatives->first_name)),
            'assoc_profile' => $this->resultId ? "res_$this->resultId" :
            ($this->combinationId ? "comb_$this->combinationId" : 'comb_base'),

            'name' => honorificNicknames($relatives->full_name),
            'location' => getStateName($relatives->city),
            'parent_comb' => $this->combinationId,
            'url' => $url,
            'res' => $this->resultId ?? null,
        );

        $result = $this->addDataPoint('relatives', $rt, $relatives);
        Log::info('add relatives end');
    }
}
