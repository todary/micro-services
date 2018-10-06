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
class Combination extends Model
{
    protected $table = 'combination';

    protected $fillable = [
        'id',
        'report_id', 'source_id',
        'unique_name', 'big_city', 'is_generated', 'additional', 'username',
        'version', 'extra_data'
    ];

    public $timestamps = false;

    public function levels()
    {
        return $this->hasMany(CombinationLevel::class, 'comb_id');
    }
}