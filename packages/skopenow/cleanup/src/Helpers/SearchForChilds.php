<?php

namespace Skopenow\Cleanup\Helpers;

use Skopenow\Cleanup\FiltrationModel;
use App\Models\Result;
use App\Models\ResultData;
use Skopenow\Search\Models\SearchResult;

class SearchForChilds
{

	protected $filtrationModel;

	public function __construct(FiltrationModel $filtrationModel)
	{
		$this->filtrationModel = $filtrationModel;
	}

	public function search()
	{
		$pendingResults = $this->filtrationModel->getAttribute('pendingResults');
		// dump($pendingResults);
		$allResults = $this->filtrationModel->getAttribute('allResults');

		$parentChilds = [];
		foreach ($allResults as $parentResult) {
			if ($parentResult['is_profile']) {
				$parentChilds = array_merge($parentChilds, $this->getParentChilds($parentResult, $pendingResults));
			}
		}

		// dump($parentChilds, 'END');
		return $parentChilds;
	}

	protected function getParentChilds(Result $parentResult, array $pendingResults)
	{
		$parentChilds = [];
		foreach ($pendingResults as $pendingResult) {
			$childResult = @unserialize($pendingResult['resultData']);
			// if ($childResult instanceof ResultData || $childResult instanceof SearchResult) {
				$parentUrl = $parentResult['unique_content'];
				$childUrl = $childResult->unique_url;

				if ($this->checkIsParentUrl($parentUrl, $childUrl)) {
					if (isset($childs[$parentUrl])) {
						$parentChilds['childs'][] = $childResult;
						continue;
					}
					$parentChilds[$parentUrl] = [
						'parent'	=>	$parentResult,
						'childs'	=>	[$childResult],
					];
				}
			// }
		}

		return $parentChilds;
	}

	protected function checkIsParentUrl(string $parentUrl, string $childUrl): bool
	{
		if (strlen($parentUrl) >= strlen($childUrl)) {
			return false;
		}

		$childSuffix = substr($childUrl, strlen($parentUrl));
		
		if (strtolower($parentUrl.$childSuffix) == strtolower($childUrl)) {
			return true;
		}

		return false;
	}
}