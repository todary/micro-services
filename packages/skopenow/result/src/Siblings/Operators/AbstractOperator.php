<?php

/**
 * abstract Operator operates over the result to return its siblings.
 *
 * @author  Ahmed Samir <ahmed.samir@queentechsolution.net>
 *
 */
namespace Skopenow\Result\Siblings\Operators;

use Skopenow\Result\Siblings\Operators\OperatorsInterface;
use App\Models\ResultData;
use App\Models\SubResultDataInterface;
use App\Models\SubResult;
use App\Models\SubResultData;
use App\Models\Entity;

abstract class AbstractOperator implements OperatorsInterface
{
    protected $result;

    public function __construct(ResultData $result)
    {
        $this->result = $result;
    }

    public function getDefaultSiblings(): \Iterator
    {
        return new \ArrayIterator();
    }

    public function save(SubResultDataInterface $result): bool
    {
        try {
            $subResult = new SubResult();
            $subResult->CreateFromSubResultData($result);
            return true;
        } catch (\Exception $ex) {
            if (stripos($ex->getMessage(), "Duplicate entry") !== false
                || stripos($ex->getMessage(), "Cannot add or update a child row") !== false
                || stripos($ex->getMessage(), "UNIQUE constraint failed") !== false
            ) {
                $data["is_deleted"] = $result->isDelete;
                
                if ($this->update($data, $result->url)) {
                    return true;
                }
                return false;
            }

            throw($ex);
        }
        return false;
    }

    public function saveBulk(\Iterator $results): bool
    {
        $status = false;
        foreach ($results as $key => $result) {
            if ($result instanceof SubResultDataInterface) {
                $this->save($result);
                $status = true;
            }
        }

        return $status;
    }

    protected function buildResult(string $url): SubResultDataInterface
    {
        $report_id = config('state.report_id');
        $entity = $this->createEntity($report_id);
        $result = new SubResultData($url);
        $result->entity_id = $entity->id;
        $result->report_id = $report_id;

        return $result;
    }

    protected function createEntity(int $report_id): Entity
    {
        $entity = new Entity();
        $entity->type = 'result';
        $entity->report_id = $report_id;
        $entity->save();
        return $entity;
    }

    public function update(array $data, string $url = null, int $id = null):int
    {
        if (is_null($url) && is_null($is)) {
            return false;
        }

        $output = SubResult::where(function ($q) use ($url, $id) {
            if (!is_null($url)) {
                $q->where("url", $url);
            }

            if (!is_null($id)) {
                $q->where("id", $id);
            }
        })->update($data);

        return $output;
    }

    public function prepareParentChilds(array $childs)
    {
    	$data = new \ArrayIterator();
    	$parentResult = $this->buildResult($this->result->url);
    	$parentResult->type = 'result';
        $parentResult->parent_id = $this->result->id;
        $parentResult->rank = 0;
        $parentResult->is_parent = 1;
        $data->append($parentResult);
        foreach ($childs as $child) {
            $subResult = $this->buildResult($child->url);
            $subResult->type = $this->exploreResultType($parentResult->mainSource, $child->url);
            $subResult->parent_id = $this->result->id;
            // $subResult->rank = $key;
            $data->append($subResult);
        }

        return $data;
    }

    protected function exploreResultType(string $mainSource, string $url)
    {
    	$type = 'result';
    	if ($mainSource == 'facebook') {
    		$type = 'post';
    	} elseif ($mainSource == 'twitter') {
    		$type = 'status';
    	}

    	return $type;
    }
}
