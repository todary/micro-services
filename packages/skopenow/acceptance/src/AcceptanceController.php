<?php

/**
 * AcceptanceController for formatting
 *
 * @category Class
 * @package  Acceptance
 * @author   Queen tech <info@queentechsolutions.net>
 * @license  license http://www.queentechsolutions.net/
 * @link     http://www.queentechsolutions.net/
 */
namespace Skopenow\Acceptance;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/**
 * AcceptanceController for formatting
 *
 * @category Class
 * @package  Acceptance
 * @author   Queen tech <info@queentechsolutions.net>
 * @license  license http://www.queentechsolutions.net/
 * @link     http://www.queentechsolutions.net/
 */
class AcceptanceController extends Controller
{
	public function index()
	{
		$flags = $request->flags;
		$nameFound = $request->nameFound; 
		$locationFound = $request->locationFound;
		$isDataPoint = $request->isDataPoint;
		$userNameFound = $request->userNameFound;

		$acceptanceObj = loadService("acceptance");
		$output = $acceptanceObj->checkAcceptance(
		    $flags,
		    $nameFound,
		    $locationFound,
		    $isDataPoint,
		    $userNameFound
		);

		return json_encode($output);
	}
}