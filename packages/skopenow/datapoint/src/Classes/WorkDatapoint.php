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

use App\Libraries\BridgeCriteria;
use Illuminate\Support\Facades\Log;
use Search\Helpers\Bridges\ResultsBridge;

/**
 * Abstract Datapoint class
 *
 * @category Micro_Services-phase_2
 * @package  Datapoint
 * @author   Mahmoud AboElnouR <aboelnoor@queentecksolutions.com>
 * @license  QueenTechSolutions, Inc.
 * @link     http://www.queentecksolutions.com
 **/
class WorkDatapoint extends Datapoint
{
    public $isQuable = true;

    public function addEntry($input)
    {
        Log::info('add work start');
        $status = false;

        $data['company'] = $input->company ?? '';
        $data['title'] = $input->title ?? '';
        $data['start'] = $input->start ?? '';
        $data['end'] = $input->end ?? '';
        if ($data['company'] || $data['title']) {
            $data['key'] = md5($data['title'] . $data['company']);
            $data['assoc_profile'] = "res_$this->resultId";
            $data['res'] = $this->resultId;
            $data['parent_comb'] = $this->combinationId;

            $this->addDataPoint('work_experiences', $data, $input);

            if (filter_var($data['company'], FILTER_VALIDATE_URL)) {
                $bridge_criteria = new BridgeCriteria();
                $bridge_criteria->compare('id', $this->resultId);
                $result_bridge = new ResultsBridge($this->report);
                $result = $result_bridge->get($bridge_criteria);
                $type = isset($this->type) ? $this->type : 'linkedin';
                $this->websitesFromWorkExperience($data['company'], $type, $result);
            }
        }
        $status = true;
        Log::info('add work end');

        return $status;
    }

    public function websitesFromWorkExperience($website_url, $source, $result)
    {
        $resultId = 0;
        if ($resultId != 0) {
            $data['assoc_profile'] = 'res_' . $result['id'];
            $data['res'] = $result['id'];
            $data['parent_comb'] = (isset($this->combinationId)) ? $this->combinationId : $result['combination_id'];
            $data['url'] = $website_url;

            $data['url'] = rtrim($data['url'], 'http://');
            $data['url'] = rtrim($data['url'], 'https://');
            $data['url'] = rtrim($data['url'], 'www.');
            $data['url'] = ltrim($data['url'], '/');
            $data['key'] = md5($data['url']);

            $emails = getEmailsByWhoIs($data['url']);

            $this->addEmail($emails, ['admin', 'registrant'], $result['id']);
            getEmailFromWhoIsResult($result);
        }
    }

    private function addEmail(array $emails, array $types, int $resultId)
    {
        foreach ($types as $type) {
            if (!empty($emails[$type . 'Email'])) {
                $inUs = checkWebSiteCountry($emails[$type . 'Email'], $this->city, $this->report, $this->combination);

                if ($this->unique_name || $inUs) {
                    \SearchApis::add_emails($emails[$type . 'Email'], $this->report, $this->combination, $resultId);
                }
            }
        }
    }
}
