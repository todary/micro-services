<?php
/**
 * Url validation code
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

/**
 * Url validation class
 *
 * @category Micro_Services-phase_1
 * @package  Validation
 * @author   Mahmoud AboElnouR <aboelnoor@queentecksolutions.com>
 * @license  QueenTechSolutions, Inc.
 * @link     http://www.queentecksolutions.com
 **/
class UrlValidation extends Validation
{
    /**
     * Check if url is valid
     *
     * @param string $url url to be validate
     *
     * @return bool
     */
    protected function isValid(&$url): bool
    {
        $url = strpos($url, 'http://') === 0 ? $url : 'http://' . $url;

        if (filter_var($url, FILTER_VALIDATE_URL)) {
            return true;
        }

        $this->error = 'Input must be valid url.';
        return false;
    }
}
