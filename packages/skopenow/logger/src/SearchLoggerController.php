<?php

/* 
 * Here will be the search logger controller which will handle all the requests
 * and interations with the logger .
 */

namespace Skopenow\Logger ;

use Skopenow\Logger\EntryPoint ;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SearchLoggerController extends Controller
{
	public function __construct() 
	{
		require 'config/logger.php';
	}
	
	public function addLog(Request $request)
	{
		$uri = "mongodb://";
		if(!empty(config("logger.mongodb.username"))){
			$uri .= config("logger.mongodb.username")."@".config("logger.mongodb.password"); 
		}
		
		$uri .= config("logger.mongodb.host");
		if(!empty(config("logger.mongodb.port"))){
			$uri .= ":".config("logger.mongodb.port");
		}

		if(!empty($request['state']) && !empty($request['data']) && !empty($request['log_type'])){
			$type =  $request->log_type;//\SearchLogger::SEARCH_START ;
			$writers = array();
			$mongo = new \MongoDB\Client($uri); //'mongodb://localhost:27017'
			dd($mongo);
			$writers[] = new Skopenow\Logger\Writer\MongoDBWriter($mongo);
			$searchLogger = new EntryPoint($type , $writers);
			$parameters = json_decode($request->data,true);
			$env = json_decode($request->state,true);
			$response = $searchLogger->addLog( $env, $parameters);
			return response()->json($response);
		}
		return response()->json(['statue' => false , "notes" => ["required" => "You must define the state , data and type to proceed with this request" ]]);
	}
}
