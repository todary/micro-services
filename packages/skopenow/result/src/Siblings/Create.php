<?php
namespace Skopenow\Result\Siblings;

use App\Models\ResultData;
use App\Models\Result;
use Skopenow\Result\Siblings\Operators\OperatorsInterface;
use Skopenow\Result\Siblings\Operators\FacebookOperator;
use Skopenow\Result\Siblings\Operators\TwitterOperator;

class Create
{
    public function createDefaultSiblings(ResultData $result): bool
    {
        $operator = $this->getOperator($result);
        if ($operator instanceof OperatorsInterface) {
            $data = $operator->getDefaultSiblings($result);
            return $operator->saveBulk($data);
        }

        return false;
    }

    public function saveParentChilds(Result $parent, array $childs)
    {
    	$parent = ResultData::fromModel($parent);
    	$operator = $this->getOperator($parent);
    	if ($operator instanceof OperatorsInterface) {
            $data = $operator->prepareParentChilds($childs);
            return $operator->saveBulk($data);
        }
    }

    public function getOperator(ResultData $result)
    {
        $main_source = $result->mainSource;
        switch ($main_source) {
            case 'facebook':
                $operator = new FacebookOperator($result);
                break;
            case 'twitter':
            	$operator = new TwitterOperator($result);
            	break;
            
            default:
                $operator = null;
                break;
        }

        return $operator;
    }
}
