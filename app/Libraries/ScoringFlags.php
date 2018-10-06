<?php
/**
 * Indicates the matching status flags.
 *
 * @author Ahmed Samir <ahmedsamir732@gmail.com>
 */
namespace App\Libraries;

use App\Models\Result;

class ScoringFlags
{
	/**
	 * @const for first name matching flag.
	 */
	const FN_MATCH 		= 0b000000000000001;

	/**
	 * @const for middle name matching flag.
	 */
	const MN_MATCH 		= 0b000000000000010;

	/**
	 * @const for last name matching flag.
	 */
	const LN_MATCH 		= 0b000000000000100;

	/**
	 * @const for small city matching flag.
	 */
	const SM_MATCH 		= 0b000000000001000;

	/**
	 * @const for big city matching flag.
	 */
	const BG_MATCH 		= 0b000000000010000;

	/**
	 * @const for partial city matching flag.
	 */
	const PCT_MATCH 	= 0b000000000100000;

	/**
	 * @const for state matching flag.
	 */
	const ST_MATCH 		= 0b000000001000000;

	/**
	 * @const for company matching flag.
	 */
	const CM_MATCH 		= 0b000000010000000;

	/**
	 * @const for work position matching flag.
	 */
	const PO_MATCH 		= 0b000000100000000;

	/**
	 * @const for school matching flag.
	 */
	const SC_MATCH 		= 0b000001000000000;

	/**
	 * @const for email matching flag.
	 */
	const EM_MATCH 		= 0b000010000000000;

	/**
	 * @const for phone matching flag.
	 */
	const PH_MATCH		= 0b000100000000000;

	/**
	 * @const for address matching flag.
	 */
	const ADDR_MATCH	= 0b001000000000000;

	/**
	 * @const for age matching flag.
	 */
	const AGE_MATCH 	= 0b010000000000000;

	/**
	 * @const for username matching flag.
	 */
	const UN_MATCH 		= 0b1000000000000000;

	protected $matchingFlags = [
		'fn'		=>	self::FN_MATCH,
		'mn'		=>	self::MN_MATCH,
		'ln'		=>	self::LN_MATCH,
		'exct-sm'	=>	self::SM_MATCH,
		'exct-bg'	=>	self::BG_MATCH,
		'pct'		=>	self::PCT_MATCH,
		'st'		=>	self::ST_MATCH,
		'cm'		=>	self::CM_MATCH,
		'po'		=>	self::PO_MATCH,
		'sc'		=>	self::SC_MATCH,
		'em'		=>	self::EM_MATCH,
		'ph'		=>	self::PH_MATCH,
		'addr'		=>	self::ADDR_MATCH,
		'age'		=>	self::AGE_MATCH,
		'un'		=>	self::UN_MATCH,
	];

	protected $matchingFlagsDefinitions = [
		self::FN_MATCH		=>	['name'	=>	'first name match'	, 'description'	=>	''],
		self::MN_MATCH		=>	['name'	=>	'middle name match'	, 'description'	=>	''],
		self::LN_MATCH		=>	['name'	=>	'last name match'	, 'description'	=>	''],
		self::SM_MATCH		=>	['name'	=>	'small city match'	, 'description'	=>	''],
		self::BG_MATCH		=>	['name'	=>	'big city match'	, 'description'	=>	''],
		self::PCT_MATCH		=>	['name'	=>	'partial city match', 'description'	=>	''],
		self::ST_MATCH		=>	['name'	=>	'state match'		, 'description'	=>	''],
		self::CM_MATCH		=>	['name'	=>	'company match'		, 'description'	=>	''],
		self::PO_MATCH		=>	['name'	=>	'position match'	, 'description'	=>	''],
		self::SC_MATCH		=>	['name'	=>	'school match'		, 'description'	=>	''],
		self::EM_MATCH		=>	['name'	=>	'email match'		, 'description'	=>	''],
		self::PH_MATCH		=>	['name'	=>	'phone match'		, 'description'	=>	''],
		self::ADDR_MATCH	=>	['name'	=>	'address match'		, 'description'	=>	''],
		self::AGE_MATCH		=>	['name'	=>	'age match'			, 'description'	=>	''],
		self::UN_MATCH		=>	['name'	=>	'username match'	, 'description'	=>	''],
	];


	const INPUT_NAME 		= 0b0000000001;
	const INPUT_LOCATION 	= 0b0000000010;
	const INPUT_COMPANY 	= 0b0000000100;
	const INPUT_SCHOOL 		= 0b0000001000;
	const INPUT_EMAIL 		= 0b0000010000;
	const INPUT_PHONE 		= 0b0000100000;
	const INPUT_ADDRESS 	= 0b0001000000;
	const INPUT_AGE 		= 0b0010000000;
	const INPUT_USERNAME 	= 0b0100000000;

