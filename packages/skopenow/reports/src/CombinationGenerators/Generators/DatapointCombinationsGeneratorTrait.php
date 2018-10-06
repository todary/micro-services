<?php
namespace Skopenow\Reports\CombinationGenerators\Generators;

use Skopenow\Reports\Models\Report;
use App\DataTypes\DataType;

/**
*
*/
trait DatapointCombinationsGeneratorTrait
{
    protected $data;

    protected function makeEmailCombinations()
    {
        switch ($this->data['source']) {
            case 'peopleData':
                $this->makeCombinations(
                    'email',
                    ['fullcontact', 'google', 'facebook_people_search', 'googleplus', 'websites']
                );
                break;

            case 'facebook':
                $this->makeCombinations('email', ['google','websites']);
                break;

            case 'linkedin':
            case 'twitter':
                $this->makeCombinations(
                    'email',
                    ['google', 'facebook_people_search', 'websites']
                );
                break;

            case 'websites':
                $this->makeCombinations(
                    'email',
                    ['google', 'facebook_people_search']
                );
                break;

            default:
                // $this->makeCombinations('email', ['google']);
                break;
        }
    }
    protected function makePhoneCombinations()
    {
        switch ($this->data['source']) {
            case 'facebook':
                $this->makeCombinations(
                    'phone',
                    ['google','websites', 'yellowpages']
                );
                break;
            case 'peopleData':
            case 'linkedin':
            case 'websites':
                $this->makeCombinations(
                    'phone',
                    ['google','websites', 'yellowpages', 'facebook_people_search']
                );
                break;

            default:
                // $this->makeCombinations('phone', ['google']);
                break;
        }
    }

    protected function makeWorkCombinations()
    {
        switch ($this->data['source']) {
            case 'facebook':
            case 'linkedin':
            case 'googleplus':
            case 'angel':
                $this->makeCombinations('company', ['google']);
                break;

            default:
                // $this->makeCombinations('company', ['google']);
                break;
        }
    }

    protected function makeSchoolCombinations()
    {
        switch ($this->data['source']) {
            case 'facebook':
            case 'linkedin':
            case 'googleplus':
            case 'angel':
                $this->makeCombinations('school', ['google']);
                break;

            default:
                // $this->makeCombinations('school', ['google']);
                break;
        }
    }

    protected function makeLocationCombinations()
    {
        $this->makeCombinations('location', ['google']);
    }

    protected function makeAddressCombinations()
    {
        $combinations = $this->combinationsMaker
            ->withEach(['address'])
            ->get();

        foreach ($combinations as $combination) {
            $criteria = $this->buildSearchCriteria($combination);
            $level = ['source' => 'google', 'data' => $criteria->toCombinationData(), 'level_number' => 1];
            $combId = $this->combinationsService->store('google', [$level]);
            $params = ['type' => 'd2c'];
            $this->insertRelationship($this->data['entityId'], $combId, $params);
        }
    }

    protected function makeWebsiteCombinations()
    {
        switch ($this->data['source']) {
            case 'facebook':
            case 'linkedin':
            case 'twitter':
            case 'websites':
            case 'angel':
                $this->makeCombinations('websites', ['websites']);
                break;

            default:
                // $this->makeCombinations('websites', ['google']);
                break;
        }
    }

    protected function makeUsernameCombinations(bool $verified, bool $isPoepleData)
    {
        if (!$this->checkUsernameWithFlags() && !$isPoepleData && $this->data['source']!='email') {
            return;
        }
        $status = $verified ? 'verified' : 'not_verified';
        $source = $this->data['source'];
        if ($isPoepleData) {
            $source = 'peopleData';
        }
        \Log::info('BRAIN: Creating Username combination from DataPoint and un is ' . $status);
        $this->combinationsMaker->set('username_source', [$source]);
        $this->combinationsMaker->set('username_status', [$status]);
        $this->combinationsMaker->set('username', $this->data['values'], 'username_dp');
        $combinations = $this->combinationsMaker
            ->withEach(['username_dp', 'username_source', 'username_status'])
            ->get();

        foreach ($combinations as $combination) {
            $criteria = $this->buildSearchCriteria($combination);
            $level = ['source' => 'usernames', 'data' => $criteria->toCombinationData(), 'level_number' => 1];
            $combId = $this->combinationsService->store('usernames', [$level]);
            $params = ['type' => 'd2c'];
            $this->insertRelationship($this->data['entityId'], $combId, $params);
        }
    }

