<?php
namespace App\Libraries;

use App\Libraries\DBCriteriaInterface;
/**
 * summary
 */
class DBCriteria extends BridgeCriteria implements DBCriteriaInterface 
{
    /**
     * [prepareLumenQuery description]
     * 
     * @return array [description]
     */
    public function prepareLumenQuery()
    {
		$query = [];

		$query["select"] = $this->select;

		if ($this->condition){
			$query["raw"] = $this->condition;	
		}

		$query["limit"] = 500;
		if ($this->limit > 0) {
			$query["limit"] = $this->limit;
		}

		$query["offset"] = 0;
		if ($this->offset > 0) {
			$query["offset"] = $this->offset;
		}
		
		$query["order"] = $this->order;

		$group = explode(",", $this->group);

		$queryGroup = [];
		for ($i=0;$i<count($group);$i++) {
			if (!empty($group[$i])) {
				$queryGroup[$i] = trim($group[$i]);
			}
		}

		if (!empty($queryGroup)) {
			$query["group"] = $queryGroup;
		}
		

		return $query;		    	
    }

    
}
