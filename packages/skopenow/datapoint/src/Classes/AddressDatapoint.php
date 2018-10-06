<?php
/**
 * Abstract Datapoint code
 *
 * PHP version 7.0
 *
 * @category Micro_Services-phase_2
 * @package  Datapoint
 * @author   Mahmoud AboElnouR <aboelnoor@queentecksolutions.com>
 * @license  QueenTechSolutions, Inc.
 * @link     http://www.queentecksolutions.com
 **/
namespace Skopenow\Datapoint\Classes;

use App\Models\Settings;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

/**
 * Abstract Datapoint class
 *
 * @category Micro_Services-phase_2
 * @package  Datapoint
 * @author   Mahmoud AboElnouR <aboelnoor@queentecksolutions.com>
 * @license  QueenTechSolutions, Inc.
 * @link     http://www.queentecksolutions.com
 **/
class AddressDatapoint extends Datapoint
{
    public $isQuable = false;

    const FILTER_REGEX = [
        '/\s+\#unt\s*\d+/i',
        '/\\s+\\d+\\s*(,\\s*(us|usa|united states))?\\s*$/i',
        '/\\s+(Rd|Road|street|st\\.|st|ln|lane)\\s*((\\Apt|#Apt|#|apartment)?[^,]*)?/i',
    ];
    const CACHE_TIME = 5;
    const DIST_THRESHOLD = 300;

    public function addEntry($address)
    {
        Log::info("add address start\n");
        $fullAddress = $address->full_address;
        $isValidAddress = $this->isValidInputs([
            'address' => (array) $fullAddress,
            'location' => (array) $fullAddress,
        ], false);

        if (strlen($fullAddress) < 5 && !isset(loadData('states_abv')[strtoupper($address->state)])) {
            Log::warning("Invalid address $fullAddress, ignored.");
            return false;
        }

        if (!$isValidAddress) {
            Log::warning("Invalid address [$fullAddress], validation ignored.");
            return false;
        }

        /*$oldLocation = $this->checkAddress($address);

        $minDist = null;
        if (!empty($oldLocation)) {
            list($address['lattitude'], $address['longitude']) = $oldLocation;
            $key = $oldLocation[2] ?? null;
            if (is_array($key)) {
                return;
            }
        }

        if (env('APP_ENV') != 'local' && $this->report['cities'] && empty($address['lattitude'])) {
            $primaryLocation = getLatLngOf($this->report['cities'][0]);
            $currentAddress = getLatLngOf($fullAddress)[0];
            $address['lattitude'] = $currentAddress['lat'];
            $address['longitude'] = $currentAddress['lng'];
            foreach ($primaryLocation as $locationDetails) {
                $dist = vdistance(
                    $locationDetails['lat'],
                    $locationDetails['lng'],
                    $address['lattitude'],
                    $address['longitude']
                );
                $minDist = is_null($minDist) ? $dist : min($minDist, $dist);
            }
        }

        if (env('APP_ENV') == 'local' && empty($address['lattitude'])) {
            $address['lattitude'] = rand(100, -100);
            $address['longitude'] = rand(100, -100);
            $key = $key ?? md5(strtolower($address['full_address']));
        }*/

        $location = ltrim($address->city . ', ', ', ') . $address->state;
        if (!$location) {
            $addressParts = explode(",", $fullAddress);
            $location = trim(end($addressParts));
        }
        $location = trim($location, ', ');

        $insertData = array(
            // 'key' => $key ?? md5("{$address['lattitude']},{$address['longitude']}"),
            'key' => md5($fullAddress),
            'assoc_profile' => $this->resultId ? "res_{$this->resultId}" :
            (!$this->combinationId ? "comb_$this->combinationId" : 'comb_base'),

            'res' => $this->resultId,
            // 'nearbyLocation' => $minDist,
            'locationName' => $location,
            /*'locationLat' => $address['lattitude'],
            'locationLng' => $address['longitude'],*/
            'locationDetails' => $address->data,
            'shortAddress' => $location,
            'fullAddress' => $fullAddress,
            'parent_comb' => $this->combinationId ?? null,
            'bigCity' => $location ? checkCitySize($location) : null,
        );

        Log::debug('data passed to datasource', compact('insertData', 'address'));
        $this->addDataPoint('addresses', $insertData, $address);
        Log::info("add address end\n");
        return true;
    }

    public function checkAddress($data): array
    {
        /*if (config('flags.initiating_report')) {
        return array();
        }*/

        Log::info("check address start\n");
        $locations = array_filter($this->datasource->currentProgress('addresses', true));

        $distance_threshold = Cache::remember('settings', self::CACHE_TIME, function () {
            return Settings::where('key', 'distance_threshold')
                ->select('value')->first()->value ?? self::DIST_THRESHOLD;
        });

        if (count($locations)) {
            $latLen1 = numberOfDecimals($data['latitude']);
            $lonLen1 = numberOfDecimals($data['longitude']);
            foreach ($locations as $location) {
                if (!is_array($location)) {
                    continue;
                }
                $location = $location['data'];

                $locationLat = $location['locationLat'] ?? null;
                $locationLng = $location['locationLng'] ?? null;

                Log::debug('loaction check:', ['location', $location]);
                $oldLocation = strtolower(preg_replace(self::FILTER_REGEX, '', $location['fullAddress']));
                $inputLocation = strtolower(preg_replace(self::FILTER_REGEX, '', $data['full_address']));

                $isDetailedLocation = strlen($oldLocation) <= strlen($inputLocation)
                && strpos($inputLocation, $oldLocation) !== false;

                if ($oldLocation == $inputLocation || $isDetailedLocation) {
                    Log::info("check address end\n");
                    return array($locationLat, $locationLng, $location['key']);
                }

                $isPartialLocation = strlen($oldLocation) >= strlen($inputLocation)
                && strpos($oldLocation, $inputLocation) !== false;

                if ($isPartialLocation) {
                    Log::info("check address end\n");
                    return array(null, null, $location);
                }

                $latLen2 = numberOfDecimals($locationLat);
                $lonLen2 = numberOfDecimals($locationLng);
                if ($latLen1 < $latLen2) {
                    $latLen = $latLen1;
                } else {
                    $latLen = $latLen2;
                }

                if ($lonLen1 < $lonLen2) {
                    $lonLen = $lonLen1;
                } else {
                    $lonLen = $latLen2;
                }

                if ($latLen) {
                    $data['lat'] = floor(trim($data['lat']) * pow(10, $latLen)) / pow(10, $latLen);
                    $data['lon'] = floor(trim($data['lon']) * pow(10, $latLen)) / pow(10, $latLen);
                }
                if ($lonLen) {
                    $locationLat = floor(trim($locationLat) * pow(10, $lonLen)) / pow(10, $lonLen);
                    $locationLng = floor(trim($locationLng) * pow(10, $lonLen)) / pow(10, $lonLen);
                }

                if (vdistance($data['lat'], $data['lon'], $locationLat, $locationLng) <= $distance_threshold) {
                    Log::info("check address end2\n");
                    return array($locationLat, $locationLng);
                }
            }
        }

        Log::info("check address end3\n");
        return array();
    }
}
