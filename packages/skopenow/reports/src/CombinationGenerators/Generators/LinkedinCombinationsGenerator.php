<?php
namespace Skopenow\Reports\CombinationGenerators\Generators;

use Skopenow\Reports\Models\Report;

/**
*
*/
class LinkedinCombinationsGenerator extends AbstractCombinationsGenerator
{

    public function make()
    {
        $emails = $this->report->email;
        $this->makeNameWorkCombinations();
        $this->makeNameSchoolCombinations();
        $this->makeNameZipCodeCombinations();
        $this->makeNameLocationCombinations();

        foreach ($emails as $email) {
            if ($this->checkPrivateEmail($email)) {
                $this->makePrivateEmailCombinations($email, 'linkedin');
            }
        }
    }

    public function makeNameSchoolCombinations()
    {
        $this->createSimpleCombination(['name', 'school'], 'linkedin');
    }

    public function makeNameWorkCombinations()
    {
        $this->createSimpleCombination(['name', 'company'], 'linkedin');
    }

    public function makeNameZipCodeCombinations()
    {
        // TODO
        // Check getZipCode methode => it returns NULL
        $zipCode = $this->getZipCode();
        if (empty($zipCode)) {
            return;
        }
        $this->combinationsMaker->set('zipCode', $zipCode);
        $combinations = $this->combinationsMaker
            ->withEach(['name', 'zipcode'])
            ->get();

        foreach ($combinations as $combination) {
            // $combinationId = $this->combinationsService->store('linkedin');
            $criteria = $this->buildSearchCriteria($combination);
            $level = ['source' => 'linkedin', 'data' => $criteria->toCombinationData(), 'level_number' => 1];
            $this->combinationsService->store('linkedin', [$level]);
        }
    }

    public function makeNameLocationCombinations()
    {
        $source = 'linkedin';
        $locationsWithSmallCity = $this->sortLocationsByCitySize($this->report->city);
        $locations = $this->getLocationWithStatus($locationsWithSmallCity);
        $isUniqueName = isUniqueFullName($this->report->full_name);
        $this->combinationsMaker->set('location', $locations);
        $this->combinationsMaker->set('country_code', [$this->report->country ?? 'US']);
        // if First name is uniqe -> priority to name
        if (!empty($isUniqueName) && $isUniqueName[0]) {
            $combinations = $this->combinationsMaker
                ->withEach(['name', 'location', 'country_code'])
                ->get();
        // if common name and small city -> priority to location
        } elseif (!empty($locations[0]['city_status']) && $locations[0]['city_status'] === 'smallCity') {
            $combinations = $this->combinationsMaker
                ->withEach(['location', 'name', 'country_code'])
                ->get();
        // common name and big city -> priority to name
        } else {
            $combinations = $this->combinationsMaker
                ->withEach(['name', 'location', 'country_code'])
                ->get();
        }

        $levels = [];
        $levelNumber = 1;
        foreach ($combinations as $combination) {
            $criteria = $this->buildSearchCriteria($combination);
            $criteriaData = $criteria->toCombinationData();
            $hashKey = md5(json_encode($criteriaData));
            if (!array_key_exists($hashKey, $levels)) {
                $levels[$hashKey] = ['source' => $source, 'data' => $criteriaData, 'level_number' => $levelNumber];
                $levelNumber++;
            }
        }
        foreach ($combinations as $combination) {
            $criteria->city = '';
            $criteria->city_status = '';
            if (!empty($criteria->state)) {
                $criteriaData = $criteria->toCombinationData();
                $hashKey = md5(json_encode($criteriaData));
                if (!array_key_exists($hashKey, $levels)) {
                    $levels[$hashKey] = ['source' => $source, 'data' => $criteriaData, 'level_number' => $levelNumber];
                    $levelNumber++;
                }
            }
        }
        foreach ($combinations as $combination) {
            $criteria->state = '';
            $criteriaData = $criteria->toCombinationData();
            $hashKey = md5(json_encode($criteriaData));
            if (!array_key_exists($hashKey, $levels)) {
                if ($criteria->name_status == 'unique') {
                    $criteria->country_code = empty($this->report->country) ? 'US' : empty($this->report->country);
                    $number = 1;
                    $lvls = [['source' => 'linkedin', 'data' => $criteriaData, 'level_number' => $number]];

                    $number++;
                    $criteria->middle_name = '';
                    $criteria->search_type = 'with_name_flags';
                    $lvls[] = ['source' => 'linkedin', 'data' => $criteria->toCombinationData(), 'level_number' => $number];
                    $number++;
                    // $countryCode = $this->report->country ?? 'US';
                    $countryCode = $criteria->country_code;
                    $criteria->country_code = '';
                    $lvls[] = ['source' => 'linkedin', 'data' => $criteria->toCombinationData(), 'level_number' => $number];
                    $number++;

                    $criteria->country_code = $countryCode;
                    $criteria->search_type = '';
                    $lvls[] = ['source' => 'linkedin', 'data' => $criteria->toCombinationData(), 'level_number' => $number];
                    $number++;

                    $criteria->country_code = '';
                    $lvls[] = ['source' => 'linkedin', 'data' => $criteria->toCombinationData(), 'level_number' => $number];
                    $number++;
                    $this->combinationsService->store('linkedin', $lvls);
                } else {
                    $criteria->country_code = empty($this->report->country) ? 'US' : empty($this->report->country);
                    $levels[$hashKey] = ['source' => 'linkedin', 'data' => $criteriaData, 'level_number' => $levelNumber];
                    $levelNumber++;
                    $criteria->middle_name = '';
                    $criteria->search_type = 'with_name_flags';
                    $levels[] = ['source' => 'linkedin', 'data' => $criteria->toCombinationData(), 'level_number' => $levelNumber];
                    $levelNumber++;
                    $countryCode = $criteria->country_code;
                    // $countryCode = $this->report->country ?? 'US';
                    $criteria->country_code = '';
                    $levels[] = ['source' => 'linkedin', 'data' => $criteria->toCombinationData(), 'level_number' => $levelNumber];
                    $levelNumber++;

                    $criteria->country_code = $countryCode;
                    $criteria->search_type = '';
                    $levels[] = ['source' => 'linkedin', 'data' => $criteria->toCombinationData(), 'level_number' => $levelNumber];
                    $levelNumber++;

                    $criteria->country_code = '';
                    $levels[] = ['source' => 'linkedin', 'data' => $criteria->toCombinationData(), 'level_number' => $levelNumber];
                    $levelNumber++;
                    // $this->combinationsService->store('linkedin', $levels);
                }
            }
        }
        $this->combinationsService->store('linkedin', $levels);
    }

    protected function getZipCode()
    {
        $cityState = new \ArrayIterator($this->report->city);
        $code = loadService('location')->getCityZipCodes($cityState);
        $zipcodes = $code->getArrayCopy();
        $codes = [];
        foreach ($zipcodes as $zipcode) {
            if (is_array($zipcode) && !empty($zipcode['zip'])) {
                $codes[] = $zipcode['zip'];
            }
        }
        return array_filter($codes, 'strlen');
    }
}
