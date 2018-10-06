<?php

/** 
 * This is the place where we add the score the identities of the result matching status .
 * Matching Data[
 *	name => [
 *		status => bool (true | false) ,
 *		identities => array [
 *			fn	=> bool (true , false) ,
 *			mn	=> bool (true, false),
 *			ln	=> bool (true, false),
 *			otherName	=> bool (true, false),
 *		],
 *		matchWith => string "Ahmed Samir" ,
 *  ],
 *	location => [
 *		status => bool ,
 *		identities => array [
 *			exct-sm => bool , 
 *			exct-bg => bool , 
 *		]
 *		matchWith => string  "Oyster Bay ,NY" ,
 *	]
 * ]
 * @author Ahmed Samir <ahmedsamir732@gmail.com>
 */

namespace Skopenow\Scoring\Identity ;

class IdentityScore 
{
	
	protected $identityScores = array() ;
	
	protected $resultIdentities = array() ;
	
	protected $identifiers = array() ;

	protected $onlyOne = false;

	protected $is_relative = false;

	protected $identitiesShouldHave = [];

	protected $additionalIdentifiers = [];
	
	public function __construct(array $identityScores) {
		$this->identityScores = $identityScores ;
	}
	
	public function init(array $matchingData) : array
	{
		$scoreData = array(
			"score"	=> 0 ,
			"identities" => array(),
			"identifiers" => array() ,
		);
		try{
			foreach ($matchingData as $key => $value){
				$this->getItemIdentities($value) ;
				$this->identifiers[] = $value['matchWith'] ;
			}
			if ($this->onlyOne) {
				$this->resultIdentities[] = 'onlyOne';
			}

			if ($this->is_relative) {
				$this->resultIdentities[] = 'rltv';
			}
			$this->processIdentitiesShouldHave();

			$this->resultIdentities = array_merge($this->resultIdentities,$this->identitiesShouldHave);

			$this->filterIdentities();
			$scoreData['score'] = $this->calculateScore();
			$scoreData['identities'] = $this->resultIdentities ;
			$scoreData['identifiers'] = array_filter($this->identifiers);
			if (empty($scoreData['identifiers'])) {
				$scoreData['identifiers'] = $this->additionalIdentifiers;
			}
			$flagsData = $this->calculateFlags();
			$scoreData['flags'] = $flagsData['flags']; 
			$scoreData['matching_flags'] = $flagsData['matching_flags']; 
			$scoreData['input_flags'] = $flagsData['input_flags']; 
			$scoreData['extra_flags'] = $flagsData['extra_flags']; 
			return $scoreData ;
		} catch (\Exception $ex) {
			throw new \Exception($ex->getMessage());
		}
	}
	
	protected function getItemIdentities(array $itemData)
	{
		if ($itemData['status']){
			foreach ($itemData['identities'] as $key => $value){
				if($value == true){
					$this->resultIdentities[] =  $key;
				}
			}
		} elseif (isset($itemData['found_name']) && !$itemData['found_name']) {
			$this->resultIdentities[] =  'name_not_found';
		} elseif (isset($itemData['found_location']) && !$itemData['found_location']) {
			$this->resultIdentities[] =  'loc_not_found';
		}
		return $this ;
	}


	public function filterIdentities()
	{
		$unsetIndexes = array() ;
		if(in_array("exct-sm", $this->resultIdentities) && in_array("exct-bg", $this->resultIdentities)){
			$unsetIndexes[] = array_search("exct-bg", $this->resultIdentities);
		}
		if((in_array("exct-sm", $this->resultIdentities) || in_array("exct-bg", $this->resultIdentities)) 
				&& in_array("pct", $this->resultIdentities)){
			$unsetIndexes[] = array_search("pct", $this->resultIdentities);
		}
		
		$this->resultIdentities = $this->unsetIndexes($this->resultIdentities, $unsetIndexes);
		$this->resultIdentities = array_unique($this->resultIdentities);
		return $this ;
	}
	
	protected function unsetIndexes($arr , $indexes)
	{
		foreach ($indexes as $index)
		{
			if(isset($arr[$index])){
				unset($arr[$index]);
			}
		}
		
		$arr = array_values($arr);
		return $arr ;
	}
	
	public function calculateFlags()
	{
		$scoringFlags = loadData("scoringFlags") ;
		$identityFlags = new IdentityFlags($scoringFlags) ;
		$flags = $identityFlags->getAllFlags($this->resultIdentities);
		return $flags ;
	}


	public function calculateScore()
	{
		$score = 0 ;
		foreach ($this->resultIdentities as $key => $identity){
			$index = array_search($identity, array_column($this->identityScores, "type"));
			if($index !== false){
				$score += $this->identityScores[$index]->score ;
			}
		}

		return $score ;
	}

	public function setOnlyOne(bool $status): self
	{
		$this->onlyOne = $status;
		return $this;
	}

	public function setIsRelative(bool $is_relative)
	{
		$this->is_relative = $is_relative;
	}
	
	public function setIdentities($identities)
	{
		$this->resultIdentities = $identities ;
		return $this ;
	}

	public function setIdentifiers(array $identifiers): self
	{
		$this->identifiers = $identifiers;
		return $this;
	}

	public function setIdentitiesShouldHave(array $identitiesShouldHave): self
	{
		$this->identitiesShouldHave = $identitiesShouldHave;

		return $this;
	}

	public function setAdditionalIdentifiers(array $additionalIdentifiers): self
	{
		$this->additionalIdentifiers = $additionalIdentifiers;

		return $this;
	}

	public function getIdentifiers(): array
	{
		return array_filter(array_unique($this->identifiers));
	}
	
	public function getIdentities()
	{
		return $this->resultIdentities ;
	}

	public function processIdentitiesShouldHave()
	{
		if (!empty($this->identitiesShouldHave)) {
			$keys = array_flip($this->identitiesShouldHave);
			if(isset($keys['rltvWithRltv']) && !$this->is_relative) {
				$this->identitiesShouldHave[] = 'rltvWithMain';
			}
		}
	}
	
}

