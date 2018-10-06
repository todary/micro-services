<?php
namespace Skopenow\Relationship\Classes;

use App\Models\Entity;
use App\Models\Relationship;
use Illuminate\Support\Facades\Log;

/**
 * Insert Operation
 */
class Insert extends Operation
{
    use InsertLinear;
    /**
     * Insert Operation constructor
     */
    public function __construct(Retrieve $retriever, Update $updater)
    {
        parent::__construct();
        $this->retriever = $retriever;
        $this->updater = $updater;
    }

    public function setRelationshipWithIds(int $firstEntityId, int $secondEntityId, array $params)
    {
        $sourceEntity = Entity::find($firstEntityId);
        $targetEntity = Entity::find($secondEntityId);
        if (!$sourceEntity || !$targetEntity) {
            $debugData = compact('firstEntityId', 'secondEntityId', 'sourceEntity', 'targetEntity');
            Log::warning('Relationship skipped: Entity Not found', $debugData);

            return null;
        }
        Log::info('Relationship Insert start');
        return $this->setRelationship($sourceEntity, $targetEntity, $params);
    }

    // Main callable
    public function setRelationship(Entity $sourceEntity, Entity $targetEntity, array $params)
    {
        try {
            $debugData = compact('sourceEntity', 'targetEntity');
            if ($sourceEntity->id == $targetEntity->id) {
                Log::warning('Relationship skipped: Same Entity', $debugData);
                return false;
            }
            // if dublicate throw exception
            if ($this->retriever->entityRelationshipExists($sourceEntity->id, $targetEntity->id)) {
                Log::notice('Relationship exists', $debugData);
                throw new \UnexpectedValueException('Duplicate entry found');
            }

            $debugData += compact('params');
            if (empty($params['reason'])) {
                $params['reason'] = 0;
            }

            $relationshipFlags = loadData('relationsFlags');
            if (isset($params['reason_flags'])) {
                if (isset($params['is_relative'])) {
                    $params['reason'] = $relationshipFlags['relative']['value'] | $params['reason_flags'];
                } else {
                    $params['reason'] = $params['reason_flags'];
                }
            } elseif (isset($params['is_relative'])) {
                $params['reason'] = $relationshipFlags['relative']['value'];
            }

            if (isset($params['source']) && $params['source'] == 'list') {
                $params['reason'] = $params['reason'] | $relationshipFlags['list']['value'];
                $this->isList = true;
            }

            $insertParams = array(
                'source_entity' => $sourceEntity->id,
                'target_entity' => $targetEntity->id,
                'reason' => $params['reason'],
                'type' => $params['type'] ?? 'R2R',
            );
            Log::debug('Relationship params', $params);

            $ret = $this->insertRelationship($insertParams);
            Log::info('Relationship Inserted = '.$ret);
            return $ret;
        } catch (\UnexpectedValueException $e) {
            $ret = $this->updater
                ->updateReasonIfDublicate($sourceEntity->id, $targetEntity->id, $params['reason'] ?? 0);
            Log::debug('Relationship Updated', $debugData);
            return $ret;
        }
    }

    public function insertRelationship(array $insertParams)
    {
        $relationship = $this->report->relationships()->firstOrCreate($insertParams);
        $debugData = compact('relationship', 'insertParams');
        Log::debug('Linear Relationship Insert start', $debugData);
        $this->insertLinearRelationships($relationship);
        return $relationship->id;
    }
}