    protected function makeCombinations(string $type, array $sources)
    {
        $resultService = loadService('result');
        $sourcesVerifiedResults = $resultService->checkForVerifiedResults($sources);
        $this->combinationsMaker->set($type, $this->data['values'], $type . '_dp');
        $this->combinationsMaker->set('has_verified_profiles', [true]);
        if ($type !== 'email' && $type !== 'phone') {
            $this->combinationsMaker->set('name', $this->name, 'name_dp');
            $combinations = $this->combinationsMaker
                ->withEach(['name_dp', $type . '_dp', 'has_verified_profiles'])
                ->get();
        } else {
            $combinations = $this->combinationsMaker
                ->withEach([$type . '_dp', 'has_verified_profiles'])
                ->get();
        }
        foreach ($sources as $source) {
            /*if (!empty($sourcesVerifiedResults[$source])) {
                \Log::info("BRAIN: Datapoint combination generator .. {$source} has already veified results .. skip");
                continue;
            }*/
            foreach ($combinations as $combination) {
                $criteria = $this->buildSearchCriteria($combination);
                if (empty($sourcesVerifiedResults[$source])) {
                    $criteria->has_verified_profiles = false;
                    \Log::info("BRAIN: Datapoint combination generator .. {$source} has no veified results");
                } else {
                    \Log::info("BRAIN: Datapoint combination generator .. {$source} has already veified results");
                }
                $level = ['source' => $source, 'data' => $criteria->toCombinationData(), 'level_number' => 1];
                $combId = $this->combinationsService->store($source, [$level]);
                $params = ['type' => 'd2c'];
                $this->insertRelationship($this->data['entityId'], $combId, $params);
            }
        }
    }

    protected function makeRelativesCombinations()
    {
        \Log::info('BRAIN: Creating Relatives Combinations from Datapoint');
        $name = $this->data['values'][0]['full_name'];
        $unique = isUniqueFullName([$name]);
        $name_status = $unique[0] ? 'unique' : 'common';
        $name = array_merge(name_parts($name), ['name_status' => $name_status]);

        $this->combinationsMaker->set('name', [$name], 'name_dp');
        $this->combinationsMaker->set('is_relative', [true]);

        if (array_key_exists('address', $this->data['values'][0])) {
            $locations = $this->getLocationWithStatus([$this->data['values'][0]['address']]);
            $this->combinationsMaker->set('location', $locations, 'location_dp');
            $combinations = $this->combinationsMaker
                ->withEach(['name_dp', 'location_dp', 'is_relative'])
                ->get();
        } else {
            $combinations = $this->combinationsMaker
                ->withEach(['name_dp', 'is_relative'])
                ->withEach(['location'])
                ->get();
        }
        \Log::debug('BRAIN: Creating Relatives Combinations', $this->data);
        $levelNumber = 1;
        $levels = [];
        foreach ($combinations as $combination) {
            $criteria = $this->buildSearchCriteria($combination);
            $criteria->middle_name = '';
            $levels[] = ['source' => 'facebook_people_search', 'data' => $criteria->toCombinationData(), 'level_number' => $levelNumber];
            $levelNumber++;
        }
        foreach ($combinations as $combination) {
            $criteria = $this->buildSearchCriteria($combination);
            $criteria->middle_name = '';
            $criteria->city = '';
            $criteria->city_status = '';
            $levels[] = ['source' => 'facebook_people_search', 'data' => $criteria->toCombinationData(), 'level_number' => $levelNumber];
            $levelNumber++;
        }
        foreach ($combinations as $combination) {
            $criteria = $this->buildSearchCriteria($combination);
            $criteria->middle_name = '';
            $criteria->city = '';
            $criteria->state = '';
            $criteria->city_status = '';
            $levels[] = ['source' => 'facebook_people_search', 'data' => $criteria->toCombinationData(), 'level_number' => $levelNumber];
            $levelNumber++;
        }
        $combId = $this->combinationsService->store('facebook_people_search', $levels);
        $params = ['type' => 'd2c'];
        $this->insertRelationship($this->data['entityId'], $combId, $params);
    }
}
