# Reports Service
This service is responsible for
- Getting report data
- Generate report
- Get report suggestions
- Start search
- On search complete
- After search complete
- Handle datapoint insertion events

## How To use?

### Initialize Service 
```php
use Skopenow\Reports\EntryPoint;

$reportsService = new EntryPoint();
```

### Generate Report
```php
 $reportData = [
        'name' => [
            'Rob Douglas',
            'Rob Kevin Douglas'
        ],
        'location' => [
            'Oyster bay, New York',
            'New York',
        ]
    ];

    $reportId = $reportsService->generateReport($postData);

```

Available indices:
- name
- location
- address
- age
- occupation
- school
- phone
- email
- username

### Catch Errors
```php
    $reportId = $reportsService->generateReport($postData);

    if ($reportId) {
        $errors = $reportsSerivce->getErrors();
    }
```

####Errors Sample
```php
    [
        'name' => [
            'message 1',
            'message 2',
        ],
        'location' => [
            'message 1',
            'message 2',
        ],
        ...
    ]
```

### Get Suggestions
```php
    $suggestions = $reportsService->getSuggestions($reportId);

```

#### Suggestions Return Data
```php
    [
        0 => [
            'gender' => 'male',
            'source' => '',
            'first_name' => '',
            'middle_name' => '',
            'last_name' => '',
            'location' => '',
            'street' => '',
            'address' => '',
            'city' => '',
            'state' => '',
            'zip' => '',
            'age' => '',
            'phones' => [],
            'emails' => [],
            'relatives' => [],
            'usernames' => [],
            'addresses' => [],
            'work' => [],
            'school' => [],
            'images' => [],
            'profiles' => [],
        ],
        1 [
            ...
        ]
    ]
```

### Start Search
```php
    $reportsService->startSearch($reportId);
```

If you want to pass suggesstion data as second parameter

```php
    $reportsService->startSearch($reportId, $suggestionData);
```

### On Search Complete
```php
    config(['state.report_id' => $reportId]);
    $reportsService->onSearchComplete();
```

### After Search Complete
```php
    config(['state.report_id' => $reportId]);
    $reportsService->afterSearchComplete();
```

### Delete Report
```php
    config(['state.report_id' => $reportId]);
    $reportsService->deleteReport();
```

### Get Report
```php
    config(['state.report_id' => $reportId]);
    $reportsService->getReport();
```

```php
    [
        'id' => 123,
        'names' => [],
        'ages' => [],
        'cities' => [],
        'addresses' => [],
        'phones' => [],
        'companies' => [],
        'emails' => [],
        'usernames' => []
    ];
```

## Get Report Names
```php
    config(['state.report_id' => $reportId]);
    $reportsService->getReportNames();
```

## Get Report Other Names
```php
    config(['state.report_id' => $reportId]);
    $reportsService->getReportOtherNames();
```

## Get Report Relatives
```php
    config(['state.report_id' => $reportId]);
    $reportsService->getReportRelatives();
```

## Get Report Locations
```php
    config(['state.report_id' => $reportId]);
    $reportsService->getReportLocations();
```

### Handle Datapoint Insertion
```php
    config(['state.report_id' => $reportId]);
    $reportsService->handleInsertedDatapointCombination($data);
```

### Handle Datapoint Update
```php
    config(['state.report_id' => $reportId]);
    $reportsService->handleUpdatedDatapointCombination($data);
```
