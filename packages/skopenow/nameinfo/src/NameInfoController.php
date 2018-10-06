<?php

/**
 * NameInfoController
 *
 *
 * @package   NameInfoController
 * @author    Queen tech <info@queentechsolutions.net>
 * @copyright 2017-2018 Queen tech
 * @access    public
 * @license   http://www.queentechsolutions.net/ BSD Licence
 * @version   CVS: 1.0.0
 * @link      http://www.queentechsolutions.net/
 */
namespace Skopenow\NameInfo;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Skopenow\NameInfo\EntryPoint;

/**
 * NameInfoController
 *
 * @package   NameInfoController
 * @author    Queen tech <info@queentechsolutions.net>
 * @copyright 2017-2018 Queen tech
 * @access    public
 * @license   http://www.queentechsolutions.net/ BSD Licence
 * @version   CVS: 1.0.0
 * @link      http://www.queentechsolutions.net/
 */

class NameInfoController extends Controller
{

    /**
    * nameSplit
    *
    *
    * @access public
    * @param Request $request
    * @return string
    */
    public function nameSplit(Request $request)
    {
        $inputs = new \ArrayIterator($request->all());
        $entryPoint = new EntryPoint();
        $output = $entryPoint->NameSplit($inputs);
        
        $newFormat = [];
        foreach($output as $key => $value){
            $newFormat[$key] = $value;
        }
        return json_encode($newFormat, true);
    }

    /**
    * nickNames
    *
    *
    * @access public
    * @param Request $request
    * @return string
    */
    public function nickNames(Request $request)
    {
        $inputs = new \ArrayIterator($request->all());
        $entryPoint = new EntryPoint();
        $output = $entryPoint->NickNames($inputs);

        $newFormat = [];
        foreach($output as $key => $value){
            $newFormat[$key] = $value;
        }
        return json_encode($newFormat, true);
    }

    /**
    * uniqueName
    *
    *
    * @access public
    * @param Request $request
    * @return string
    */
    public function uniqueName(Request $request)
    {
        $inputs = new \ArrayIterator($request->all());
        $entryPoint = new EntryPoint();
        $output = $entryPoint->UniqueName($inputs);

        $newFormat = [];
        foreach($output as $key => $value){
            $newFormat[$key] = $value;
        }
        return json_encode($newFormat, true);
    }
}