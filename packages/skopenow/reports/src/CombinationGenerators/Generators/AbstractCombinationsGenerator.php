<?php
namespace Skopenow\Reports\CombinationGenerators\Generators;

use App\Models\SearchCriteria;
use Skopenow\Reports\Models\Report;
use App\Models\EmailBlacklist;

abstract class AbstractCombinationsGenerator
{
    protected $combinationsMaker;
    protected $combinationsService;
    protected $data;
    protected $report;

    public function __construct($combinationsMaker, $combinationsService)
    {
        $this->combinationsMaker = $combinationsMaker;
        $this->combinationsService = $combinationsService;
    }

    public function generate(int $reportId)
    {
        $this->report = Report::find($reportId);
        $this->extractFromReport($this->report);
        return $this->make();
    }

    protected function extractFromReport($report)
    {
        $names = [];
        foreach ($report->full_name as $name) {
            $unique = isUniqueFullName([$name]);
            $name_status = $unique[0] ? 'unique' : 'common';
            $nameParts = name_parts($name);
            $names[] = array_merge($nameParts, ['name_status' => $name_status]);
            if (!empty($nameParts['middle_name'])) {
                $nameParts['middle_name'] = "";
                $names[] = array_merge($nameParts, ['name_status' => $name_status]);
            }
        }
        $locations = $this->getLocationWithStatus($report->city);

        $this->combinationsMaker->set('name', $names);
        $this->combinationsMaker->set('location', $locations);
        $this->combinationsMaker->set('company', $report->company);
        $this->combinationsMaker->set('school', $report->school);
        $this->combinationsMaker->set('address', $report->address);
        $this->combinationsMaker->set('age', $report->age);
        $this->combinationsMaker->set('email', $report->email);
        $this->combinationsMaker->set('phone', $report->phone);
        $this->combinationsMaker->set('zipcode', $report->zipcode);
        $this->combinationsMaker->set('username', $report->usernames);
        $this->combinationsMaker->set('date_of_birth', $report->date_of_birth);
        // $this->combinationsMaker->set('country_code', [$report->country]);
    }

    protected function buildSearchCriteria($combination)
    {
        $data = $combination->getData();
        $criteria = new SearchCriteria();
        $criteria->full_name = $data['name'][0]['full_name']??'';
        $criteria->first_name = $data['name'][0]['first_name']??'';
        $criteria->middle_name = $data['name'][0]['middle_name']??'';
        $criteria->last_name = $data['name'][0]['last_name']??'';
        $criteria->name_status = $data['name'][0]['name_status']??'';
        $criteria->city = $data['location'][0]['city']??'';
        $criteria->state = $data['location'][0]['state']??'';
        $criteria->city_status = $data['location'][0]['city_status']??'';
        $criteria->country_code = $data['country_code'][0]??'';
        $criteria->zipcode = $data['zipcode'][0]??'';
        $criteria->social_profile_id = $data['social_profile_id'][0]??'';
        $criteria->birth_date = $data['date_of_birth'][0]??'';
        $criteria->phone = $this->implode($data, 'phone');
        $criteria->email = $this->implode($data, 'email');
        $criteria->username = $this->implode($data, 'username');
        $criteria->address = $this->implode($data, 'address');
        $criteria->domain = $this->implode($data, 'domain');
        $criteria->site = $this->implode($data, 'site');
        $criteria->company = $this->implode($data, 'company');
        $criteria->social_profile_id = $this->implode($data, 'social_profile_id');
        $criteria->result_id = $this->implode($data, 'result_id');
        $criteria->related_to = $this->implode($data, 'related_to');
        $criteria->is_relative = $this->implode($data, 'is_relative');
        $criteria->has_verified_profiles = $this->implode($data, 'has_verified_profiles');
        $criteria->school = $this->implode($data, 'school');
        $criteria->username_source = $this->implode($data, 'username_source');
        $criteria->username_status = $this->implode($data, 'username_status');
        $criteria->profile_image = $this->implode($data, 'profile_image');
        $criteria->profiles = $data['profiles']??[];
        $criteria->distance = $this->implode($data, 'distance');
        return $criteria;
    }

    protected function implode($data, $key)
    {
        return implode('|', $data[$key]??[]);
    }

    public function setData($data)
    {
        $this->data = $data;
    }

    protected function checkPrivateEmail($email) : bool
    {
        if (($email = filter_var($email, FILTER_VALIDATE_EMAIL)) !== false) {
            preg_match("/(.*)@(.*)/", $email, $domain);
            $blackList = EmailBlacklist::where('domain', $domain[2])->first();
            $this->report->domain = $domain[1];
            return $blackList === null;
        }
        return false;
    }

    protected function sortLocationsByCitySize($locations)
    {
        \Log::info('BRAIN: Sorting Locations by city size');
        $locationsWithSmallCity = [];
        foreach ($locations as $location) {
            // 0 for small City and 1 for big city
            $locationsWithSmallCity[$location] = $this->isBigCity($location) ? 1 : 0;
        }
        asort($locationsWithSmallCity);
        $locations = [];
        foreach ($locationsWithSmallCity as $location => $isBigCity) {
            $locations[] = $location;
        }
        return $locations;
    }

