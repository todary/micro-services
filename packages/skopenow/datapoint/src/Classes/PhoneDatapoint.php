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
class PhoneDatapoint extends Datapoint
{
    public $isQuable = false;

    /**
     * @param array $phones list of result person phones
     * @param object $person searched Person object
     * @param mix $combination the combination that sent to the web site
     * @param integer $key the key of the resulted person
     * these method adds phone to the progress data to dispalyed in search
     */
    public function addEntry($phone)
    {
        ## format phone numbers
        $phoneNumber = $this->formatData('phones', $phone->phone);

        if (!is_string($phoneNumber) || empty($phoneNumber) || strlen($phoneNumber) != 14) {
            Log::warning("Invalid phone $phoneNumber, ignored.");
            return false;
        }

        ## save in progress data
        $this->addDataPoint('phones', [
            'key' => md5($phoneNumber),
            'assoc_profile' => 'res_' . ($this->key ?? null),
            'number' => $phoneNumber,
            'parent_comb' => $this->combinationId,
        ], $phone);
    }
}
