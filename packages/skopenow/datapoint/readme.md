# Datapoint Service

Datapoint service is a micro Skopenow service helps to insert inputs into database using HTTP requests(under construction) or by calling code directly.


## Overview

* [Inputs](#inputs)
* [Outputs](#outputs)
* [Available Indexes](#available-indexes)
* [Special Indexes](#special-indexes)

## Inputs

1. Calling from HTTP request (under construction)
Service URL: `http:://skopenow.com/validate` Method: `POST`
You have to send array of string inputs indexed with singular name of index name.
For ex. `name = ['Peter', 'Peter Mark']`
The same for all validation input even if it just singl input.
For ex. `age = ['15']`

2. Calling directly from code

* calling main datapoint to access dataresourse:
```php
$datapointService = loadService('datapoint');
$datapoint = $datapointService->create();
```
    You can now use those functions:
    1. progressData(string $key, string $dbkey, array $val, array $rescanSetting = [])
    2.
    3.


* adding new datatype into datapoint database:
```php
$datapointService = loadService('datapoint');
$addressDatapoint = $datapointService->create(['addrees' => array($data)]);
```

Notes:
`$personId` must be integer of current person id.
`$combinationId` must be integer of current combination id.
`$data` must be kind of Iterator of available datatypes.
For Ex: 
```php
[
    'address' => array($address1[, $address2, ...]) //must be type of address datatype
]
```

3. Bulk Inputs
You can send bulk of inputs like names, ages, emails .. etc.
For ex.

```php
    [
        'address' => array($address1[, $address2, ...]),
        'age' => array($age1[, $age2, ...]),
        'email' => array($email1[, $email2, ...]),
        'name' => array($name1[, $name2, ...]),
        'nickname' => array($nickname1[, $nickname2, ...]),
        'phone' => array($phone1[, $phone2, ...]),
        'relatives' => array($relatives1[, $relatives2, ...]),
        'school' => array($school1[, $school2, ...]),
        'username' => array($username1[, $username2, ...]),
        'website' => array($website1[, $website2, ...]),
        'work' => array($work1[, $work2, ...]),
    ]
```

## Outputs

1. Calling from HTTP request: (under construction)
The service execution works directly on the database.
If inputs contain invalid index Exception will be thrown.

2. Calling directly from code:
The service execution works directly on the database.
If inputs contain invalid index Exception will be thrown.

3. Bulk Inputs:
The service execution works directly on the database.
If inputs contain invalid index Exception will be thrown.

## Available indexes

* address
* age
* email
* name
* nickname
* phone
* relatives
* school
* username
* website
* work
