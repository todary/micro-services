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
class EmailDatapoint extends Datapoint
{
    public $isQuable = false;

    const MAIL_SERVERS =
        '/@.*(who|customer|service|domain|whois|registry|proxy|private|godaddy|registrar|gandi|hostmaster)/i';

    public function addEntry($email)
    {
        Log::info("add email start\n");

        $emailAddress = trim($email->email);
        $is_email = filter_var($emailAddress, FILTER_VALIDATE_EMAIL);
        if (!$is_email || !$emailAddress || preg_match(self::MAIL_SERVERS, $emailAddress) == true) {
            Log::warning("Invalid email $emailAddress, ignored.");
            return false;
        }
        $emailAddress = $this->formatData('emails', $emailAddress);

        $data['key'] = md5(substr($emailAddress, 0, strrpos($emailAddress, '.')));
        $data['emailAddress'] = $emailAddress;
        if ($this->resultId != 0) {
            $data['assoc_profile'] = "res_$this->resultId";
            $data['res'] = $this->resultId;
        }
        $data['parent_comb'] = $this->combinationId;

        $this->addDataPoint('emails', $data, $email);

        $debugMsg = "Person: {$this->report['id']}
                    Combination: $this->combinationId
                    result id: $this->resultId,
                    saving emails data from whois:
                    email : $emailAddress \n";
        Log::debug($debugMsg);
        Log::info("add email end\n");
    }
}
