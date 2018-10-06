<?php
/**
 * Http Requests client code
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

use Skopenow\Combinations\SourcesService;
use Skopenow\Combinations\EntitiesService;
use Skopenow\Combinations\CombinationProcessManager;
use Skopenow\Combinations\Transformers\CombinationLevelTransformer;
use Skopenow\Combinations\Models\CombinationLevel;
use Skopenow\Combinations\Transformers\CombinationTransformer;

/**
 * Combinations Service Entry Point
 *
 * @category Micro_Services-phase_1
 * @package  Combinations Service
 * @author   Mahmoud Mgady <mahmoud.magdy@queentecksolutions.com>
 * @license  QueenTechSolutions, Inc.
 * @link     http://www.queentecksolutions.com
 **/
class EntryPoint
{
    protected $combinationProcessManager;

    public function __construct()
    {
        $sourcesService = new SourcesService();
        $entitiesService = new EntitiesService();
        $this->combinationProcessManager = new CombinationProcessManager($sourcesService, $entitiesService);
    }

    public function store(string $sourceName, array $levels = [])
    {
        $reportId = config('state.report_id');
        return $this->combinationProcessManager->store($reportId, $sourceName, $levels);
    }

    public function enableNextLevel(int $combination_id)
    {
        return $this->combinationProcessManager->enableNextLevel($combination_id);
    }

    public function addCombinationLevel(int $combinationId, string $sourceName, $data, $levelNumber = null)
    {
        $reportId = config('state.report_id');
        return $this->combinationProcessManager->addCombinationLevel($reportId, $combinationId, $sourceName, $data, $levelNumber);
    }

    /**
     * get pending combinations
     * @return [type] [description]
     */
    public function getPendingCombs()
    {
        $reportId = config('state.report_id');
        $transformer = new CombinationLevelTransformer();
        $transformer->includeCombination();
        return $transformer->transformAll($this->combinationProcessManager->getPendingCombs($reportId));
    }

    /**
     * get combination
     * @return [type] [description]
     */
    public function getCombinationById($combinationId)
    {
        $reportId = config('state.report_id');
        $transformer = new CombinationTransformer();
        $transformer->includeCombinationLevels();
        $combination = $this->combinationProcessManager->getCombinationById($combinationId);
        if (!$combination) {
            return null;
        }
        return $transformer->transform($combination);
    }

    /**
     * get combination level
     * @return [type] [description]
     */
    public function getCombinationLevelById($combinationLevelId)
    {
        $reportId = config('state.report_id');
        $transformer = new CombinationLevelTransformer();
        $transformer->includeCombination();
        $combinationLevel = $this->combinationProcessManager->getCombinationLevelById($combinationLevelId);
        if (!$combinationLevel) {
            return null;
        }
        return $transformer->transform($combinationLevel);
    }

    public function onLevelStart(int $combinationLevelId)
    {
        $this->combinationProcessManager->onLevelStart($combinationLevelId);
    }

    public function onLevelEnd(int $combinationLevelId, bool $status)
    {
        $this->combinationProcessManager->onLevelEnd($combinationLevelId, $status);
    }

    public function getSourceCombinationsCount(string $source) : int
    {
        $reportId = config('state.report_id');
        return CombinationLevel::where([
            ['report_id', $reportId],
            ['source', $source],
        ])->count();
    }
}
