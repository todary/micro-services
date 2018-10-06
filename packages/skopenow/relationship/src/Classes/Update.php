<?php
namespace Skopenow\Relationship\Classes;

use App\Models\Relationship;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Update Relationship
 */
class Update extends Operation
{
    use InsertLinear;

    public function __construct(Retrieve $retriever)
    {
        parent::__construct();
        $this->retriever = $retriever;
    }

    public function updateRelationship(Relationship $relationship, array $updateParams): int
    {
        $debugData = compact('relationship', 'updateParams');
        Log::debug('relationship update start', $debugData);
        $relationship->update($updateParams);
        Log::info('relationship updated');
        $this->insertLinearRelationships($relationship);
        return $relationship->first()->id;
    }

    public function updateReasonIfDublicate(int $sourceEntity, int $targetEntity, string $reason): bool
    {
        $debugData = compact('sourceEntity', 'targetEntity', 'reason');
        Log::debug('relationship update reason start', $debugData);
        $conditions = [
            ['source_entity', $sourceEntity],
            ['target_entity', $targetEntity],
        ];

        $orConditions = [
            ['source_entity', $targetEntity],
            ['target_entity', $sourceEntity],
        ];
        $relationship = $this->report->relationships()
            ->where($conditions)
            ->orWhere($orConditions);

        // if the provided reason not null then do update
        if ($reason) {
            $this->updateRelationship($relationship->first(), ['reason' => DB::raw("reason|$reason")]);
            return true;
        }
        Log::warning('relationship update skipped', $debugData);
        return false;
    }
}