	protected $inputFlags = [
		'input_name'=>	self::INPUT_NAME,
		'input_loc'	=>	self::INPUT_LOCATION,
		'input_cm'	=>	self::INPUT_COMPANY,
		'input_sc'	=>	self::INPUT_SCHOOL,
		'input_em'	=>	self::INPUT_EMAIL,
		'input_ph'	=>	self::INPUT_PHONE,
		'input_addr'=>	self::INPUT_ADDRESS,
		'input_age'	=>	self::INPUT_AGE,
		'input_un'	=>	self::INPUT_USERNAME,
	];

	protected $InputFlagsDefinitions = [
		self::INPUT_NAME		=>	['name'	=>	'first name match'	, 'description'	=>	''],
		self::INPUT_LOCATION	=>	['name'	=>	'middle name match'	, 'description'	=>	''],
		self::INPUT_COMPANY		=>	['name'	=>	'last name match'	, 'description'	=>	''],
		self::INPUT_SCHOOL		=>	['name'	=>	'small city match'	, 'description'	=>	''],
		self::INPUT_EMAIL		=>	['name'	=>	'big city match'	, 'description'	=>	''],
		self::INPUT_PHONE		=>	['name'	=>	'partial city match', 'description'	=>	''],
		self::INPUT_ADDRESS		=>	['name'	=>	'state match'		, 'description'	=>	''],
		self::INPUT_AGE			=>	['name'	=>	'company match'		, 'description'	=>	''],
		self::INPUT_USERNAME	=>	['name'	=>	'position match'	, 'description'	=>	''],
	];


	const ONLY_ONE 					= 0b000000001;
	const USERNAME_VERIFIED 		= 0b000000010;
	const PEOPLE_USERNAME 			= 0b000000100;
	const IS_RELATIVE 				= 0b000001000;
	const RELATIVE_WITH_MAIN		= 0b000100000;
	const RELATIVE_WITH_RELATIVE	= 0b001000000;
	const NOT_FOUND_NAME			= 0b001000000;
	const NOT_FOUND_LOCATION		= 0b001000000;

	protected $extraFlags = [
		'onlyOne'			=>	self::ONLY_ONE,
		'verified_un'		=>	self::USERNAME_VERIFIED,
		'rltv'				=>	self::IS_RELATIVE,
		'people_un'			=>	self::PEOPLE_USERNAME,
		'rltvWithMain'		=>	self::RELATIVE_WITH_MAIN,
		'rltvWithRltv'		=>	self::RELATIVE_WITH_RELATIVE,
		'name_not_found'	=>	self::NOT_FOUND_NAME,
		'loc_not_found'		=>	self::NOT_FOUND_LOCATION,
	];

	protected $extraFlagsDefinitions = [
		self::ONLY_ONE					=>	['name'	=>	'only one'					, 'description'	=>	''],
		self::USERNAME_VERIFIED			=>	['name'	=>	'username verified'			, 'description'	=>	''],
		self::PEOPLE_USERNAME			=>	['name'	=>	'people username'			, 'description'	=>	''],
		self::IS_RELATIVE				=>	['name'	=>	'is relative'				, 'description'	=>	''],
		self::RELATIVE_WITH_MAIN		=>	['name'	=>	'related with main profile'	, 'description'	=>	''],
		self::RELATIVE_WITH_RELATIVE	=>	['name'	=>	'relatd with relative profile', 'description'	=>	''],
		self::NOT_FOUND_NAME			=>	['name'	=>	'name not found'		, 'description'	=>	''],
		self::NOT_FOUND_LOCATION		=>	['name'	=>	'location not found'		, 'description'	=>	''],
	];



	public function getFlagsFromIdentities(array $identities): array
	{
		$flags = ['matching_flags'	=>	0, 'input_flags'	=>	0, 'extra_flags'	=>	0];
		foreach ($identities as $identity) {
			$identityFlags = $this->getIdentityGroup($identity);
			if (!empty($identityFlags['type']) && !empty($identityFlags['matchingData'])) {
				$flags[$identityFlags['type']] = $flags[$identityFlags['type']] | $identityFlags['matchingData'][$identity];
			}
		}

		return $flags;
	}

