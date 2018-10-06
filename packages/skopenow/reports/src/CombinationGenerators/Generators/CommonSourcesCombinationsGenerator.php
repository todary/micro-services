<?php
namespace Skopenow\Reports\CombinationGenerators\Generators;

use Skopenow\Reports\Models\Report;
use App\Models\EmailBlacklist;
use Skopenow\Reports\Services\SourcesService;
use \Illuminate\Database\Eloquent\ModelNotFoundException;

/**
*
*/
class CommonSourcesCombinationsGenerator extends AbstractCombinationsGenerator
{
    public function make()
    {
        \Log::info('BRAIN: Common Sources Combination Generator');
        // Linkedin combination created in LinkedIn due to special search with keywords
        $locationSources = ['facebook_people_search', 'twitter', 'twitterstatus', 'google', 'soundcloud'];
        $nameSources = ['youtube', 'flickr', 'foursquare', 'instagram', 'myspace', 'pinterest', 'slideshare'];

        $this->makeNameLocationCombinations($locationSources);
        foreach ($nameSources as $nameSource) {
            $this->makeNameCombinations($nameSource);
        }

        $this->makeUsernameCombinations();
        $this->makeCustomUsernameCombinations();
    }

    public function makeUsernameCombinations()
    {
        \Log::info('BRAIN: Common Sources Generator, Creating USERNAME combs, SOURCE: usernames');
        $this->combinationsMaker->set('username_source', ['input']);
        $this->combinationsMaker->set('username_status', ['not_verified']);
        $combinations = $this->combinationsMaker
            ->withEach(['username', 'username_status', 'username_source'])
            ->get();
        $levels = [];
        $levelNumber = 1;
        foreach ($combinations as $combination) {
            $criteria = $this->buildSearchCriteria($combination);
            $levels[] = ['source' => 'usernames', 'data' => $criteria->toCombinationData(), 'level_number' => $levelNumber];
            $levelNumber++;
        }

        $this->combinationsService->store('usernames', $levels);
    }

    public function makeCustomUsernameCombinations()
    {
        \Log::info('BRAIN: Common Sources Generator, Creating Custom username combs');
        $usernames = $this->generateUsernamesFromNames($this->report->full_name);
        if (count($usernames) == 0) {
            \Log::info('BRAIN: Custom usernames no usernames generated');
            return;
        }
        $this->combinationsMaker->set('username', $usernames, 'username_generated');
        $this->combinationsMaker->set('username_source', ['generated']);
        $this->combinationsMaker->set('username_status', ['not_verified']);
        $combinations = $this->combinationsMaker
            ->withEach(['username_generated', 'username_status', 'username_source'])
            ->get();
        foreach ($combinations as $combination) {
            $criteria = $this->buildSearchCriteria($combination);

            $level = ['source' => 'usernames', 'data' => $criteria->toCombinationData(), 'level_number' => 1];
            $this->combinationsService->store('usernames', [$level]);
        }
    }

    public function makeNameLocationCombinations(array $sources)
    {
        \Log::info('BRAIN: Common Sources Generator, Creating NAME & LOC combs');
        $locationsWithSmallCity = $this->sortLocationsByCitySize($this->report->city);
        $locations = $this->getLocationWithStatus($locationsWithSmallCity);
        $isUniqueName = isUniqueFullName($this->report->full_name);
        $this->combinationsMaker->set('location', $locations);
        // if First name is uniqe -> priority to name
        if (!empty($isUniqueName) && $isUniqueName[0]) {
            $combinations = $this->combinationsMaker
                ->withEach(['name', 'location'])
                ->get();
        // if common name and small city -> priority to location
        } elseif (!empty($locations[0]['city_status']) && $locations[0]['city_status'] === 'smallCity') {
            $combinations = $this->combinationsMaker
                ->withEach(['location', 'name'])
                ->get();
        // common name and big city -> priority to name
        } else {
            $combinations = $this->combinationsMaker
                ->withEach(['name', 'location'])
                ->get();
        }
        foreach ($sources as $source) {
            \Log::info('BRAIN: Common Sources Generator, Creating NAME & LOC combs, SOURCE: ' . $source);
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
            \Log::info('BRAIN: Common Sources Generator, Creating NAME & LOC combs with State');
            foreach ($combinations as $combination) {
                $criteria = $this->buildSearchCriteria($combination);
                $criteria->city = '';
                $criteria->city_status = '';
                if ($source == 'twitter' || $source == 'twitterstatus') {
                    $criteria->distance = 2;
                }
                if (!empty($criteria->state)) {
                    $criteriaData = $criteria->toCombinationData();
                    $hashKey = md5(json_encode($criteriaData));
                    if (!array_key_exists($hashKey, $levels)) {
                        $levels[$hashKey] = ['source' => $source, 'data' => $criteriaData, 'level_number' => $levelNumber];
                        $levelNumber++;
                    }
                }
                $criteria->distance = '';
            }

            \Log::info('BRAIN: Common Sources Generator, Creating NAME SOURCE: ' . $source);
            foreach ($combinations as $combination) {
                $criteria = $this->buildSearchCriteria($combination);
                $criteria->state = '';
                $criteriaData = $criteria->toCombinationData();
                $hashKey = md5(json_encode($criteriaData));
                if (!array_key_exists($hashKey, $levels)) {
                    if ($criteria->name_status == 'unique') {
                        $this->combinationsService->store($source, [['source' => $source, 'data' => $criteriaData, 'level_number' => 1]]);
                    } else {
                        $levels[$hashKey] = ['source' => $source, 'data' => $criteriaData, 'level_number' => $levelNumber];
                        $levelNumber++;
                    }
                }
            }
            \Log::info('BRAIN: Common Sources Generator, storing NAME & LOC combs, SOURCE: ' . $source);
            $this->combinationsService->store($source, $levels);
        }
    }

    public function makeNameCombinations(string $source)
    {
        \Log::info('BRAIN: Common Sources Generator, Creating NAME combs, SOURCE: ' . $source);
        $this->createSimpleCombination(['name'], $source);
    }
}
