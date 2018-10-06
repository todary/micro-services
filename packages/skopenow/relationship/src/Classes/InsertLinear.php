<?php
namespace Skopenow\Relationship\Classes;

use App\Models\Relationship;

trait InsertLinear
{
    protected $leftLinearTypes = ['C2R', 'D2R'];

    public function insertLinearRelationships(Relationship $relationship)
    {
        $debugData = compact('relationship');
        if ($this->isList) {
            \Log::notice('Linear Relationship skipped: true isList', $debugData);
            return;
        }

        $params = [
            'report_id' => $relationship->report_id,
            'relationship_id' => $relationship->id,
            'first_party' => $relationship->source_entity,
            'second_party' => $relationship->target_entity,
            'reason' => $relationship->reason,
        ];
        $relationshipLinear = $this->retriever
            ->entityLinearRelationshipExists($relationship->source_entity, $relationship->target_entity);
        if (!$relationshipLinear) {
            $debugData += compact('params');
            $this->report->relationshipsLinear()->create($params);
            \Log::debug('Linear relationship Init', $debugData);
        }

        $allEntityLinears = $this->retriever->getLinearRelationships($params); // RelationshipsLinear
        \Log::debug('All Linear relationships found', compact('allEntityLinears'));

        foreach ($allEntityLinears as $entityLinear) {
            if ($this->prepareLinearInsertParams($params, $relationship, $entityLinear)) {
                $relationshipLinear = $this->retriever
                    ->entityLinearRelationshipExists($params['first_party'], $params['second_party']);
                if (!$relationshipLinear) {
                    try {
                        $this->report->relationshipsLinear()->create($params);
                        $debugData = compact('params');
                        \Log::debug('Linear relationship success', $debugData);
                    } catch (\Exception $exeption) {
                        $debugData = compact('exeption', 'relationshipLinear');
                        \Log::warning('Linear relationship Exception', $debugData);
                    }
                }
            }
        }
        return true;
    }

    public function prepareLinearInsertParams(array &$params, Relationship $relationship, array $entityLinear): bool
    {
        if (!in_array($relationship->type, $this->leftLinearTypes)) {
            if ($relationship->source_entity == $entityLinear['first_party']) {
                $params['first_party'] = $relationship->target_entity;
                $params['second_party'] = $entityLinear['second_party'];
                return $relationship->target_entity != $entityLinear['second_party'] ?: false;
            }

            if ($relationship->source_entity == $entityLinear['second_party']) {
                $params['first_party'] = $relationship->target_entity;
                $params['second_party'] = $entityLinear['first_party'];
                return $relationship->target_entity != $entityLinear['first_party'] ?: false;
            }
        }

        if ($relationship->target_entity == $entityLinear['second_party']) {
            $params['first_party'] = $relationship->source_entity;
            $params['second_party'] = $entityLinear['first_party'];
            return $relationship->source_entity != $entityLinear['first_party'] ?: false;
        }

        if ($relationship->target_entity == $entityLinear['first_party']) {
            $params['first_party'] = $relationship->source_entity;
            $params['second_party'] = $entityLinear['second_party'];
            return $relationship->source_entity != $entityLinear['second_party'] ?: false;
        }

        return false;
    }
}
