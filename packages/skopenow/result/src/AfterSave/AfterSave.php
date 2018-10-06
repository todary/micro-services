<?php

/**
 * After each result save should run this class .
 *
 * @author Ahmed Samir <ahmedsamir732@gmail.com>
 *
 */
namespace Skopenow\Result\AfterSave;

use App\Models\ResultData as Result;
use Skopenow\Result\AfterSave\Helpers\DataPoints;
use Skopenow\Result\AfterSave\Helpers\Runner;
use Skopenow\Result\AfterSave\MatchDataPoints\MatchDataPointInterface;
use Skopenow\Result\EntryPoint as ResultService;
use Skopenow\Result\Verify\CheckVerifiedResults;
use Skopenow\Result\Save\ResultSave;
use App\Libraries\DBCriteria;
use App\Models\Result as ResultLumen;

class AfterSave
{

    use DataPoints;
    use Runner;


    protected $report_id;
    /**
     * The result's object .
     * @var object
     */
    protected $result;

    /**
     * [$relationshipService object of the relationship service]
     * @var [type]
     */
    protected $relationshipService;

    /**
     * [$dataPointService object of the data points service]
     * @var [type]
     */
    protected $dataPointService;

    /**
     * [$scoringService object of the scoring service]
     * @var [type]
     */
    protected $scoringService;

    /**
     * [$matchingService object of the matching service]
     * @var [type]
     */
    protected $matchingService;
    /**
     * array of the result data points , should be in this format .
     * array(
     *    "workExperiences" => array(
     *        [
     *            "main_value" => string "skopenow" ,
     *            -------
     *        ],
     *    ),
     *    "schools" => [
     *        [
     *            "main_value" => string "vanderbelt" ,
     *            --------
     *        ],
     *    ],
     *    "username => array(
     *        "main_value" => string "romado" ,
     *        --------
     *    )
     * )
     * @var array
     */
    protected $dataPoints = array();

    /**
     * array of the results ids should be related .
     * array(
     *        [$firstEntity , $secondEntity] ,
     *        [$firstEntity , $secondEntity] ,
     * )
     * @var array
     */
    protected $relationships = array();

    /**
     * [$matchedResults the matched results with the entered result]
     * array(
     *     ["result_id" => int $result_id , "reason" => int $reason ]
     * )
     * @var array
     */
    protected $matchedResults = array();

    /**
     * [$nonMatchedResults The non matched results with the entered result]
     * array(
     *     ["result_id" => int $result_id , "reason" => int $reason ]
     * )
     * @var array
     */
    protected $nonMatchedResults = array();

    /**
     * [$resultsDataPoints description]
     * @var array
     */
    protected $resultsDataPoints = array();

    public $processStatus = [
    	"saveDataPoints"	=>	false,
    	"isVerified"		=>	false,
    	"setRelationships"	=>	false,
    	"upgradeResults"	=>	false,
    	"hideResults"		=>	false,
    ];

    /**
     * [__construct sets the data must be here for processing]
     * @param Result $result        [The main result]
     * @param array  $dataPoints    [the data points of the entered main result]
     * @param array  $relationships [initial relationships with the result]
     */
    public function __construct()
    {
    	$this->report_id = config('state.report_id');
        $this->relationshipService = loadService("relationship");
        $this->dataPointService = loadService("datapoint");
        $this->scoringService = loadService("scoring");
        $this->matchingService = loadService("matching");

    }


    public function runAfterResultSave(Result $result)
    {
        $this->result = $result;
        $this->relationships = iterator_to_array($result->getLinks());
        ## saveDataPoints.
        $this->processStatus['saveDataPoints'] =$this->saveDataPoints($result);
        ## match With Data points.
        $dataPoints = $this->prepareDataPointsForMatching($result);
        $this->startDataPointMatch($dataPoints);
        ## save relationships.
        $this->processStatus['setRelationships'] = $this->startRelatingResults($this->relationships);

        ##check if result is verified.
        $isVerified = $this->isVerified($this->result);
        // dump($result,$isVerified);return false;
        $this->processStatus['isVerified'] = $isVerified;

        if ($isVerified['status']) {
            ## start the filtration process.
            $filtrationStatus = $this->startFilterationProcess($isVerified['level']);
        }elseif (!empty($this->matchedResults)) {
        	$verfiedDataPointsResults = $this->getResultWithVerifiedData($this->matchedResults);
        	$this->upgradeMatchedResult($verfiedDataPointsResults);
        }else {
            ## get the verified results from the same source, and run
            ## the filtration event for them.
        }
        // dd($this->processStatus);
        return $this->processStatus;
    }

    public function getResultWithVerifiedData($results)
    {
    	$verified = [];
    	foreach ($results as $key => $result) {
    		if(!empty($result['isVerifiedDataPoint']) && $result['isVerifiedDataPoint']) {
    			$verified[] = $result;
    		}
    	}
    	return $verified;
    }

    public function runAfterResultUpdate(Result $result)
    {
    	$this->result = $result;
        $this->relationships = iterator_to_array($result->getLinks());

        ## match With Data points.
        $dataPoints = $this->prepareDataPointsForMatching($result);
        $this->startDataPointMatch($dataPoints);

        ## save relationships.
        $this->processStatus['setRelationships'] = $this->startRelatingResults($this->relationships);

        ##check if result is verified.
        $isVerified = $this->isVerified($this->result);
        $this->processStatus['isVerified'] = $isVerified;
        if ($isVerified['status']) {
            ## saveDataPoints.
            $this->processStatus['saveDataPoints'] =$this->saveDataPoints($result);
            ## start the filtration process.
            $filtrationStatus = $this->startFilterationProcess($isVerified['level']);
        }elseif (!empty($this->matchedResults)) {
            $verfiedDataPointsResults = $this->getResultWithVerifiedData($this->matchedResults);
            $this->upgradeMatchedResult($verfiedDataPointsResults);
        }else {
            ## get the verified results from the same source, and run
            ## the filtration event for them.
        }
        // dd($this->processStatus);
        return $this->processStatus;
    }

}
