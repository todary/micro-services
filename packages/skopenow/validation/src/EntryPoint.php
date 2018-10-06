<?php
/**
 * Validation entry point
 *
 * PHP version 7.0
 *
 * @category Micro_Services-phase_1
 * @package  Validation
 * @author   Mahmoud AboElnouR <aboelnoor@queentecksolutions.com>
 * @license  QueenTechSolutions, Inc.
 * @link     http://www.queentecksolutions.com
 **/
namespace Skopenow\Validation;

use Skopenow\Validation\Classes\Validation as BaseValidation;

/**
 * Validation entry point
 *
 * @category Micro_Services-phase_1
 * @package  Validation
 * @author   Mahmoud AboElnouR <aboelnoor@queentecksolutions.com>
 * @license  QueenTechSolutions, Inc.
 * @link     http://www.queentecksolutions.com
 **/
class EntryPoint
{
    private $results;

    public function getResults(): \Iterator
    {
        return $this->results;
    }

    /**
     * EntryPoint constructor
     *
     * @return void
     */
    public function __construct()
    {
        $this->results = new \ArrayIterator;
    }

    /**
     * EntryPoint client function
     *
     * @param \Iterator $request validation inputs
     *
     * @return void
     */
    public function validate(\Iterator $request)
    {
        if ($request->valid()) {
            $hasData = false;
            foreach ($request as $index => $inputs) {
                $inputs = is_object($inputs) ? $inputs->getArrayCopy() : (array) $inputs;
                $inputs = @array_filter($inputs);
                $inputs = new \ArrayIterator($inputs);
                if ($inputs->valid()) {
                    $validation = $this->_getAction($index, $inputs);
                    $this->results->offsetSet($index, $validation->validate());
                    if (!$hasData) {
                        $hasData = true;
                    }
                }
            }
            if ($hasData) {
                return;
            }
        }
        throw new \Exception('No data has provided');
    }

    /**
     * Factory function to get action
     *
     * @param string    $name   name of action to get function
     * @param \Iterator $inputs inputs to pass to action consctructor
     *
     * @return type
     */
    private function _getAction(string $name, \Iterator $inputs): BaseValidation
    {
        $class = 'Skopenow\Validation\Classes\\' . ucfirst($name) . 'Validation';
        if (class_exists($class)) {
            return new $class($inputs);
        }

        throw new \Exception('Invalid validation type: "' . $name . '"');
    }
}
