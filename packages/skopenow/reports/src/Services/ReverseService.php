<?php
namespace Skopenow\Reports\Services;

/**
*
*/
class ReverseService
{
    public function get($report)
    {
        $reveseParams = [];
        $reverseParams['first_name'] = $report->first_name;
        $reverseParams['middle_name'] = $report->middle_name;
        $reverseParams['last_name'] = $report->last_name;
        $reverseParams['address'] = $report->address;
        $reverseParams['phone'] = $report->phone;
        $reverseParams['username'] = $report->username;
        $reverseParams['email'] = $report->email;

        require_once(__DIR__.'/../../../../../../protected/commands/ReversCommand.php');
        return \ReversCommand::actionRunReverse($reveseParams, $report->id);
    }
}
