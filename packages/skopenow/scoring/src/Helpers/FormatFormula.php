<?php
namespace Skopenow\Scoring\Helpers;

trait FormatFormula
{
	public static function validateFormula($formula)
	{
		$equation = preg_replace('/\s+/', '', $formula);
		$number = '((?:0|[1-9]\d*)(?:\.\d*)?(?:[eE][+\-]?\d+)?|pi|π)'; // What is a number
		$functions = '(?:sinh?|cosh?|tanh?|acosh?|asinh?|atanh?|exp|log(10)?|deg2rad|rad2deg|sqrt|pow|abs|intval|ceil|floor|round|(mt_)?rand|gmp_fact)'; // Allowed PHP functions
		$operators = '[\/*\^\+-,]'; // Allowed math operators
		$regexp = '/^([+-]?('.$number.'|'.$functions.'\s*\((?1)+\)|\((?1)+\))(?:'.$operators.'(?1))?)+$/'; // Final regexp, heavily using recursive patterns

		if (preg_match($regexp, $equation)){
			$equation = preg_replace('!pi|π!', 'pi()', $equation); // Replace pi with pi function
			eval('$result = '.$equation.';');
		}else{
			$result = false;
		}

		return $result;
	}
}