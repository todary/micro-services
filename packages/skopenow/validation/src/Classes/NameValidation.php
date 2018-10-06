<?php
/**
 * Name validation class
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

use Skopenow\Validation\Interfaces\NameValidationInterface;

/**
 * Name Validation class
 *
 * @category Micro_Services-phase_1
 * @package  Validation
 * @author   Mahmoud AboElnouR <aboelnoor@queentecksolutions.com>
 * @license  QueenTechSolutions, Inc.
 * @link     http://www.queentecksolutions.com
 **/
class NameValidation extends Validation implements NameValidationInterface
{
    const VALIDATION_PATTERN = '/^[\w\.\-\']{3,}(\s[\w\.\-\']{1,})?(\s[\w\.\-\']{3,})$/u';

    /**
     * Check if name is valid
     *
     * @param string $name name to validate
     *
     * @return bool
     */
    protected function isValid(&$name): bool
    {
        $spacesCount = substr_count($name, ' ');

        if ($spacesCount < 1) {
            $this->error = 325;
            return false;
        }

        if ($spacesCount == 2 && $this->lastNameSuffix($name)) {
            return true;
        }

        if (!$this->namePatternMatch($name)) {
            $this->error = 324;
            return false;
        }

        return true;
    }

    /**
     * Check if name matches the name pattern validation
     *
     * @param string $name string name to match pattern
     *
     * @return bool
     */
    public function namePatternMatch(string $name): bool
    {
        return preg_match(self::VALIDATION_PATTERN, preg_replace('#\d+#', '*', $name));
    }

    /**
     * Spacify if lastname have suffix
     *
     * @param string $name name to ckeck suffix
     *
     * @return bool
     */
    public function lastNameSuffix(string $name): bool
    {
        $wordsIgnore = ['i', 'ii', 'iii', 'jr', 'sr', 'iv'];

        $temp = explode(' ', $name, 3);
        $filter_ = trim(strtolower($temp[2]));
        $filter_ = rtrim($filter_, '.');

        if (in_array($filter_, $wordsIgnore)) {
            return true;
        }
        return false;
    }

}
