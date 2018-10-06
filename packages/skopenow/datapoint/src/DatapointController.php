<?php
/**
 * Datapoint controller
 *
 * PHP version 7.0
 *
 * @category Micro_Services-phase_1
 * @package  Datapoint
 * @author   Mahmoud AboElnouR <aboelnoor@queentecksolutions.com>
 * @license  QueenTechSolutions, Inc.
 * @link     http://www.queentecksolutions.com
 **/
namespace Skopenow\Datapoint;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/**
 * Datapoint controller
 *
 * @category Micro_Services-phase_1
 * @package  Datapoint
 * @author   Mahmoud AboElnouR <aboelnoor@queentecksolutions.com>
 * @license  QueenTechSolutions, Inc.
 * @link     http://www.queentecksolutions.com
 **/
class DatapointController extends Controller
{
    /**
     * Datapoint gateway function
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $inputs = new \ArrayIterator($request->all());
        // $datapoint = app('datapoint');
        $datapoint = new EntryPoint;

        try {
            $datapoint->validate($inputs);
            $response = $datapoint->getResults();
        } catch (\Exception $e) {
            $response = ['error' => $e->getMessage()];
        }
        return response()->json($response);
    }
}