    protected function isBigCity($location)
    {
        $entry = loadService('matching');
        return $entry->isBigCity($location);
    }

    protected function getMainSourceId($mainSource)
    {
        $mainSource = \DB::table('source')->where('name', $mainSource)->first();
        return $mainSource->id;
    }

    protected function getLocationWithStatus($cityState)
    {
        \Log::info('BRAIN: get locations with city status');
        $locationService = loadService('location');
        $cityStatus = [];
        $locations = $locationService->splitLocation(new \ArrayIterator($cityState));
        $locations = $locations->getArrayCopy();
        foreach ($cityState as $city) {
            $cityStatus[] = $this->isBigCity($city) ? 'bigCity' : 'smallCity';
        }
        $locs = [];
        for ($i = 0; $i < count($locations); $i++) {
            $locs[$i] = array_merge(['city_status' => $cityStatus[$i]], $locations[$i]);
        }

        return $locs;
    }

    protected function makePrivateEmailCombinations($email, $source)
    {
        preg_match("/(.*)@(.*)/", $email, $match);
        $username = $match[1];
        $domain = $match[2];
        $this->combinationsMaker->set('domain', [$domain]);
        $this->combinationsMaker->set('username', [$username]);
        $combinations = $this->combinationsMaker
            ->withEach(['name', 'domain'])
            ->get();

        foreach ($combinations as $combination) {
            $criteria = $this->buildSearchCriteria($combination);
            $level = ['source' => $source, 'data' => $criteria->toCombinationData(), 'level_number' => 1];
            $this->combinationsService->store($source, [$level]);
        }
    }

    protected function generateUsernamesFromNames(array $names)
    {
        $usernames = [];
        foreach ($names as $name) {
            $unique = isUniqueFullName([$name]);
            $nameParts = name_parts($name);
            if (empty($nameParts['middle_name'])) {
                if ($unique[0]) {
                    $usernames[$name] = $nameParts['first_name'];
                    $usernames[$name] .= '.' . $nameParts['last_name'];
                }
                continue;
            }
            $usernames[$name] = $nameParts['first_name'];
            $usernames[$name] .= '.' . substr($nameParts['middle_name'], 0, 1);
            $usernames[$name] .= '.' . $nameParts['last_name'];
            $usernames[$name] = strtolower($usernames[$name]);
        }
        return $usernames;
    }

    protected function insertRelationship($id1, $id2, array $params)
    {
        if (empty($id1) || empty($id2)) {
            return;
        }
        $relationship = loadService('relationship');
        $insert = $relationship->insert();
        $insert->setRelationshipWithIds($id1, $id2, $params);
        \Log::debug('BRAIN: add relationship, type => ', [$params['type']]);
    }

    protected function checkUsernameWithFlags() : bool
    {
        $scoringFlags = loadData("scoringFlags");
        \Log::info('BRAIN: Check username with flags');
        if (empty($this->data['input']->extras['flags'])) {
            \Log::info('BRAIN: CheckUsername ... no flags');
            return false;
        }
        $flags = $this->data['input']->extras['flags'];
        $uniqueNameFlag = [$scoringFlags['unq_name']['value']];
        $resultService = loadService('result');
        $firstNameFlag = $scoringFlags['fn']['value'];
        $lastNameFlag = $scoringFlags['ln']['value'];
        $nameFlags = $firstNameFlag | $lastNameFlag;

        if ($resultService->checkWithFlags($flags, $uniqueNameFlag)) {
            \Log::info('BRAIN: CheckUsername ... unique name');
            return true;
        } elseif (! $resultService->checkWithFlags($flags, [$nameFlags])) {
            \Log::info('BRAIN: CheckUsername ... no name with username .. return false');
            return false;
        }

        $matchingFlags = [
            'exct_small_city' => $scoringFlags['exct-sm']['value'],
            'exct_big_city' => $scoringFlags['exct-bg']['value'],
            'partial_city' => $scoringFlags['pct']['value'],
            'state' => $scoringFlags['st']['value'],
            'email' => $scoringFlags['em']['value'],
            'phone' => $scoringFlags['ph']['value'],
            'company' => $scoringFlags['cm']['value'],
            'school' => $scoringFlags['sc']['value'],
            'age' => $scoringFlags['age']['value'],
        ];

        if ($resultService->checkWithFlags($flags, $matchingFlags)) {
            \Log::info('BRAIN: CheckUsername . checked username flag : ' . $flags);
            return true;
        }

        \Log::info('BRAIN: CheckUsername ... no matched flags .. return false');
        return false;
    }
    protected function createSimpleCombination(array $types, string $source)
    {
        $combinations = $this->combinationsMaker
            ->withEach($types)
            ->get();

        foreach ($combinations as $combination) {
            $criteria = $this->buildSearchCriteria($combination);

            $level = ['source' => $source, 'data' => $criteria->toCombinationData(), 'level_number' => 1];
            $this->combinationsService->store($source, [$level]);
        }
    }
}
