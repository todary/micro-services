<?php
/**
 * Datapoint entry point
 *
 * PHP version 7.0
 *
 * @category Micro_Services-phase_1
 * @package  Datapoint
 * @author   Mahmoud AboElnouR <aboelnoor@queentecksolutions.com>
 * @license  QueenTechSolutions, Inc.
 * @link     http://www.queentecksolutions.com
 **/
namespace Skopenow\Datapoint;

use Illuminate\Support\Facades\Log;
use Skopenow\Datapoint\Classes\Datapoint as BaseDatapoint;
use Skopenow\Datapoint\Classes\Datasource;

/**
 * Datapoint entry point
 *
 * @category Micro_Services-phase_1
 * @package  Datapoint
 * @author   Mahmoud AboElnouR <aboelnoor@queentecksolutions.com>
 * @license  QueenTechSolutions, Inc.
 * @link     http://www.queentecksolutions.com
 **/
class EntryPoint
{
    public function make(\Iterator $data) /** : BaseDatapoint */
    {
        Log::info('Datapoint service start');
        Log::debug('EntryPoint data', ['data', $data]);
        $status = null;
        foreach ($data as $index => $inputs) {
            if ($inputs->count()) {
                $datapoint = $this->_getAction($index, $inputs);
                $status = $datapoint->add();
            }
        }

        Log::info('Datapoint service end');
        return $status;
    }

    public function datasource()
    {
        return new Datasource;
    }

    /**
     * Factory function to get action
     *
     * @param string    $name   name of action to get function
     * @param \Iterator $inputs inputs to pass to action consctructor
     *
     * @return type
     */
    private function _getAction(string $name, \Iterator $data): BaseDatapoint
    {
        $className = $this->formatClassName($name);
        $class = 'Skopenow\Datapoint\Classes\\' . $className . 'Datapoint';
        if (class_exists($class)) {
            return new $class($data, $this->datasource());
        }

        throw new \UnexpectedValueException("Invalid datapoint type: $name");
    }

    private function formatClassName($name)
    {
        switch ($name) {
            case 'workExperiences':
                return ucfirst('work');
                break;
            case 'educations':
                return ucfirst('school');
                break;
            case 'phones':
                return ucfirst('phone');
                break;
            case 'emails':
                return ucfirst('email');
                break;
            default:
                return ucfirst($name);
                break;
        }
    }
}
