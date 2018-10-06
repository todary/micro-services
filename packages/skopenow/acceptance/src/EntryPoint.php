<?php

/**
 * EntryPoint
 *
 * PHP version 7.0
 *
 * @category Class
 * @package  Acceptance
 * @author   Queen tech <info@queentechsolutions.net>
 * @license  license http://www.queentechsolutions.net/
 * @link     http://www.queentechsolutions.net/
 */

namespace Skopenow\Acceptance;

use Skopenow\Acceptance\Classes\FlagsCheck;
use Skopenow\Acceptance\Classes\Acceptance;
use Skopenow\Acceptance\Classes\Visible;
use Skopenow\Acceptance\Classes\Banned;

/**
 * EntryPoint
 *
 * @category Class
 * @package  Acceptance
 * @author   Queen tech <info@queentechsolutions.net>
 * @license  license http://www.queentechsolutions.net/
 * @link     http://www.queentechsolutions.net/
 */
class EntryPoint
{
    /**
     * [$acceptanceObj description]
     *
     * @var [type]
     */
    protected $acceptanceObj;

    /**
     * [$visibleObj description]
     *
     * @var [type]
     */
    protected $visibleObj;

    /**
     * [__construct description]
     */
    public function __construct()
    {
        $banned = new Banned;
        $flagsCheckObj = new FlagsCheck;
        $report = loadService("reports");
        $this->acceptanceObj = new Acceptance($flagsCheckObj, $banned, $report);
        $this->visibleObj = new Visible($flagsCheckObj, $report);
        
    }

    /**
     * [checkAcceptance description]
     *
     * @param integer $flags         [description]
     * @param booleam $nameFound     [description]
     * @param booleam $locationFound [description]
     * @param booleam $isDataPoint   [description]
     * @param boolean $userNameFound [description]
     *
     * @return boolean               [description]
     */
    public function checkAcceptance($resultData, $flags)
    {
        $output = [];

        $acceptance  = $this->acceptanceObj->checkAccptance($resultData, $flags);

        $visible["visible"] = true;
        $visible["reason"] = 0;

        if ($acceptance) {
            $visible  = $this->visibleObj->checkVisible($resultData, $flags);
        }

        $output["acceptance"] = $acceptance;
        $output["visible"] = $visible;

        $loggerData["url"] = $resultData->url;
        $loggerData["acceptance"] = $acceptance;
        $loggerData["visible"] = $visible;
        $loggerData["flags"] = $resultData->flags;

        $state = [
            "report_id" => config("state.report_id"),
            "combination_id" => config("state.combination_id"),
            "combination_level_id" => config("state.combination_level_id"),
            "environment" => env("APP_ENV")
        ];

        $logger = loadService("logger", [120]);
        $logger->addLog($state, $loggerData);
        return $output;
    }
}
