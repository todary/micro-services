<?php
/**
 * Relationship controller
 *
 * PHP version 7.0
 *
 * @category Micro_Services-phase_1
 * @package  Relationship
 * @author   Mahmoud AboElnouR <aboelnoor@queentecksolutions.com>
 * @license  QueenTechSolutions, Inc.
 * @link     http://www.queentecksolutions.com
 **/
namespace Skopenow\Relationship;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/**
 * Relationship controller
 *
 * @category Micro_Services-phase_1
 * @package  Relationship
 * @author   Mahmoud AboElnouR <aboelnoor@queentecksolutions.com>
 * @license  QueenTechSolutions, Inc.
 * @link     http://www.queentecksolutions.com
 **/
class RelationshipController extends Controller
{
    /**
     * Relationship gateway function
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $inputs = new \ArrayIterator($request->all());
        // $relationship = app('relationship');
        $relationship = new EntryPoint;

        try {
            $relationship->validate($inputs);
            $response = $relationship->getResults();
        } catch (\Exception $e) {
            $response = ['error' => $e->getMessage()];
        }
        return response()->json($response);
    }
}
