<?php

/**
 * Match work is one of the data points matching type , which matching the found work experiences
 * from different results with each other so that we can relate results together .
 *
 * @author Ahmed Samir<ahmedsamir732@gmail.com>
 *
 */
namespace Skopenow\Result\AfterSave\MatchDataPoints ;

use Skopenow\Result\AfterSave\MatchDataPoints\MatchDataPointInterface;
use Skopenow\Result\AfterSave\MatchDataPoints\MatchDataPointAbstract ;
use App\Models\ResultData as Result;

class MatchWork extends MatchDataPointAbstract
{
    public function __construct(Result $result)
    {
        parent::__construct($result);
        $this->relationshipType = $this->getRelationTypeFlag("company") ;
        $this->scoreFlag = "cm" ;
    }
    
    public function match(\Iterator $dataPoints, array $resultsDataPoints)
    {
        // $relationships = array() ;
        $dataPoints->rewind();
        while ($dataPoints->valid()) {
            $dataPoint = $dataPoints->current();
            if (!empty($dataPoint['company'])) {
                foreach ($resultsDataPoints as $resultsDataPoint) {
                    if (empty($resultsDataPoint['res'])) {
                        continue;
                    }
                    $this->isVerifiedDataPoint = $resultsDataPoint['is_verified']??false;
                    $firstEntity = $dataPoint['company'];
                    $secondEntity = $resultsDataPoint['main_value'];
                    $status = $this->matchingService->matchWork($firstEntity, $secondEntity, true, []);
                    if ($status) {
                        if ($this->result->id) {
                            $this->setRelationship($this->result->id, $resultsDataPoint['res']);
                        }
                        $this->setMatchedResult($resultsDataPoint['res'], $secondEntity);
                        continue;
                    }
                    // set result into non matched result .
                    $this->setNonmatchedResult($resultsDataPoint['res']);
                }
            }
            $dataPoints->next();
        }
        return $this->relationships;
    }
}
