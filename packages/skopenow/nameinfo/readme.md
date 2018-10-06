# NameInfo Package
###### Version 1.0.0
## Introduction
This package used to manipulate names like splitting names, checking if name is unique, and retrieve 
the nicknames of specific user 
## Installation
1- Update Composer file
```php
"psr-4": {
    "App\\": "app/",
    "Skopenow\\NameInfo\\": "packages/Skopenow/NameInfo/src"
}
2- Update your bootstrap App
```php
$app->register(Skopenow\NameInfo\providers\NameInfoServiceProvider::class);
```
And then we run this command from main folder:
```php
composer dump-autoload 
```
## Usage
#### 1- Internally
* Name Splitter
input: is an array iterator of names
```php
$names = new \ArrayIterator([
		"Rob Douglas",
		"Rob Douglas jr",
		"John Smith sr",
		"Victorio Gruezo jr"
]);
$nameInfo = loadService("NameInfoService");
$output = $nameInfo->nameSplit($names);
```
* Retrieving Nick Names
input: is an array iterator of names
```php
$names = new \ArrayIterator([
		"Rob Douglas",
		"Kazi Anwarul Mamun",
		"Mohnish Magarde",
		"David Will"
	]);
$nameInfo = loadService("NameInfoService");
$output = $nameInfo->nickNames($names);
```
* Checking names is unique
input: is an array iterator of names
```php
$names = new \ArrayIterator([
								[
									"firstName" => "Rob",
									"middleName" => "",
									"lastName" => "Douglas"
								],
								[
									"firstName" => "Kazi",
									"middleName" => "",
									"lastName" => "Magarde"
								],
								[
									"firstName" => "David",
									"middleName" => "",
									"lastName" => "Will"
								]
	]);
$nameInfo = loadService("NameInfoService");
$output = $nameInfo->uniqueName($names);

```
#### 2-Via API
* nameSplit
``` To call nameSplit service use url ```
project url/nameInfo/nameSplit/[post-data]

Input must be json object like this
```input json
[
  "Rob Douglas jr rob@yahoo.com",
  "John Smith sr",
  "Victorio Gruezo jr"
]
```
output will be also json object has array of output object
```php
[
  {
    "input": "John smith david-will John@yahoo.com",
    "splitted": [
      {
        "firstName": "john",
        "middleName": "smith",
        "lastName": "david-will"
      },
      {
        "firstName": "john",
        "middleName": "smith",
        "lastName": "david"
      },
      {
        "firstName": "john",
        "middleName": "smith",
        "lastName": "will"
      }
    ]
  },
  {
    "input": "Rob Douglas jr",
    "splitted": [
      {
        "firstName": "rob",
        "middleName": "",
        "lastName": "douglas"
      }
    ]
  }
 ]
```
* nickNames
```
``` To call nickNames service use url ```
project url/nameInfo/nickNames/[post-data]

input json
[
  "Rob Douglas",
  "Kazi Anwarul Mamun",
  "Mohnish Magarde",
  "David Will"
]
```
output will be also json object
```php
[
  "rob",
  "Mamun",
  "Magarde",
  "Will"
]
```
uniqueName
``` To call uniqueName service use url ```
project url/nameInfo/uniqueName/[post-data]

```input json
```
[
  {
    "firstName": "Rob",
    "middleName": "",
    "lastName": "Douglas"
  },
  {
    "firstName": "Kazi",
    "middleName": "",
    "lastName": "Magarde"
  },
  {
    "firstName": "David",
    "middleName": "",
    "lastName": "Will"
  }
]
```
output will be a json object of user names and their unique status (0, 1)
```
[
  {
    "input": {
      "firstName": "rob",
      "middleName": "",
      "lastName": "douglas"
    },
    "unique": 0
  },
  {
    "input": {
      "firstName": "kazi",
      "middleName": "",
      "lastName": "magarde"
    },
    "unique": 1
  },
  {
    "input": {
      "firstName": "david",
      "middleName": "",
      "lastName": "will"
    },
    "unique": 0
  }
]
```
Examples:
```
Initialize the entry point 

$entryPoint = new EntryPoint();

1. To use the Entry point nameSplit()
$inputArray = array
(
    [0] => Wael Salah Elbadry-Ahmed wael@yahoo.com
    [1] => Rob Douglas jr
    [2] => John Smith sr
    [3] => Victorio Gruezo jr
);
$nameSplitterIterator = new \ArrayIterator($inputArray);
$output = $entryPoint->nameSplit($nameSplitterIterator);
print_r($output);

displays

ArrayIterator Object
(
    [storage:ArrayIterator:private] => Array
        (
            [0] => Array
                (
                    [input] => Rob Douglas jr
                    [splitted] => Array
                        (
                            [0] => Array
                                (
                                    [firstName] => rob
                                    [middleName] => 
                                    [lastName] => douglas
                                )

                        )

                )

            [1] => Array
                (
                    [input] => John Smith david-will
                    [splitted] => Array
                        (
                            [0] => Array
                                (
                                    [firstName] => john
                                    [middleName] => smith
                                    [lastName] => david-will
                                )

                            [1] => Array
                                (
                                    [firstName] => john
                                    [middleName] => smith
                                    [lastName] => david
                                )

                            [2] => Array
                                (
                                    [firstName] => john
                                    [middleName] => smith
                                    [lastName] => will
                                )

                        )

                )

            [2] => Array
                (
                    [input] => Victorio Gruezo jr
                    [splitted] => Array
                        (
                            [0] => Array
                                (
                                    [firstName] => victorio
                                    [middleName] => 
                                    [lastName] => gruezo
                                )

                        )

                )

        )

)
```2. To use the Entry point uniqueName()
```
$names = array(
                  [
                      "firstName" => "Rob",
                      "middleName" => "",
                      "lastName" => "Douglas"
                  ],
                  [
                      "firstName" => "Kazi",
                      "middleName" => "",
                      "lastName" => "Magarde"
                  ],
                   [
                       "firstName" => "David",
                       "middleName" => "",
                       "lastName" => "Will"
                   ]
    );
$uniqueNamesIterator = new \ArrayIterator($names);
$output = $entryPoint->uniqueName($uniqueNamesIterator, $isRelative = false, $apiKey = "your api key");
print_r($output);

displays

ArrayIterator Object
(
    [storage:ArrayIterator:private] => Array
        (
            [0] => Array
                (
                    [input] => Array
                        (
                            [firstName] => rob
                            [middleName] => 
                            [lastName] => douglas
                        )

                    [unique] => 0
                )

            [1] => Array
                (
                    [input] => Array
                        (
                            [firstName] => kazi
                            [middleName] => 
                            [lastName] => magarde
                        )

                    [unique] => 1
                )

            [2] => Array
                (
                    [input] => Array
                        (
                            [firstName] => david
                            [middleName] => 
                            [lastName] => will
                        )

                    [unique] => 0
                )


        )

)
