<?php
/**
 * RelationshipBridge an aliase of friendshipBridge for dealing with the relationships database scheme .
 *
 * @package Skopenow\RelationshipBridge
 * @author Ahmed Samir <ahmed.samir@queentechsolutions.net>
 *
 */
namespace Skopenow\Relationship\Classes;

use App\Models\Report;
use Illuminate\Support\Facades\Cache;

class Operation
{
    const CACHE_TIME = 6;
    /**
     * [$person The report info object]
     * @var [object]
     */
    protected $report;
    protected $isList = false;

    /**
     * [__construct class constructor]
     * @param $report [the report info object]
     */
    public function __construct()
    {
        $report_id = config('state.report_id');
        $this->report = Cache::remember('report_' . $report_id, self::CACHE_TIME, function () use ($report_id) {
            return Report::find($report_id);
        });
    }
}
