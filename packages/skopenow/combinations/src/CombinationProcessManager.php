<?php
/**
 * Combination Process Manager
 *
 * PHP version 7.0
 *
 * @category Micro_Services-phase_1
 * @package  Combinations Service
 * @author   Mahmoud Mgady <mahmoud.magdy@queentecksolutions.com>
 * @license  QueenTechSolutions, Inc.
 * @link     http://www.queentecksolutions.com
 **/
namespace Skopenow\Combinations;

use Skopenow\Combinations\Interfaces\EntitiesServiceInterface;
use Skopenow\Combinations\Interfaces\SourcesServiceInterface;
use Skopenow\Combinations\Interfaces\CombinationProcessManagerInterface;

use Skopenow\Combinations\Models\Combination;
use Skopenow\Combinations\Models\CombinationLevel;

/**
*
*/
class CombinationProcessManager implements CombinationProcessManagerInterface
{
    protected $sourcesService;
    protected $entitiesService;

    public function __construct(
        SourcesServiceInterface $sourcesService,
        EntitiesServiceInterface $entitiesService
    ) {
        $this->sourcesService = $sourcesService;
        $this->entitiesService = $entitiesService;
    }

    public function store(int $reportId, string $sourceName, array $levels = [])
    {
        $source = $this->sourcesService->getSourceByName($sourceName);

        if (!$source) {
            throw new \Exception('Unkown Source '. $source);
        }

        $entityId = $this->entitiesService->createCombinationEntity($reportId);

        $combData = [
            'id' => $entityId,
            'report_id' => $reportId,
            'source_id' => $source->id,
            // 'unique_name' => $data['unique_name']??null,
            // 'big_city' => $data['big_city']??null,
            // 'is_generated' => $data['is_generated']??null,
            // 'additional' => $data['additional']??null,
            // 'username' => $data['username']??null,
            // 'extra_data' => $data['extra_data']??null,
        ];

        if ($version = config('state.version')) {
            $combData['version'] = $version;
        }

        $combination = Combination::create($combData);

        $toStoreLevels = [];
        foreach ($levels as $level) {
            $combinationInfo = [
                'comb_id' => $entityId,
                'level_no' => $level['level_number'],
                'source' => $level['source'],
                'data' => json_encode($level['data']),
                'has_verified_profiles' => $level['data']['has_verified_profiles']??0,
                'report_id' => $reportId,
                'enabled' => $level['level_number']==1 ? 1 : 0,
                'time' => time(),
            ];
            $combinationInfo = $this->addHash($combinationInfo);
            $toStoreLevels[] = $combinationInfo;
            $toStoreLevel = $combinationInfo;

            try {
                \DB::table('combination_level')->insert($toStoreLevel);
            } catch (\Exception $ex) {
                return null;
            }
        }

        // CombinationLevel::create($toStoreLevels);
        // \DB::table('combination_level')->insert($toStoreLevels);
        return $entityId;
    }

    public function addCombinationLevel(int $reportId, int $combinationId, string $sourceName, $data, $levelNumber = null)
    {
        if (!$levelNumber) {
            $maxLevelNo = CombinationLevel::where('comb_id', $combinationId)
                ->where('report_id', $reportId)
                ->max('level_no');
            $levelNumber = $maxLevelNo + 1;
        }

        try {
            $combinationInfo = [
                'comb_id' => $combinationId,
                'level_no' => $levelNumber,
                'source' => $sourceName,
                'data' => json_encode($data),
                'has_verified_profiles' => $level['data']['has_verified_profiles']??0,
                'report_id' => $reportId,
                'enabled' => $levelNumber==1 ? 1 : 0,
                'time' => time(),
            ];
            $combinationInfo = $this->addHash($combinationInfo);
            $combinationLevel = CombinationLevel::create($combinationInfo);

            return $combinationLevel->id;
        } catch (\Exception $ex) {
            return null;
        }
    }

    /**
     * get pending combinations
     * @return [type] [description]
     */
    public function getPendingCombs(int $reportId = null)
    {
        $combLevelModel = new CombinationLevel();

        if ($reportId) {
            $combLevelModel = $combLevelModel->where('report_id', $reportId);
        }

        $combs = $combLevelModel->where('enabled', 1)
            ->whereNull('end_time')
            ->get();

        return $combs;
    }

    /**
     * get combination
     * @return [type] [description]
     */
    public function getCombinationById(int $combinationId)
    {
        $combination = Combination::with(['levels'])->find($combinationId);

        return $combination;
    }

    /**
     * get combination level
     * @return [type] [description]
     */
    public function getCombinationLevelById(int $combinationLevelId)
    {
        $combLevel = CombinationLevel::with(['combination'])->find($combinationLevelId);

        return $combLevel;
    }

    public function enableNextLevel(int $combination_id)
    {
        $currentCombinationLevel = CombinationLevel::where('comb_id', $combination_id)
                                    ->where('enabled', 1)
                                    ->orderBy('id', 'desc')->first();

        if (!empty($currentCombinationLevel)) {
            return CombinationLevel::where('comb_id', $combination_id)
                        ->where('level_no', $currentCombinationLevel['level_no']+1)
                        ->update(['enabled' => 1]);
        }
        return false;
    }

    public function onLevelStart(int $combinationLevelId)
    {
    }

    public function onLevelEnd(int $combinationLevelId, bool $status)
    {
        $combinationLevel = CombinationLevel::find($combinationLevelId);
        $combinationLevel->update([
                'is_completed' => 1,
                'end_time' => \DB::raw('now()')
            ]);

        if (!$status) {
            CombinationLevel::where('comb_id', $combinationLevel->comb_id)
                        ->where('level_no', $combinationLevel->level_no+1)
                        ->update(['enabled' => 1]);
        }

        try {
            \DB::update("update persons set last_combination_run = now() where id = {$combinationLevel->report_id}");
        } catch (\Exception $ex) {
        }
    }

    protected function addHash(array $data)
    {
        $filteredData = json_decode($data['data'], true);
        if (!$filteredData) {
            $filteredData = [];
        }
        unset($filteredData['name_status']);
        unset($filteredData['city_status']);
        unset($filteredData['username_source']);
        unset($filteredData['username_status']);
        $data['combinations_hash'] = sha1($data['source'] . '_' . json_encode($filteredData));
        return $data;
    }
}
