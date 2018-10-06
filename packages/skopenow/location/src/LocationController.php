<?php

/**
 * EntrypointController for formatting
 *
 * PHP version 7.0
 * 
 * @category Class
 * @package  MyPackage
 * @author   Queen tech <info@queentechsolutions.net>
 * @license  license http://www.queentechsolutions.net/
 * @link     http://www.queentechsolutions.net/
 */
namespace Skopenow\Location;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Skopenow\Location\EntryPoint;

/**
 * EntrypointController for formatting
 *
 * @category Class
 * @package  MyPackage
 * @author   Queen tech <info@queentechsolutions.net>
 * @license  license http://www.queentechsolutions.net/
 * @link     http://www.queentechsolutions.net/
 */
class LocationController extends Controller
{
    public function distance(Request $request)
    {
        $caller = new EntryPoint();
        
        $firstlatLng = new \ArrayIterator([
            "lat"=>floatval($request->firstDistance["lat"]),
            "lng"=>floatval($request->firstDistance["lng"])
            ]);
        $secondlatLng = new \ArrayIterator([
            "lat"=>floatval($request->secondDistance["lat"]),
            "lng"=>floatval($request->secondDistance["lng"])
            ]);
        $distance = $caller->calculateDistance($firstlatLng,$secondlatLng);
        return json_encode($distance);
    }

    public function findCities(Request $request)
    {
        $entryPoint = new EntryPoint();

        $cities = $entryPoint->findCities($request->clities);
        
        return json_encode($cities);
    }

    public function findAddress(Request $request)
    {
        $entryPoint = new EntryPoint();
        $addresses = $entryPoint->findAddress($request->addresses);
        return json_encode($addresses);
    }

    public function findLatLng(Request $request)
    {
        $entryPoint = new EntryPoint();
        $latLngs = $entryPoint->findLatLng($request->latLngs);
        return json_encode($latLngs);
    }

    public function locatedInUS(Request $request)
    {
        $entryPoint = new EntryPoint();
        $located = $entryPoint->isLocatedInUS($request->statesOrCities);
        return json_encode($located);
    }

    public function nearestCities(Request $request)
    {
        $entryPoint = new EntryPoint();
        $cities = $entryPoint->findNearestCities(new \ArrayIterator($request->locations));

        foreach ($cities as $key=>$value) {
            $newCites[$key] = iterator_to_array($value, true);
        }

        return json_encode($newCites);
    }
    
    public function getCityZipCodes(Request $request)
    {
        $entryPoint = new EntryPoint();
        $zipCodes = $entryPoint->getCityZipCodes(new \ArrayIterator($request->cityStates));
        return json_encode($zipCodes);
    }

    public function getStateAbv(Request $request)
    {
        $entryPoint = new EntryPoint();
        $stateAbv = $entryPoint->getStateAbv(new \ArrayIterator($request->stateskeyword));
        return json_encode($stateAbv);
    }

    public function getStateName(Request $request)
    {
        $entryPoint = new EntryPoint();
        $stateName = $entryPoint->getStateName(new \ArrayIterator($request->stateskeyword));
        return json_encode($stateName);
    }

    public function getStateNameByAreaCode(Request $request)
    {
        $entryPoint = new EntryPoint();
        $stateName = $entryPoint->getStateNameByAreaCode(new \ArrayIterator($request->areasCode));
        return json_encode($stateName);
    }

    public function normalizeStateName(Request $request)
    {
        $entryPoint = new EntryPoint();
        $normalizeState = $entryPoint->normalizeStateName(new \ArrayIterator($request->statesName));
        return json_encode($normalizeState);
    }
    
}
