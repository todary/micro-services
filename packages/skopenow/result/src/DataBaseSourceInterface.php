<?php

/**
 * Ressult
 *
 * PHP version 7.0
 *
 * @category interface
 * @package  RessultSave
 * @author   Queen tech <info@queentechsolutions.net>
 * @license  license http://www.queentechsolutions.net/
 * @link     http://www.queentechsolutions.net/
 */
namespace Skopenow\Result;

/**
 * Ressult
 *
 * @category interface
 * @package  RessultSave
 * @author   Queen tech <info@queentechsolutions.net>
 * @license  license http://www.queentechsolutions.net/
 * @link     http://www.queentechsolutions.net/
 */
interface DataBaseSourceInterface
{
    
    /**
     * [saveResult description]
     *
     * @param array $data [description]
     *
     * @return integer       [description]
     */
    public function saveResult(array $data) :int;

    /**
     * [updateResult description]
     *
     * @param array   $data            [description]
     * @param integer $id            [description]
     * @param integer $combinationId [description]
     *
     * @return bool       [description]
     */
    public function updateResult($data, $id = null, $url = null);

    public function getResult($resultId = null, $url = null);

    public function updateResults($data, $ids);

    public function getResults($criteria);
}
