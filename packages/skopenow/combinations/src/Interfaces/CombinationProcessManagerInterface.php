<?php
namespace Skopenow\Combinations\Interfaces;

interface CombinationProcessManagerInterface
{
    /**
     * Store combination in datastore
     * @param  int      $reportId            report id of report that this combination belongs to
     * @param  int      $mainSourceId          combination source name
     * @param  array    $levels              combination levels
     * @return int                           combination id
     */
    public function store(int $reportId, string $mainSourceName, array $levels = []);

    /**
     * add new combination level to combination
     * @param int $combinationId
     * @param mixed $data          data to store with combination level
     */
    public function addCombinationLevel(int $reportId, int $combinationId, string $sourceName, $data, $levelNumber = null);
    
    /**
     * return pending cmbination levels for given report
     * @return [type] [description]
     */
    public function getPendingCombs(int $reportId);
    
    /**
     * do actions on level start for example set start time for combination
     * @param  int    $combinationLevelId [description]
     * @return [type]                     [description]
     */
    public function onLevelStart(int $combinationLevelId);

    /**
     * end combination level and enable new level if it is failed
     * @param  int     $combinationId [description]
     * @param  boolean $status        [description]
     * @return void
     */
    public function onLevelEnd(int $combinationLevelId, bool $status);
}
