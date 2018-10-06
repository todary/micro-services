<?php

/**
 * EntrypointController for formatting
 *
 * @category Class
 * @package  MyPackage
 * @author   Queen tech <info@queentechsolutions.net>
 * @license  license http://www.queentechsolutions.net/
 * @link     http://www.queentechsolutions.net/
 */
namespace Skopenow\Formatter;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
//use Skopenow\Formatter\Entrypoint;

/**
 * EntrypointController for formatting
 *
 * @category Class
 * @package  MyPackage
 * @author   Queen tech <info@queentechsolutions.net>
 * @license  license http://www.queentechsolutions.net/
 * @link     http://www.queentechsolutions.net/
 */
class FormatterController extends Controller
{
	/**
	 * [index description]
	 * 
	 * @param  Request $request [description]
	 * 
	 * @return [json]           [description]
	 */
    public function index(Request $request)
    {
    	//return $request->all();
    	$inputs = new \ArrayIterator($request->all());
		$formaterPoint = new EntryPoint();
		$formatInputs = $formaterPoint->format($inputs);

		//convert the output array iterator to json 
		$newFormat = [];
		foreach($formatInputs as $key=>$value){
			$newFormat[$key] = iterator_to_array($value,true);
		}
		return json_encode($newFormat,true);
    }
    
}
