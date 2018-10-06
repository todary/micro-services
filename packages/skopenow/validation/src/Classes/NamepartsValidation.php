<?php
/**
 * Name parts validation class
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

use Skopenow\Validation\Interfaces\NamepartsValidationInterface;

/**
 * Name parts Validation class
 *
 * @category Micro_Services-phase_1
 * @package  Validation
 * @author   Mahmoud AboElnouR <aboelnoor@queentecksolutions.com>
 * @license  QueenTechSolutions, Inc.
 * @link     http://www.queentecksolutions.com
 **/
class NamepartsValidation extends Validation implements NamepartsValidationInterface
{
    const VALIDATION_PATTERN = "#^[\w\.\-']{3,}+$#u";

    /**
     * Check if name parts are valid
     *
     * @param string $name name to validate
     *
     * @return bool
     */
    protected function isValid(&$parts): bool
    {
        $parts = new \ArrayIterator((array) $parts);
        $index = null;

        if ($parts->valid()) {
            foreach ($parts->offsetGet('splitted') as $part) {
                foreach ($part as $key => $value) {
                    switch ($key) {
                        case 'firstName':
                        case 'lastName':
                            if (!$this->partPatternMatch($value)) {
                                $index = $key;
                            }
                            break;
                        case 'middleName':
                            if ($value && !$this->partPatternMatch($value)) {
                                $index = $key;
                            }
                            break;
                        default:
                            $this->error = 'Nameparts do not match the criteria';
                            $index = $key;
                            break;
                    }

                }

                $this->prepareOutput($part, $index);
            }
            return true;
        }
        return false;
    }

    /**
     * prepare output status for name parts
     *
     * @param mixed $input      input to be add to output
     * @param mixed $index      error index name if exists
     * @param mixed $normalized normalized input if exists
     *
     * @return void
     */
    protected function prepareOutput($input, $index = null, $normalized = null)
    {
        if ($index) {
            $this->error = ucfirst($index) . ' must be at least 3 characters, words and . - symbols only';
        }
// var_dump($input, $normalized);
        parent::prepareOutput($input, $normalized);
    }

    /**
     * Check if part matches the part pattern validation
     *
     * @param string $part string part to match pattern
     *
     * @return bool
     */
    public function partPatternMatch(string $part): bool
    {
        return preg_match(
            self::VALIDATION_PATTERN,
            preg_replace('#\d+#', '*', $part)
        );
    }
}
