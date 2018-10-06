# PeopleData Service

PeopleData service is one of Skopenow micro services concerned with searching for people data in third party APIs.

## Inputs
To use the service to search for people data, you have to send the search criteria which may include one or more of the following entries.

| Entry | Type | Description | Example |
| ----- | ---- | ----------- | ------- |
| apis | array | Apis name to be used | ["pipl"] |
| strategy | string | Whether to use them in "parallel", or "serial". Default is "serial" | "serial" |
| name | string | Full name to search for | "Rob K Douglas" |
| city | string | City part of the location | "Oyster Bay" |
| state | string | State part of the location | "NY" |
| country | string | Country Name | "US" |
| address | string | Full address | "92 Sunken Orchard Lane, Oyster Bay, NY" |
| phone | string | Phone number | "8179256254" |
| email | string | Email address | "romado12187@aol.com" |
| username | string | Username | "romado12187" |
| age | number | Age in years | 27 |
| company | string | Company name | "Skopenow" |
| school | string | School name | "Vanderbilt University" |
| report_id | string | Report roken | "e00a2eed1c4678a52ebfa56a..." |
| sandbox | boolean | Return sample data | true |
| api_options | array | API specificed parameters to be sent to the endpoint | ["NumberOfRecords"=>"10", "UseExactFirstNameMatch"=>"Yes"] |

## Calling
To search for people data, call the entry point method "search" with the desired criteria as follows:

    $criteria = [
        'trial1'=>[
            'people'=>[
                'tloxp'=>[
                    ['apis'=>['tloxp'], ...],
                ],
                ...
            ],
            'fullcontact'=>[
                'name1'=>[
                    ['apis'=>['fullcontact'], ...],
                ],
                'name2'=>[
                    ['apis'=>['fullcontact'], ...],
                ],
                ...
            ],
            ...
        ],
        ...
    ];
    $peopledata = loadService('peopledata');
    $return = $peopledata->search($criteria);

#### 1. Calling single API: 
Criteria should look like the following example:
```
[
    'trial1'=>[
        'people'=>[
            'tloxp'=>[
                ['apis'=>['tloxp'], 'name'=>'Rob Douglas', 'city'=>'Oyster Bay', 'state'=>'NY'],
            ],
        ],
    ],
]
```

2. Calling muliple APIs in parrellel:
```
[
    'trial1'=>[
        'people'=>[
            'tloxp'=>[
                ['apis'=>['pipl', 'tloxp'], 'strategy'=>'parallel', 'name'=>'Rob Douglas', 'city'=>'Oyster Bay', 'state'=>'NY'],
            ],
        ],
    ],
];
```

3. Calling mutiple APIs in serial in multple groups in parallel:
```
[
    'trial1'=>[
        'people'=>[
            'tloxp'=>[
                ['apis'=>['tloxp'], 'name'=>'Rob Douglas', 'city'=>'Oyster Bay', 'state'=>'NY'],
                ['apis'=>['tloxp'], 'name'=>'Rob Douglas', 'state'=>'NY'],
            ],
            'pipl'=>[
                ['apis'=>['pipl'], 'name'=>'Rob Douglas', 'city'=>'Oyster Bay', 'state'=>'NY'],
                ['apis'=>['pipl'], 'name'=>'Rob Douglas', 'state'=>'NY'],
            ],
        ],
    ],
];
```

## Output

| Entry | Type | Description | Example |
| ----- | ---- | ----------- | ------- |
| trial | mixed | Trial key of the last search returned the data | 0 |
| results | array | Array of PeopleDataResult instances | [\App\Models\PeopleDataResult...] |

```
[
    "trial" => 'trial1',
    "results" => [...]
]
```
