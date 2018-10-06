<?php

namespace Skopenow\Search\Managing\Managers ;

/**
 * Description of the facebook main manager .
 *
 * @author ahmedsamir
 */
use Skopenow\Search\Managing\AbstractManager;
use Skopenow\Search\Fetching\FetcherInterface;
use Skopenow\Search\Models\DataPointInterface;
use Skopenow\Search\Models\SearchResultInterface;
use Skopenow\Search\Libraries\PurifyPendingResults;

class LinkedinManager extends AbstractManager
{
	/**
     * @const string The selected account for search
     */
    const MAIN_SOURCE_NAME = "linkedin";

    /**
     * @const bool decide to run On Result Save event.
     */
    const Run_Main_Result_Event = true;

    public function __construct(FetcherInterface $fetcher)
    {
    	parent::__construct($fetcher);
        // $this->saveToPending = true;
    }

    public function purifyFromPending(\Iterator $results): \Iterator
    {
    	$rules = $this->generatePurifyRules();
    	$PurifyPendingResults = new PurifyPendingResults($rules);
    	$purifiedResults = $PurifyPendingResults->purify($results);

    	return $purifiedResults;
    }

    public function generatePurifyRules()
    {
    	$scoringFlags = loadData('scoringFlags');
    	$companyFlag = $scoringFlags['cm']['value'];
    	$schoolFlag = $scoringFlags['sc']['value'];
    	$nameFlags = $scoringFlags['fn']['value']|$scoringFlags['ln']['value'];
    	$ageFlag = $scoringFlags['age']['value'];
    	$smallCityFlag = $scoringFlags['exct-sm']['value'];
    	$bigCityFlag = $scoringFlags['exct-bg']['value'];
    	$pctFlag = $scoringFlags['pct']['value'];
    	$stateFlag = $scoringFlags['st']['value'];
    	return new \ArrayIterator([
    		$nameFlags|$smallCityFlag|$ageFlag|$companyFlag		=>	1 ,
    		$nameFlags|$smallCityFlag|$ageFlag|$schoolFlag		=>	1 ,
    		$nameFlags|$bigCityFlag|$ageFlag|$companyFlag		=>	2 ,
    		$nameFlags|$bigCityFlag|$ageFlag|$schoolFlag		=>	2 ,
    		$nameFlags|$pctFlag|$ageFlag|$companyFlag			=>	3 ,
    		$nameFlags|$pctFlag|$ageFlag|$schoolFlag			=>	3 ,
    		$nameFlags|$stateFlag|$ageFlag|$companyFlag			=>	4 ,
    		$nameFlags|$stateFlag|$ageFlag|$schoolFlag			=>	4 ,
    		$nameFlags|$smallCityFlag|$companyFlag				=>	5 ,
    		$nameFlags|$smallCityFlag|$schoolFlag				=>	5 ,
    		$nameFlags|$bigCityFlag|$companyFlag				=>	6 ,
    		$nameFlags|$bigCityFlag|$schoolFlag					=>	6 ,
    		$nameFlags|$pctFlag|$companyFlag					=>	7 ,
    		$nameFlags|$pctFlag|$schoolFlag						=>	7 ,
    		$nameFlags|$stateFlag|$companyFlag					=>	8 ,
    		$nameFlags|$stateFlag|$schoolFlag					=>	8 ,
    		$nameFlags|$smallCityFlag|$ageFlag					=>	9 ,
    		$nameFlags|$bigCityFlag|$ageFlag					=>	10 ,
    		$nameFlags|$pctFlag|$ageFlag						=>	11 ,
    		$nameFlags|$stateFlag|$ageFlag						=>	12 ,
    		$nameFlags|$companyFlag|$ageFlag					=>	13 ,
    		$nameFlags|$schoolFlag|$ageFlag						=>	13 ,
    		$nameFlags|$smallCityFlag							=>	14 ,
    		$nameFlags|$bigCityFlag								=>	15 ,
    		$nameFlags|$pctFlag									=>	16 ,
    		$nameFlags|$stateFlag								=>	17 ,
    	]);
    }





}
