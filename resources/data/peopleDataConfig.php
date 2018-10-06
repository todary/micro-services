<?php

return [
    'input_priority' => [
        ['email'],
        ['phone'],
        ['username'],
        ['address'],
        ['location'],
        ['name'],
        // ['location'], // city - state
    ],

    'source' => [
        'email' => [['pipl', 'tloxp', 'ignoreWith' => [['phone','address'], ['location','address']]], ['whois'], ['fullcontact'], ['facebook']],
        'phone' => [['pipl', 'tloxp'], ['whois'], ['facebook']],
        'username' => [['pipl', 'tloxp'], ['whois'], ['usernames']],
        'address' => [['tloxp', 'pipl']],
        'location' => [['tloxp', 'pipl']],
        'name' => [['tloxp', 'pipl']],
    ],

    'levels' => [
        'email' => [['email', 'name', 'city', 'state'], ['email', 'name', 'state'], ['email', 'name'], ['email']],
        'phone' => [['phone', 'name', 'city', 'state'], ['phone', 'name', 'state'], ['phone', 'name'], ['phone']],
        'username' => [['username', 'name', 'city', 'state'], ['username', 'name', 'state'], ['username', 'name'], ['username']],
        'address' => [['name', 'address']],
        'location' => [['name', 'city', 'state'], ['name', 'state']],
    ],


    'apis_order_config' => [
        'address' => [
            // change from default config to this config
            'tloxp' => [
                'apis' => ['tloxp', 'pipl'],
                'api_options' => ['tloxp' => ['MatchType' => 'ExactMatch'], 'pipl' => ['key' => 'jeck5e9frmiy6x86src2u5hw', 'match_requirements' => 'names and addresses']],
                'strategy' => 'parallel'],
        ],
        'location' => [
            // change from default config to this config
            'tloxp' => [
                'apis' => ['tloxp', 'pipl'],
                'api_options' => ['tloxp' => ['MatchType' => 'ExactMatch'], 'pipl' => ['key' => 'jeck5e9frmiy6x86src2u5hw', 'match_requirements' => 'names and addresses']],
                'strategy' => 'parallel'],
        ]
    ],
    'api_options' => [
        /*'email' => ['pipl' => ['pipl' => ['match_requirements' => 'names and emails']]],
        'phone' => ['pipl' => ['match_requirements' => 'names and phones']],
        'username' => ['pipl' => ['match_requirements' => 'names and usernames']],*/
    ]
];
