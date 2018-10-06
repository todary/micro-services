<?php
/**
 * Http Requests client code
 *
 * PHP version 7.0
 *
 * @category Micro_Services-phase_1
 * @package  Combinations Service
 * @author   Mahmoud Mgady <mahmoud.magdy@queentecksolutions.com>
 * @license  QueenTechSolutions, Inc.
 * @link     http://www.queentecksolutions.com
 **/
namespace Skopenow\Combinations\Models;

use Illuminate\Database\Eloquent\Model;

/**
*
*/
class CombinationLevel extends Model
{
    protected $table = 'combination_level';

    protected $fillable = [
        'report_id', 'comb_id',
        'level_no', 'source', 'data',
        'start_minute', 'start_time', 'end_time',
        'time_taken', 'started', 'is_completed', 'trials', 'exec_time',
        'combinations_hash', 'combs_fields', 'time', 'enabled', 'log_stream', 'has_verified_profiles'
    ];

    public $timestamps = false;

    public function combination()
    {
        return $this->belongsTo(Combination::class, 'comb_id');
    }
}
