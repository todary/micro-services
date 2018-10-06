# Combinations Service
This service is responsible for storing and retrieving combinations

## How to use

### Initialization
```php
    use Skopenow\CombinationsService\EntryPoint;

    $combinationsService = new EntryPoint();
```

### Store new combination with levels
```php
    $levels = [
        ['source' => 'facebook_livein', 'data' => ['any data'], 'level_number' => 1],
        ['source' => 'facebook_livein', 'data' => ['any data 2'], 'level_number' => 2]
    ];

    $combinationId = $combinationsService->store('facebook', $levels);
```

### Get pending combinations
```php
    $combinations = $combinationsService->getPendingCombs();
```

```php
    $combinationsDataExample = [
        'id' => 123,
        'report_id' => 1,
        'source_id' => 15,
        'levels' => [
            0 => [
                'id' => 2,
                'report_id' => 1,
                'combination_id' => 123,
                'level_no' => 1,
                'source' => 'facebook_livin',
                'data' => ['any data'],
                'is_completed' => true,
                'start_time' => '2017-10-19 01:11:00',
                'end_time' => '2017-10-19 01:13:00',
                'exec_time' => 1506508382,
            ],
            1 => [...]
        ]
    ];
```

### Get combination by id

```php
    $combination = $combinationsService->getCombinationLevelById(12);
```

### On level start
Run before start executing the combination
```php
    $combinationsService->onLevelStart(123);
```

### On level end
Run after finish executing the combination
```php
    $combinationsService->onLevelEnd(123);
```