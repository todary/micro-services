<?php

/**
 * VisibleResult
 *
 * PHP version 7.0
 * 
 * @category Class
 * @package  Ressult
 * @author   Queen tech <info@queentechsolutions.net>
 * @license  license http://www.queentechsolutions.net/
 * @link     http://www.queentechsolutions.net/
 */
namespace Skopenow\Result;

/**
 * VisibleResult
 * 
 * @category Class
 * @package  Ressult
 * @author   Queen tech <info@queentechsolutions.net>
 * @license  license http://www.queentechsolutions.net/
 * @link     http://www.queentechsolutions.net/
 */
class VisibleResult
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
     * @param integer $isVisible [description]
     * 
     * @return bool             [description]
     */
	public function visibleResults($resultIds, $isVisible) 
    {
        if (count($resultIds) == 0) {
            $idsResults = "-1"; 

            //log data
            \Log::info('No results Ids to Change its visibility'); 
            return false;
        } 

        $data = array(
            "invisible"=>$isVisible,
        );
        if(!$isVisible) $data['is_deleted'] = 0 ;

        //log data
        \Log::info('Update visibility for Results with Ids '.print_r($resultIds,true).' to be '.$isVisible); 

        return $this->dataSource->updateResults($data, $resultIds);

	}

    
}