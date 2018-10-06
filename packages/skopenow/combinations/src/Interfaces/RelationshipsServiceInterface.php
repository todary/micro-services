<?php
namespace Skopenow\Combinations\Interfaces;

interface RelationshipsServiceInterface
{
    public function setCombinationParentCombination(int $reportId, int $combinationId, int $otherCombinationId);
    public function setCombinationParentResult(int $reportId, int $combinationId, int $resultId);
}
