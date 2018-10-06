<?php

/** 
 * The place where the rescoring handled .
 * 
 * @author Ahmed Samir <ahmedsamir732@gmail.com>
 */

namespace Skopenow\Scoring\ReScore ;

use Skopenow\Scoring\EntryPoint;
use Skopenow\Scoring\Identity\IdentityScore ;

class Rescoring extends EntryPoint
{
	protected $newScoreData = array() ;
	
	protected $oldScoreData = array() ;
	
	public function resocre(array $newScoreData , array $oldScoreData)
	{
		$this->newScoreData = $newScoreData ;
		$this->oldScoreData = $oldScoreData ;
		$this->listCountRescore();
		$this->sourceTypeRescore();
		$this->resultTypeRescore();
		$this->identityRescore();
//		$this->identities = 
		
		$data = $this->formatScoreData($this);
		return $data ;
	}
	
	protected function listCountRescore()
	{
		$listCountScore = 0 ;
		if(isset($this->newScoreData['listCountScore'] , $this->oldScoreData['listCountScore'])){
			$listCountScore = max($this->newScoreData['listCountScore'] , $this->oldScoreData['listCountScore']);
		}elseif(isset($this->oldScoreData['listCountScore'])){
			$listCountScore = $this->oldScoreData['listCountScore'] ;
		}elseif(isset($this->newScoreData['listCountScore'])){
			$listCountScore = $this->newScoreData['listCountScore'] ;
		}
		$this->listCountScore = $listCountScore ;
		return $this ;
	}
	
	protected function sourceTypeRescore()
	{
		$sourceTypeScore = 0 ;
		if(isset($this->newScoreData['sourceTypeScore'] , $this->oldScoreData['sourceTypeScore'])){
			$sourceTypeScore = max($this->newScoreData['sourceTypeScore'] , $this->oldScoreData['sourceTypeScore']);
		}elseif(isset($this->oldScoreData['sourceTypeScore'])){
			$sourceTypeScore = $this->oldScoreData['sourceTypeScore'] ;
		}elseif(isset($this->newScoreData['sourceTypeScore'])){
			$sourceTypeScore = $this->newScoreData['sourceTypeScore'] ;
		}
		$this->sourceTypeScore = $sourceTypeScore ;
		return $this ;
	}
	
	protected function resultTypeRescore()
	{
		$resultTypeScore = 0 ;
		if(isset($this->newScoreData['resultTypeScore'] , $this->oldScoreData['resultTypeScore'])){
			$resultTypeScore = max($this->newScoreData['resultTypeScore'] , $this->oldScoreData['resultTypeScore']);
		}elseif(isset($this->oldScoreData['resultTypeScore'])){
			$resultTypeScore = $this->oldScoreData['resultTypeScore'] ;
		}elseif(isset($this->newScoreData['resultTypeScore'])){
			$resultTypeScore = $this->newScoreData['resultTypeScore'] ;
		}
		$this->resultTypeScore = $resultTypeScore ;
		return $this ;
	}
	
	protected function identityRescore()
	{	
		$identities = array_merge($this->oldScoreData['identities'], $this->newScoreData['identities']);
		$identifiers = [];
		if (!empty($this->oldScoreData['identifiers'])) {
			$identifiers = array_merge($identifiers,$this->oldScoreData['identifiers']);
		}
		if (!empty($this->newScoreData['identifiers'])) {
			$identifiers = array_merge($identifiers,$this->newScoreData['identifiers']);
		}

		if (!empty($this->newScoreData['identitiesShouldHave'])) {
			$identities = array_merge($identities, $this->newScoreData['identitiesShouldHave']);
		}

		if (!empty($this->newScoreData['additionalIdentifiers'])) {
			$identifiers = array_merge($identifiers, $this->newScoreData['additionalIdentifiers']);
		}

		$identityComparingScores = config("scoreIdentities");
		$identityScoreObj = new IdentityScore($identityComparingScores);
		$identityScoreObj->setIdentities($identities);
		$identityScoreObj->setIdentifiers($identifiers);

		$identityScoreObj->filterIdentities();
		$this->identities = $identityScoreObj->getIdentities();
		$this->identifiers = $identityScoreObj->getIdentifiers();
		$this->identityScore = $identityScoreObj->calculateScore();
		$flagsData = $identityScoreObj->calculateFlags();

		$this->flags = $flagsData['flags']; 
		$this->matchingFlags = $flagsData['matching_flags']; 
		$this->inputFlags = $flagsData['input_flags']; 
		$this->extraFlags = $flagsData['extra_flags'];
		return $this ;
	}
	
	
	
}

