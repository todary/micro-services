<?php

/**
 * Match username is one of the data points matching type , which matching the found usernames
 * from different results with each other so that we can relate results together .
 *
 * @author Ahmed Samir<ahmedsamir732@gmail.com>
 *
 */
namespace Skopenow\Result\AfterSave\MatchDataPoints ;

use Skopenow\Result\AfterSave\MatchDataPoints\MatchDataPointInterface;
use Skopenow\Result\AfterSave\MatchDataPoints\MatchDataPointAbstract ;
use App\Models\ResultData as Result ;
use App\DataTypes\Username;

class MatchUsername extends MatchDataPointAbstract
{
    public function __construct(Result $result)
    {
        parent::__construct($result);
        $this->relationshipType = $this->getRelationTypeFlag("username");
        $this->scoreFlag = "un" ;
    }


    public function match(\Iterator $dataPoints, array $resultsDataPoints)
    {
        $dataPoints->rewind();
        while ($dataPoints->valid()) {
            $dataPoint = $dataPoints->current();
            if (!empty($dataPoint)) {
                foreach ($resultsDataPoints as $resultsDataPoint) {
                    if (empty($resultsDataPoint['res'])) {
                        continue;
                    }
                    $this->isVerifiedDataPoint = $resultsDataPoint['is_verified']??false;
                    $firstEntity = $dataPoint;
                    $secondEntity = $resultsDataPoint['main_value'];
                    $status = $this->matchOne($firstEntity, $secondEntity);
                    if ($status) {
                        $this->setRelationship($this->result->id, $resultsDataPoint['res']);
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

    public function matchOne(Username $firstEntity, string $secondEntity)
    {
        $status = false ;
        $firstEntity = $this->formatEntity($firstEntity->offsetGet('username'));
        $secondEntity = $this->formatEntity($secondEntity);
        if (!empty($firstEntity) && !empty($secondEntity)) {
            if ($firstEntity == $secondEntity) {
                $status = true ;
            }
        }
        return $status ;
    }
}
