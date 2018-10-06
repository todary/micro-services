<?php

/**
 * Http Requests client code
 *
 * PHP version 7.0
 *
 * @category Micro_Services-phase_1
 * @package  Result caching
 * @author   Ahmed Samir <ahmed.samir@queentecksolutions.com>
 * @license  QueenTechSolutions, Inc.
 * @link     http://www.queentecksolutions.com
 **/

namespace App\Libraries;

use App\Models\ResultData;
use App\Models\Result;

class ResultCache
{

	/**
	 * @const for number of minutes to save in cache .
	 */
	const Period = 10;

	public static function save(ResultData $result) 
	{
		$key = self::prepareKeyById($result->id);
		$status = \Cache::put($key,$result,self::Period);

		self::saveUrlIntoCache($result->id, $result->unique_url);

		return $status;
	}

	public static function get(int $id , \Closure $closure = null)
	{
		$key = self::prepareKeyById($id);
		return \Cache::remember($key, self::Period, $closure);
	}

	public static function getByLink(string $link, \Closure $closure = null)
	{
		$key = self::getKeyByLink($link);

		return \Cache::remember($key, self::Period, $closure);
	}

	public static function saveKeyByUrl($link, $key)
	{
		$linkKey = self::prepareKeyByLink($link);
		$status = \Cache::put($linkKey,$key,self::Period);
		return $status;
	}

	public static function prepareKeyById(int $id)
	{
		return md5($id);
	}

	public static function prepareKeyByLink(string $link)
	{
		return md5(config('state.report_id').$link);
	}

	public static function getKeyByLink(string $link)
	{
		$key = self::prepareKeyByLink($link);

		return \Cache::get($key);
	}

}