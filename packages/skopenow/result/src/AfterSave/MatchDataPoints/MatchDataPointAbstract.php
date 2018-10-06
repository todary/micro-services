<?php

/**
 * Abstract Class for the different matching data points classes .
 * @author Ahmed Samir <ahmedsamir732@gmail.com>
 */
namespace Skopenow\Result\AfterSave\MatchDataPoints ;

use MatchingDataPointInterface;
use App\Models\ResultData as Result;

abstract class MatchDataPointAbstract implements MatchDataPointInterface
{
	protected $matchingService;
	
	protected $result;

	protected $relationships = [];
	
	protected $relationsFlags = array();
	
	protected $relationshipType;

	protected $scoreFlag;
	
	protected $matchedResults = array();
	
	protected $nonMatchedResults = array();

	protected $isVerifiedDataPoint = false;
	
	public function __construct(Result $result)
	{
		$this->matchingService = loadService('matching');
		$this->result = $result ;
		$this->loadRelationsFlags();
	}

	public abstract function match(\Iterator $dataPoints, array $resultsDataPoints); 

	// public abstract function matchOne(string $firstEntity , string $secondEntity);
	
	protected function formatEntity(string $entity)
	{
		$entity = strtolower(trim($entity));
		
		return $entity ;
	}
	
	protected function loadRelationsFlags()
	{
		$this->relationsFlags = loadData("relationsFlags");
	}
	
	protected function getRelationTypeFlag(string $type)
	{
		$typeValue = 0 ;
		if(isset($this->relationsFlags[$type]['value'])){
			$typeValue = $this->relationsFlags[$type]['value'];
		}
		return $typeValue ;
	}

	protected function addRelationships(int $firstEntity, array $secondEntity)
	{
		foreach ($secondEntity as $result_id) {
			if ($result_id == $firstEntity) {
				continue;
			}
			$relationship = array(
				"firstEntity"	=>	$firstEntity ,
				"secondEntity"	=>	$result_id , 
				"reason"		=>	$this->relationshipType ,
			);
			$this->relationships[] = $relationship;
		}
		return true;
	}

	protected function setRelationship(int $firstEntity , $secondEntity)
	{
		if(is_array($secondEntity)) {
			return $this->addRelationships($firstEntity, $secondEntity);
		}

		if ($secondEntity == $firstEntity) {
			return false;
		}

		$this->relationships[] = array(
			"firstEntity"	=>	$firstEntity ,
			"secondEntity"	=>	$secondEntity , 
			"reason"		=>	$this->relationshipType ,
		);
		return true;
	}

	protected function addMatchedResults(array $ids, string $identifier = null)
	{
		foreach ($ids as $id) {
			if ( (int) $id == (int) $this->result->id) {
				continue;
			}
			$this->matchedResults[] = array(
					"result_id" => $id ,
					"reason" 	=> $this->relationshipType ,
					"scoreFlag"	=>	$this->scoreFlag ,
					"identifier"=> $identifier??"",	
					"isVerifiedDataPoint" => $this->isVerifiedDataPoint,
				) ;
		}
		return true;
	}
	
	protected function setMatchedResult($result_id, string $identifier = null)
	{
		$identifier = trim(trim($identifier), '- ');
		if(is_array($result_id)) {
			return $this->addMatchedResults($result_id, $identifier);
		}

		if($result_id == $this->result_id) {
			return false;
		}

		$this->matchedResults[] = array(
					"result_id" => $result_id ,
					"reason" 	=> $this->relationshipType ,
					"scoreFlag"	=> $this->scoreFlag,
					"identifier"=> $identifier??"",	
					"isVerifiedDataPoint" => $this->isVerifiedDataPoint,
				) ;
		return true;
		
	}

	protected function addNonMatchedResults($ids)
	{
		foreach ($ids as $id) {
			$this->nonMatchedResults[] = array(
					"result_id" => $id ,
					"reason" 	=> $this->relationshipType ,
					"isVerifiedDataPoint" => $this->isVerifiedDataPoint,
				) ;
		}
		return true;
	}
	
	protected function setNonmatchedResult($result_id)
	{
		if(is_array($result_id)) {
			return $this->addNonMatchedResults($result_id);
		}

		$this->nonMatchedResults[] = array(
					"result_id" => $result_id ,
					"reason" 	=> $this->relationshipType,
					"isVerifiedDataPoint" => $this->isVerifiedDataPoint,
				) ;
	}


	public function getMatchedResults() : array
	{
		return $this->matchedResults ;
	}
	
	public function getNonMathedResults() : array
	{
		return $this->nonMatchedResults ;
	}
	
}

