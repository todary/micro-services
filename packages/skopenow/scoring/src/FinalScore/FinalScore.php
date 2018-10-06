<?php

/** 
 * Here we calculate the final scoring data ..
 * 
 * @author Ahmed Samir <ahmedsamir732@gmail.com>
 */

namespace Skopenow\Scoring\FinalScore ;

class FinalScore 
{
	protected $comparingData = array();
	
	public function __construct(array $comparingData) 
	{
		$this->comparingData = $comparingData ;
	}
	
	public function calculate(float $identityScore , float $listCountScore , float $sourceTypeScore , float $resultTypeScore)
	{
		$finalScore = 0 ;
		
		$identity_multiplier = 0.4;
		$count_multiplier = 0.2;
		$source_multiplier = 0.2;
		$source_type_multiplier = 0.2;
		foreach ($this->comparingData as $comparingData){
			if($comparingData->key == 'identity'){
				$identity_multiplier = $comparingData->score;
			}
			if($comparingData->key == 'result_count'){
				$count_multiplier = $comparingData->score;
			}
			if($comparingData->key == 'source'){
				$source_multiplier = $comparingData->score;
			}
			if($comparingData->key == 'source_type'){
				$source_type_multiplier = $comparingData->score;
			}
			

			// if($comparingData->key == 'identity'){
			// 	$finalScore += $this->calculateFinalIdentityScore($identityScore,$comparingData->score);
			// }
			// if($comparingData->key == 'result_count'){
			// 	$finalScore += $this->calculateFinalListCountScore($listCountScore,$comparingData->score);
			// }
			// if($comparingData->key == 'source'){
			// 	$finalScore += $this->calculateResultTypeFinalScore($resultTypeScore,$comparingData->score);
			// }
			// if($comparingData->key == 'source_type'){
			// 	$finalScore += $this->calculateSourceTypeFinalScore($sourceTypeScore,$comparingData->score);
			// }
		}

		$finalScore += $this->multiply($identityScore, $identity_multiplier, 0.4);
		$finalScore += $this->multiply($listCountScore, $count_multiplier);
		$finalScore += $this->multiply($resultTypeScore, $source_type_multiplier);
		$finalScore += $this->multiply($sourceTypeScore, $source_multiplier);

		return $finalScore ;		
	}

	protected function multiply(float $score, float $multiplier, float $defaultMultiplier = 0.2)
	{
		if (!$multiplier) {
			$multiplier = $defaultMultiplier;
		}

		return $score*$multiplier;
	} 
	
	protected function calculateFinalIdentityScore(float $identityScore , float $multiplier)
	{
		$identityFinalScore =  $identityScore * $multiplier;
		return $identityFinalScore ;
	}
	
	protected function calculateFinalListCountScore(float $listCountScore , float $multiplier)
	{
		$listCountFinalScore  = $listCountScore * $multiplier;
		return $listCountFinalScore ;
	}
	
	protected function calculateSourceTypeFinalScore(float $sourceTypeScore , float $multiplier)
	{
		$sourceTypeFinalScore = $sourceTypeScore * $multiplier;
		return $sourceTypeFinalScore ;
		
	}
	
	protected function calculateResultTypeFinalScore(float $resultTypeScore , float $multiplier)
	{
		$resultTypeFinalScore = $resultTypeScore * $multiplier;
		return $resultTypeFinalScore ;
		
	}
}

