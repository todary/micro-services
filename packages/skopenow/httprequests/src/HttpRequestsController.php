<?php

namespace Skopenow\HttpRequestsService;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HttpRequestsController extends Controller
{
    /**
     * validation gateway function
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $inputs = new \ArrayIterator($request->all());
        $validation = app('validation');

        try {
            $validation->validate($inputs);
            $response = $validation->getResults();
        } catch (\Exception $e) {
            $response = ['error' => $e->getMessage()];
        }
        return response()->json($response);
    }
}
