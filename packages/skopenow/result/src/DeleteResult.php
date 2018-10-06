<?php

/**
 * DeleteResult
 *
 * PHP version 7.0
 * 
 * @category Class
 * @package  RessultDelete
 * @author   Queen tech <info@queentechsolutions.net>
 * @license  license http://www.queentechsolutions.net/
 * @link     http://www.queentechsolutions.net/
 */
namespace Skopenow\Result;

/**
 * DeleteResult
 * 
 * @category Class
 * @package  RessultDelete
 * @author   Queen tech <info@queentechsolutions.net>
 * @license  license http://www.queentechsolutions.net/
 * @link     http://www.queentechsolutions.net/
 */
class DeleteResult
{
    /**
     * [$dataSource description]
     * 
     * @var DataBaseSourceInterface
     */
    protected $dataSource;

    /**
     * [__construct description]
     */
    public function __construct($dataSource)
    {
        $this->dataSource = $dataSource;
    }

    /**
     * [deleteResult description]
     * 
     * @param array $resultIds  [description]
     * @param integer $deleteType [description]
     *
     * 
     * @return bool             [description]
     */
    public function deleteResult($resultIds, $deleteType)
    {
        if (count($resultIds) == 0) {
            $idsResults = "-1";

            //log data
            \Log::info('No results Ids to delete');
            return false;
        }
        
        $data = array(
            "is_deleted"=>1,
            "deletion_type"=>$deleteType
        );

        //log data
        \Log::info('Delete Results with Ids '.json_encode($resultIds).' and with delete type '.$deleteType);

        $output = $this->dataSource->updateResults($data, $resultIds);
        return $output;
    }

    /**
     * [UpdateDisplayLevel description]
     * 
     * @param array $resultIds  [description]
     * @param integer $displayLevel [description]
     *
     * 
     * @return bool             [description]
     */
    public function updateDisplayLevel($resultIds, $displayLevel) 
    {
        if (count($resultIds) == 0) {
            $idsResults = "-1"; 

            //log data
            \Log::info('No results Ids to updateDisplayLevel'); 
            return false;
        } 

        $idsResults = implode(',',$resultIds);

        $data = array(
            "display_level"=>$displayLevel
        );

        //log data
        \Log::info('UpdateDisplayLevel Results with Ids '.$idsResults.' to be '.$displayLevel);


        return $this->dataSource->updateResults($data, $idsResults);
    }

    
}