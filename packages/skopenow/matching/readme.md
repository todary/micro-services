# Matching Service

## Check Social Sources
```php
    $entry = loadservice('matching');
    $status = $entry->checkAngel(string $url, $report, array $combinatiom);
    $status = [
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
            'matchWith' => '',
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
```
### Note
All social sources accept just the URL, Report and combination
`{F6s, Angel, Flickr, Foursquare, Googleplus, Instagram, Linkedin, Myspace, Picasa, Pinterest, Slideshare, Soundcloud, Twitter, Youtube}`
`EXCEPT {Facebook and Linkedin}`

### Check Linkedin
``` php
    $entry = loadservice('matching');
    $status = $entry->checkLinkedin(
        string $url,
        $report,
        array $combination,
        $extraData = [],
        $checkLocationByforce = false,
        $htmlContent = [],
        $resultsCount = 0
    );
```

### Check Facebook
``` php
    $entry = loadservice('matching');
    $status = $entry->checkFacebook(
        string $url,
        $report,
        array $combination,
        $is_relative,
        $additional_location,
        $disable_location_check,
        $NameExact,
        array $htmlContent,
        $disableMiddlenameCriteria
    );
```
