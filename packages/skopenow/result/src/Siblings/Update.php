<?php
namespace Skopenow\Result\Siblings;

use App\Models\SubResultDataInterface;
use App\Models\SubResult;
use App\Libraries\DBCriteriaInterface;
use App\Libraries\DBCriteria;

class Update
{
    public function update(array $data, DBCriteriaInterface $criteria):int
    {
        $conditions = $criteria->prepareLumenQuery();
        if (empty($conditions["raw"]) || empty($criteria->params)) {
            return 0;
        }
        $updated = SubResult::whereRaw($conditions["raw"], $criteria->params)->update($data);
        return $updated;
    }
}
