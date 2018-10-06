<?php

if (!function_exists('setArrayHash')) {
    function setArrayHash(array &$data)
    {
        unset($data['hash']);
        $data_copy = $data;
        unset($data_copy['parent_comb']);
        unset($data_copy['res']);
        unset($data_copy['data_json']);
        unset($data_copy['data_key']);
        unset($data_copy['main_value']);
        ksort($data_copy);

        $hash = md5(strtolower(json_encode($data_copy)));
        $data['hash'] = $hash;
    }
}

if (!function_exists('honorificNicknames')) {
    /**
     * Toggle dots after honrific nicknames from the first name.
     * ex: Dr. Rob => Dr Rob or Dr Rob => Dr. Rob
     *
     * @param string $name
     * @param boolean $removeDots
     *
     * @return string
     */
    function honorificNicknames($name, $removeDots = false): string
    {
        $honorificNicknames = ['dr'];
        if (!$removeDots) {
            array_walk($honorificNicknames, function (&$item, $key) {
                $item = '#^(' . $item . ')([^\S]|$)#i';
            });

            return trim(preg_replace($honorificNicknames, "$1. ", $name));
        }

        ## remove dot's from honorific nicknames
        array_walk($honorificNicknames, function (&$item, $key) {
            $item = '#^(' . $item . ')\s*(\.)#i';
        });

        return trim(preg_replace($honorificNicknames, "$1", $name));
    }
}

if (!function_exists('numberOfDecimals')) {
    function numberOfDecimals($value)
    {
        return !is_numeric($value) ? false : ((int) $value == $value ? 0 : strlen($value) - strrpos($value, '.') - 1);
    }
}
