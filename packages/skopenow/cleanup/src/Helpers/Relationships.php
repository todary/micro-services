<?php

/**
 * Used as a mab mabs the connections between results .
 *
 * @author Ahmed Samir <ahmedsamir732@gmail.com>
 */

namespace Skopenow\Cleanup\Helpers;

final class Relationships
{

    protected $allRelationships = array();

    public function __construct($relationships)
    {
        $this->initialize($relationships);
    }

    protected function initialize($relationships)
    {
        $this->allRelationships = $this->AllRelationships($relationships);
    }

    protected function AllRelationships($relationships)
    {
        $allRelationships = array();
        foreach ($relationships as $key => $relationship) {
            $type = $relationship['type'];
            $reason = $relationship['reason'];
            if (!empty($relationship->relationshipsLinear)) {
                foreach ($relationship->relationshipsLinear as $linearRelationships) {
                    $allRelationships[] = array(
                        "id" => $linearRelationships['id'],
                        "report_id" => $linearRelationships['report_id'],
                        "first_party" => $linearRelationships['first_party'],
                        "second_party" => $linearRelationships['second_party'],
                        "type" => $type,
                        "reason" => $reason,
                    );
                }
            }
        }

        return $allRelationships;
    }

    /**
     * [getRelatedResults return ids of results related to the id entered]
     * @param  array  $resultsIds [description]
     * @return [type]             [description]
     */
    public function getRelatedResults(array $resultsIds, int $reason = null)
    {
        $results = array();
        foreach ($this->allRelationships as $key => $relationship) {
            if (
            	in_array($relationship['first_party'], $resultsIds) 
            	&& $this->isMatchedReason($relationship['reason'], $reason)
            ) {
                $results[] = $relationship['second_party'];
            } elseif (
            	in_array($relationship['second_party'], $resultsIds) 
            	&& $this->isMatchedReason($relationship['reason'], $reason)
            ) {
                $results[] = $relationship['first_party'];
            }
        }
        return $results;
    }

    public function isMatchedReason(int $relationReason, $comparingReason): bool
    {
    	$status = false;
        if (!$comparingReason) {
        	$status = true;
        } else {
        	if (($relationReason&$comparingReason) == $comparingReason) {
        		$status = true;
        	}
        }

        return $status;
    }

}
