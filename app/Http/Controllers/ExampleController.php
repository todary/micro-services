<?php

namespace App\Http\Controllers;

class ExampleController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    public function index()
    {
        $query = \Illuminate\Support\Facades\DB::where("type" , "type");
        dd($query);
    }

    //
}
