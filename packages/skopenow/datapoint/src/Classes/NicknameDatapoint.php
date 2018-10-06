<?php
/**
 * Abstract Datapoint code
 *
 * PHP version 7.0
 *
 * @category Micro_Services-phase_2
 * @package  Datapoint
 * @author   Mahmoud AboElnouR <aboelnoor@queentecksolutions.com>
 * @license  QueenTechSolutions, Inc.
 * @link     http://www.queentecksolutions.com
 **/
namespace Skopenow\Datapoint\Classes;

/**
 * Abstract Datapoint class
 *
 * @category Micro_Services-phase_2
 * @package  Datapoint
 * @author   Mahmoud AboElnouR <aboeldnoor@queentecksolutions.com>
 * @license  QueenTechSolutions, Inc.
 * @link     http://www.queentecksolutions.com
 **/
class NicknameDatapoint extends Datapoint
{
    public $isQuable = true;

    public function addEntry($input)
    {
        Log::info("add nickname start\n");

        $nickNames = $this->getNickNamesFromDB($input->nickname);
        if (is_array($nickNames) && count($nickNames)) {
            $storingNickNames['names'] = array_slice($nickNames, 0, 50);
            $storingNickNames['key'] = md5($input->nickname);
            $storingNickNames['parent_comb'] = $this->combinationId;

            $this->addDataPoint('nicknames', $storingNickNames, $input);
            $debugMsg = "Person: $this->reportId ,
                            Combination: $this->combinationId
                            Saving names script nicknames:
                            first name : $input->nickname \n";
            Log::debug($debugMsg);
        }

        Log::info("add nickname end\n");
    }

    protected function getNickNamesFromDB($name)
    {
        $nickNamesIterator = loadService('nameInfo')->nickNames(new \ArrayIterator([$name]));
        $nickNamesArray = iterator_to_array($nickNamesIterator);
        return $nickNamesArray[0]['nickNames'] ?? [];
    }
}
