<?php
/**
 * Abstract validation code
 *
 * PHP version 7.0
 *
 * @category Micro_Services-phase_1
 * @package  Validation
 * @author   Mahmoud AboElnouR <aboelnoor@queentecksolutions.com>
 * @license  QueenTechSolutions, Inc.
 * @link     http://www.queentecksolutions.com
 **/
namespace Skopenow\Validation\Classes;

use Skopenow\Validation\Interfaces\ValidationInterface;

/**
 * Abstract validation class
 *
 * @category Micro_Services-phase_1
 * @package  Validation
 * @author   Mahmoud AboElnouR <aboelnoor@queentecksolutions.com>
 * @license  QueenTechSolutions, Inc.
 * @link     http://www.queentecksolutions.com
 **/
abstract class Validation implements ValidationInterface
{
    protected $status;
    protected $inputs;
    protected $error;

    /**
     * Class Constructor
     *
     * @param \Iterator $inputs Validation inputs
     *
     * @return void
     */
    public function __construct(\Iterator $inputs = null)
    {
        $this->status = new \ArrayIterator;
        $this->inputs = $inputs;
    }

    /**
     * Validate the inputs and return iterator of results
     *
     * @return \Iterator
     */
    public function validate(): \Iterator
    {
        if ($this->inputs->valid()) {
            foreach ($this->inputs as $item) {
                $input = $item; //store old input

                if (!is_array($item)) {
                    $item = trim($item);
                    if ($item !== false && $this->isValid($item)) {
                        $this->prepareOutput($input, $item);

                        continue;
                    }
                    $this->prepareOutput($input);
                } else {
                    $this->isValid($item);
                }

            }
            return $this->status;
        }

        return new \ArrayIterator([false]);
    }

    /**
     * prepare output status for validation inputs
     *
     * @param mixed $input      input to be add to output
     * @param mixed $normalized normalized input if exists
     *
     * @return void
     */
    protected function prepareOutput($input, $normalized = null)
    {
        $output['input'] = $input;
        $output['isValid'] = $this->error ? false : true;
        $output['normalized'] = $this->error === null && $normalized === null ? $input : $normalized;
        $output['error'] = $this->error;

        $this->error = null; //empty error data
        $this->status->append(new \ArrayIterator($output));
    }

    /**
     * Chack if input is valid
     *
     * @param mixed $inputs Input to validete Ex: username, age, .. etc.
     *
     * @return bool
     */
    abstract protected function isValid(&$input): bool;
}
