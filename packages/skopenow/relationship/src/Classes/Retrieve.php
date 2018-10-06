<?php
namespace Skopenow\Relationship\Classes;

use App\Libraries\DBCriteria;
use App\Models\Entity;
use App\Models\Relationship;
use App\Models\RelationshipLinear;
use Illuminate\Support\Collection;

/**
 * Retrieve Relationship
 */
class Retrieve extends Operation
{
    public function getLinearRelationships($params)
    {
        return $this->report->relationshipsLinear()
            ->whereIn('first_party', (array) $params['first_party'])
            ->orWhereIn('second_party', (array) $params['first_party'])
            ->orWhereIn('first_party', (array) $params['second_party'])
            ->orWhereIn('second_party', (array) $params['second_party'])
            ->distinct()->get()->toArray();
    }

    public function getRelationships(array $entityIds, int $reason = null, array $excludedIds = [])
    {
        $relationships = RelationshipLinear::whereIn('first_party', $entityIds)
            ->orWhereIn('second_party', $entityIds);

        if ($reason) {
            $relationships->whereRaw("reason & {$reason} = {$reason}");
        }

        if (!empty($excludedIds)) {
            $relationships->whereNotIn('source_entity', $excludedIds)
                ->whereNotIn('target_entity', $excludedIds);
        }

        $relationships = $relationships->pluck('first_party', 'second_party')->toArray();
        $relationshipsIds = array_merge(array_keys($relationships), array_values($relationships));
        $relatedEntities = Entity::with('result', 'datapoint')->find(array_unique($relationshipsIds));

        $results = $relatedEntities->where('type', 'result')->pluck('result');
        $datapoints = $relatedEntities->where('type', 'datapoint')->pluck('datapoint');

        return compact('results', 'datapoints', 'relationships');
    }

    public function getDetailedRelationships(array $entityIds)
    {
        $relationships = $this->getRelationships($entityIds);

        $datapoints = $relationships['datapoints'];
        $results = $relationships['results'];
        $relationships = $relationships['relationships'];

        if (!$datapoints->isEmpty()) {
            $ids = $datapoints->pluck('entity_id')->toArray();
            $datapoints = $this->getDatapoints($ids);

            foreach ($datapoints as &$datapoint) {
                $datapoint['data']['main_value'] = $datapoint['main_value'];
                $datapoint = $datapoint['data'];
                $datapoint['results'] = [];
                if (!$results->isEmpty()) {
                    foreach ($relationships as $related => $relationship) {
                        if ($datapoint['id'] == $related) {
                            $relDatapoint[] = $relationship;
                        }
                        if ($datapoint['id'] == $relationship) {
                            $relDatapoint[] = $related;
                        }
                    }

                    $relatedResults = $results->filter(function ($result) use ($relDatapoint) {
                        return in_array($result->id, $relDatapoint);
                    });
                    if (!$relatedResults->isEmpty()) {
                        foreach ($relatedResults as $result) {
                            $raw_type = null;
                            if ($result->is_profile) {
                                $raw_type = 'Profile - ' . ($result->profile_name ?: $result->profile_username);
                            }

                            $datapoint['results'][] = [
                                'id' => $result->id,
                                'content' => $result->url,
                                'main_source' => $result->source,
                                'source' => $result->source,
                                'raw_type' => $raw_type ?? $result->raw_type,
                                'profile_image' => $result->profile_image,
                            ];
                        }
                        // $results->diff($relatedResults->values());
                    }
                }
            }

            $datapoints = collect($datapoints);
        }

        return compact('datapoints', 'results');
    }

    public function getDatapoints(array $ids)
    {
        $datapointService = loadService('datapoint');
        $datapointDatasource = $datapointService->datasource();

        $criteria = new DBCriteria;
        $criteria->addInCondition('id', $ids);
        return $datapointDatasource->loadData($criteria);
    }

    // check dublicates in Relationship linear
    public function entityLinearRelationshipExists($sourceEntity, $targetEntity)
    {
        $conditions = [
            ['first_party', $sourceEntity],
            ['second_party', $targetEntity],
        ];

        $orConditions = [
            ['first_party', $targetEntity],
            ['second_party', $sourceEntity],
        ];

        return RelationshipLinear::where($conditions)->orWhere($orConditions)->exists();
    }

    // check dublicates in Relationship
    public function entityRelationshipExists($sourceEntity, $targetEntity)
    {
        $conditions = [
            ['source_entity', $sourceEntity],
            ['target_entity', $targetEntity],
        ];

        $orConditions = [
            ['source_entity', $targetEntity],
            ['target_entity', $sourceEntity],
        ];

        return $this->report->relationships()->where($conditions)->orWhere($orConditions)->exists();
    }
}
