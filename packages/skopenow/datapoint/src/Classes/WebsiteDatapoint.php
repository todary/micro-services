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

use App\Models\EmailBlacklist;
use Illuminate\Support\Facades\Cache;
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
class WebsiteDatapoint extends Datapoint
{
    public $isQuable = true;

    const CACHE_TIME = 60;
    public function addEntry($input)
    {
        $combinationId = isset($this->combinationId) ? $this->combinationId : null;

        if (!count($input) || !$this->isValidInputs(['url' => (array) $input->url])) {
            //TODO:: To be changed //log service
            $debugMsg = "Person: {$this->report['id']} Combination: $combinationId result id: $this->resultId, could not save  websites  data : domain : $input->url \n";
            Log::warning($debugMsg);
            return;
        }

        $is_blacklisted = Cache::remember(
            "email_blacklist_$input->url",
            self::CACHE_TIME,
            function () use ($input) {
                return EmailBlacklist::where('domain', $input->url)->first();
            }
        );
        if ($is_blacklisted) {
            Log::warning("$input->url blacklist skipped.");
            return;
        }

        $data['url'] = $input->url;
        $data['assoc_profile'] = "res_$this->resultId";
        $data['res'] = $this->resultId;
        $data['parent_comb'] = $this->combinationId;
        $data['key'] = md5($input->url);

        $this->addDataPoint('websites', $data, $input);
        $debugMsg = "Person: {$this->report['id']} Combination: $combinationId result id: $this->resultId, saving websites  data : domain : $input->url \n";
        Log::debug($debugMsg);
    }
}
