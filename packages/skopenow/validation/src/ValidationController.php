<?php
/**
 * Validation controller
 *
 * PHP version 7.0
 *
 * @category Micro_Services-phase_1
 * @package  Validation
 * @author   Mahmoud AboElnouR <aboelnoor@queentecksolutions.com>
 * @license  QueenTechSolutions, Inc.
 * @link     http://www.queentecksolutions.com
 **/
namespace Skopenow\Validation;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/**
 * Validation controller
 *
 * @category Micro_Services-phase_1
 * @package  Validation
 * @author   Mahmoud AboElnouR <aboelnoor@queentecksolutions.com>
 * @license  QueenTechSolutions, Inc.
 * @link     http://www.queentecksolutions.com
 **/
class ValidationController extends Controller
{
    /**
     * validation gateway function
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $inputs = new \ArrayIterator($request->all());
        // $validation = app('validation');
        $validation = new EntryPoint;

        try {
            $validation->validate($inputs);
            $response = $validation->getResults();
        } catch (\Exception $e) {
            $response = ['error' => $e->getMessage()];
        }
        return response()->json($response);
    }
}
