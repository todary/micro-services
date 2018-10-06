<?php

namespace Skopenow\Matching;
/**
 * Status class serve as Data type
 */
class Status
{
    public $matchingData = [
        'name' => [
            'status' => false,
            'identities' => [
                'fn'  => false,
                'mn'  => false,
                'ln'  => false,
                'input_name' => false,
                'unq_name' => false,
                'fzn' => false,
            ],
            'matchWith' => '',
            'found_name' => true,
        ],
        'location' => [
            'status' => false,
            'identities' => [
                'exct-sm' => false,
                'exct-bg' => false,
                'input_loc' => false,
                'pct' => false,
                'st' => false,
            ],
            'distance' => 0,
            'matchWith' => '',
            'found_location' => true,
        ],
        'email' => [
            'status' => false,
            'identities' => [
                'em' => false,
                'input_em' => false,
            ],
            'matchWith' => '',
        ],
        'phone' => [
            'status' => false,
            'identities' => [
                'ph' => false,
                'input_ph' => false,
            ],
            'matchWith' => '',
        ],
        'work' => [
            'status' => false,
            'identities' => [
                'cm' => false,
                'input_cm' => false,
            ],
            'matchWith' => '',
        ],
        'school' => [
            'status' => false,
            'identities' => [
                'sc' => false,
                'input_sc' => false,
            ],
            'matchWith' => '',
        ],
        'age' => [
            'status' => false,
            'identities' => [
                'age' => false,
            ],
            'matchWith' => '',
        ],

        'username' => [
            'status' => false,
            'identities' => [
                'un' => false,
                'input_un' => false,
                'verified_un' => false,
            ],
            'matchWith' => '',
        ],
    ];
}
