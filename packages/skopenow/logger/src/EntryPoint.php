<?php

/**
 * SearchLogger
 * A monolog implementation for logging in a custom way for skopenow search .
 *
 * @author Ahmed Samir <ahmedsamir732@gmail.com>
 */

namespace Skopenow\Logger;

use Skopenow\Logger\Writer\WriterInterface;
use Skopenow\Logger\Writer\NullWriter ;
use Skopenow\Logger\Writer\MongoDBWriter ;
use monolog\Logger as MonologLogger;
use Skopenow\Logger\ManageWriters ;

class EntryPoint
{
    
    use ManageWriters ;
    /**
     *Log type for search starting .
     */
    const SEARCH_INIT = 10 ;
    /**
     *Log type is combination create .
     */
    const COMBINATION_CREATE = 20 ;

    /**
     *Log type for creating list for search criteria .
     */
    const COMBINATION_CRITERIA_FORMAT = 30 ;

    /**
     *Log type for sending curl request to get list results .
     */
    const REQUEST = 40 ;

    /**
     *Log type for extracting data from the combination list .
     */
    const LIST_EXTRACT = 50 ;

    /**
     *Log type for extracting result data .
     */
    const RESULT_EXTRACT = 60 ;

    /**
     *Log type for matching result data with the current search criteria .
     */
    const MATCHING = 70 ;

    /**
     *Log Type for saving data .
     */
    const DATA_SAVE = 80 ;

    /**
     *Log type for setting relationships between entities .
     */
    const RELATIONSHIP_SET = 90 ;
    
    /**
     *Log type for results filtration at the end of the search .
     */
    const FILTRATION = 100 ;
    
    /**
     *Log type for search completed .
     */
    const SEARCH_END = 110;

    /**
     *Log type for ACCEPTANCE
     */
    const ACCEPTANCE = 120;

    /**
     *Log type for FORMATTER
     */
    const FORMATTER = 130;

    /**
     *Log type for LOCATION
     */
    const LOCATION = 140;

    /**
     *Log type for LOCATION
     */
    const RESULT = 150;

    /**
     *Log type for Search
     */
    const SEARCH = 160;

    /**
     *
     *@var log types
     */
    public $log_types = array(
        self::SEARCH_INIT => "SEARCH_INIT",
        self::COMBINATION_CREATE => "COMBINATION_CREATE",
        self::COMBINATION_CRITERIA_FORMAT => "COMBINATION_CRITERIA_FORMAT",
        self::REQUEST => "REQUEST",
        self::LIST_EXTRACT => "LIST_EXTRACT",
        self::RESULT_EXTRACT => "RESULT_EXTRACT",
        self::MATCHING => "MATCHING",
        self::DATA_SAVE => "DATA_SAVE",
        self::RELATIONSHIP_SET => "RELATIONSHIP_SET",
        self::FILTRATION => "FILTRATION",
        self::SEARCH_END => "SEARCH_END",
        self::ACCEPTANCE => "ACCEPTANCE",
        self::FORMATTER => "FORMATTER",
        self::LOCATION => "LOCATION",
        self::RESULT => "RESULT",
        self::SEARCH => "SEARCH",
        
    );


    /**
     *The log type
     */
    protected $type ;
    
    /**
     *
     *@var processor [ProcessorInterface]
     */
    protected $processor ;

    /**
     *The writers stack
     */
    protected $writers ;


    /**
     *the entry point for the search logger class .
     *@param int $type
     *@param array $writers
     *@throws \Exception
     */
    public function __construct(int $type, array $writers = array())
    {
        if (!isset($this->log_types[$type])) {
            throw new \Exception("You must declare a valid log type!");
        }
        $this->type = $type ;
        $this->writers = $writers ;
    }

    /**
     *provide the data will be logged .
     *@param array $state
     *@param array $data
     *@return array
     */
    public function addLog(array $state, array $data)
    {
        return;
        
        $this->processor = $this->determineProcessByLogType();
        if (empty($this->processor)) {
            $notes = ["processor" => "no processor for your log type!"];
            return $this->getFailedResponse($notes);
        }
        $status = $this->processor->process($state, $data);

        if (!$status) {
            $notes = $this->processor->getValidationNotes();
            return $this->getFailedResponse($notes);
        }
        
        $dataModel = $this->processor->getModelData();

        if (empty($this->writers)) {
            $this->manageWriters();
        }

        foreach ($this->writers as $writer) {
            $writer->handle($dataModel);
        }
        return $this->getSuccessResponse();
    }

    /**
     *push a writer to be write the log data with .
     *@param WriterInterface $writer
     *@return $this
     */
    public function pushWriter(WriterInterface $writer)
    {
        array_unshift($this->writers, $writer) ;
        return $this ;
    }
    
    /**
     *determine the processor the by the log type entered to the class .
     *@return \SearchLogger\Processor\RequestProcessor
     */
    protected function determineProcessByLogType()
    {
        $processor = null ;
        switch ($this->type) {
            case self::SEARCH_INIT:
                $processor = new Processor\SearchInitProcessor($this->type);
                break;
            case self::COMBINATION_CREATE:
                $processor = new Processor\CombinationCreateProcessor($this->type);
                break;
            case self::REQUEST:
                $processor = new Processor\RequestProcessor($this->type);
                break;
            case self::ACCEPTANCE:
                $processor = new Processor\AcceptanceProcessor($this->type);
                break;
            case self::FORMATTER:
                $processor = new Processor\FormatterProcessor($this->type);
                break;
            case self::LOCATION:
                $processor = new Processor\LocationProcessor($this->type);
                break;
            case self::RESULT:
                $processor = new Processor\ResultProcessor($this->type);
                break;
            case self::SEARCH:
                $processor = new Processor\SearchProcessor($this->type);
                break;
            default:
                break;
        }
        
        return $processor ;
    }
    
    /**
     *return the failed response .
     *@param array $notes
     *@return array
     */
    protected function getFailedResponse(array $notes)
    {
        $response = array(
            "status" => false ,
            "notes"  => $notes ,
        );
        return $response ;
    }
    
    /**
     *return the success response .
     *@return array
     */
    protected function getSuccessResponse()
    {
        $response = array(
            "status" => true ,
        );
        
        return $response ;
    }
    
    /**
     *return the log type .
     *@return int
     */
    public function getType()
    {
        return $this->type ;
    }
    
    /**
     *return the array of the writers .
     *@return array
     */
    public function getWriters()
    {
        return $this->writers ;
    }
}