	public function check($identities, int $matchingFlags, int $inputFlags, int $extraFlags)
	{
		if (!is_array($identities)) {
			$identities = [$identities];
		}
		$flags = $this->getFlagsFromIdentities($identities);
		$status = false;

		if (
			($this->match($matchingFlags, $flags['matching_flags']) && $matchingFlags)
			&& ($this->match($inputFlags, $flags['input_flags']) && $inputFlags)
			&& ($this->match($extraFlags, $flags['extra_flags']) && $extraFlags)
		) {
			$status = true;
		}

		// dump([$matchingFlags,$flags['matching_flags'],$this->match($matchingFlags, $flags['matching_flags'])]);
		// dump([$inputFlags, $flags['input_flags'],$this->match($inputFlags, $flags['input_flags'])]);
		// dump([$extraFlags, $flags['extra_flags'],$this->match($extraFlags, $flags['extra_flags'])]);
		// dump($status);


		return $status;
	}

	public function checkByResult($identities, Result $result)
	{
		if (!is_array($identities)) {
			$identities = [$identities];
		}
		
		$status = $this->check($identities, $result->matching_flags, $result->input_flags, $result->extra_flags);

		return $status;
	}

	public function checkBulk(array $checkingIdentities, int $matchingFlags, int $inputFlags, int $extraFlags)
	{
		$status = false ;
		foreach ($checkingIdentities as $identities) {
			if ($this->check($identities, $matchingFlags, $inputFlags, $extraFlags)) {
				$status = true ;
				break ;
			}
		}
		return $status ;
	}

	public function checkBulkByResult(array $checkingIdentities, Result $result)
	{
		$status = false ;
		foreach ($checkingIdentities as $identities) {
			if ($this->checkByResult($identities, $result)) {
				$status = true ;
				break ;
			}
		}
		return $status ;
	}

	protected function match(int $firstFlag, int $secondFlag)
	{
		$status = false;
		if (!$firstFlag || !$secondFlag) {
			$status = true;
		} else {
			if (($firstFlag&$secondFlag) == $secondFlag) {
				$status = true;
			}
		}

		return $status;
	}


	protected function getIdentityGroup(string $identity): array
	{
		$data = ['type' => '', 'matchingData'	=>	[]];
		switch ($identity) {
			case $this->isMatchingFlag($identity):
				$data['type'] = 'matching_flags';
				$data['field'] = 'matching_flags';
				$data['matchingData'] = $this->matchingFlags;
				break ;
			case $this->isInputFlag($identity):
				$data['type'] = 'input_flags';
				$data['field'] = 'input_flags';
				$data['matchingData'] = $this->inputFlags;
				break ;
			case $this->isExtraFlag($identity):
				$data['type'] = 'extra_flags';
				$data['field'] = 'extra_flags';
				$data['matchingData'] = $this->extraFlags;
				break ;
			
			default:
				$data = ['type' => '', 'matchingData'	=>	[]];
				break;
		}

		return $data;
	}

	private function isMatchingFlag(string $identity): bool
	{
		$status = false;
		$matchingIdentities = array_keys($this->matchingFlags);
		if (in_array($identity, $matchingIdentities, true)) {
			$status = true;
		}

		return $status;
	}

	private function isInputFlag(string $identity): bool
	{
		$status = false;
		$matchingIdentities = array_keys($this->inputFlags);
		if (in_array($identity, $matchingIdentities, true)) {
			$status = true;
		}

		return $status;
	}

	private function isExtraFlag(string $identity): bool
	{
		$status = false;
		$matchingIdentities = array_keys($this->extraFlags);
		if (in_array($identity, $matchingIdentities, true)) {
			$status = true;
		}

		return $status;
	}

	public function convertFlagsIntoQuery(array $identities, bool $sameContext = false): string
	{
		$query = '';
		foreach ($identities as $identity) {
			if (is_string($identity) && !$sameContext) {
				$group = $this->getIdentityGroup($identity);
				if (empty($group['field'])) {
					continue;
				}

				$query .= " (({$group['field']}&{$group['matchingData'][$identity]}) = {$group['matchingData'][$identity]}) or ";
			} elseif (is_string($identity) && $sameContext) {
				$group = $this->getIdentityGroup($identity);
				if (empty($group['field'])) {
					continue;
				}

				$query .= " ({$group['field']}&{$group['matchingData'][$identity]}) = {$group['matchingData'][$identity]} and ";
			} elseif (is_array($identity)) {
				$queryFromArray = $this->convertFlagsIntoQuery($identity, true);
				$queryFromArray = trim($queryFromArray, 'and ');
				// $queryFromArray = trim($queryFromArray, 'or ');
				$query .= $queryFromArray .' or ';
			}
		}
		$query = trim($query, 'and ');	
		$query = trim($query, 'or ');	

		if($sameContext) {
			$query = '('.$query.')';
		}

		return $query;
	}

} 