# Validation Service

Validation service is a micro Skopenow service helps to validate inputs using
HTTP requests or by calling code directly.


## Overview

* [Inputs](#inputs)
* [Outputs](#outputs)
* [Available Indexes](#available-indexes)
* [Special Indexes](#special-indexes)

## Inputs

1. Calling from HTTP request
Service URL: `http:://skopenow.com/validate` Method: `POST`
You have to send array of string inputs indexed with singular name of index name.
For ex. `name = ['Peter', 'Peter Mark']`
The same for all validation input even if it just singl input.
For ex. `age = ['15']`

2. Calling directly from code
Use clsss: `Skopenow\Validation\Validation`
Next step you have to instantiate the class.
For ex. `$validation = new Validation`
Now you have to call `$validation->validate($inputs)`
$inputs must be kind of SPL Iterators.

3. Bulk Inputs
You can send bulk of inputs like names, ages, emails .. etc.
For ex.

```
    [
        'name' => ['Peter', 'Peter Mark'],
        'age' => ['15', '150'],
        'email' => ['em@qts.net', 'email', '20'],
        'address' => ['11 Oyster Bay , NY', 'Oyster Bay , NY'],
        'birthdate' => ['2006/01/01', '1'],
        'location' => ['Oyster Bay , NY', 'Oyster Bay , N'],
        'occupation' => ['Web Developer, QTS', 'Web, Developer, QTS'],
        'phone' => ['2222 222 222', '222 222 222'],
        'url' => ['http://qts.net', 'url@'],
        'username' => ['username', 'username@qts.net']
    ]
```

## Outputs

1. Calling from HTTP request
Output will be in json format look like the following:

```
    {
        "name": {
            "0": {
                "input": "Mark",
                "isValid": false,
                "normalized": null,
                "error": "Input must contains at least firstname and lastname, . - symbols are allowed"
            },
            "1": {
                "input": "Peter Mark",
                "isValid": true,
                "normalized": "Peter Mark",
                "error": null
            }
        }
    }
```

For the other input example above output expected to be like the following:
```
    {
        "age": {
            "0": {
                "input": "15",
                "isValid": true,
                "normalized": 15,
                "error": null
            }
        }
    }
```
Or may kind of kind of error if you send invalid index
For ex. `lastname = ['Mark']`
`{"error":"Invalid validation type: \"lastname\""}`

2. Calling directly from code
Output will be ArrayIterator of the results.
If inputs contain invalid index Exception will be thrown.

3. Bulk Inputs
Output will be something like the following in case of HTTP request:

```
    {
        "name": {
            "0": {
                "input": "Mark",
                "isValid": false,
                "normalized": null,
                "error": "Input must contains at least firstname and lastname, . - symbols are allowed"
            },
            "1": {
                "input": "Peter Mark",
                "isValid": true,
                "normalized": "Peter Mark",
                "error": null
            }
        },
        "age": {
            "0": {
                "input": "15",
                "isValid": true,
                "normalized": 15,
                "error": null
            }
        },
        "email": {
            "0": {
                "input": "em@qts.net",
                "isValid": true,
                "normalized": "em@qts.net",
                "error": null
            },
            "1": {
                "input": "email",
                "isValid": false,
                "normalized": null,
                "error": "Input must be valid email address."
            },
            "2": {
                "input": "20",
                "isValid": false,
                "normalized": null,
                "error": "Input must be valid email address."
            }
        },
        "address": {
            "0": {
                "input": "11 Oyster Bay , NY",
                "isValid": true,
                "normalized": "11 Oyster Bay , NY",
                "error": null
            },
            "1": {
                "input": "Oyster Bay , NY",
                "isValid": false,
                "normalized": null,
                "error": "Input must be full address."
            }
        },
        "location": {
            "0": {
                "input": "Oyster Bay , NY",
                "isValid": true,
                "normalized": "Oyster Bay , NY",
                "error": null
            },
            "1": {
                "input": "Oyster Bay , N",
                "isValid": false,
                "normalized": null,
                "error": "Location state must be more than 2 alphabets"
            }
        },
        "occupation": {
            "0": {
                "input": "Web Developer, QTS",
                "isValid": true,
                "normalized": "Web Developer, QTS",
                "error": null
            },
            "1": {
                "input": "Web Developer, QTS",
                "isValid": true,
                "normalized": "Web Developer, QTS",
                "error": null
            }
        },
        "phone": {
            "0": {
                "input": "2222 222 222",
                "isValid": true,
                "normalized": "2222222222",
                "error": null
            },
            "1": {
                "input": "222 222 222",
                "isValid": false,
                "normalized": null,
                "error": "Input must be at least 10 numbers."
            }
        },
        "url": {
            "0": {
                "input": "http://qts.net",
                "isValid": true,
                "normalized": "http://http://qts.net",
                "error": null
            },
            "1": {
                "input": "url@",
                "isValid": false,
                "normalized": null,
                "error": "Input must be valid url."
            }
        },
        "username": {
            "0": {
                "input": "username",
                "isValid": true,
                "normalized": "username",
                "error": null
            },
            "1": {
                "input": "username@qts.net",
                "isValid": false,
                "normalized": null,
                "error": "Input must be at least 3 characters, alphabets with @ _ - symbols only are allowed."
            }
        },
        "birthdate": {
            "0": {
                "input": "12/22/2006",
                "isValid": true,
                "normalized": "12/22/2006",
                "error": null
            },
            "1": {
                "input": "22",
                "isValid": false,
                "normalized": null,
                "error": "Input must be valid date."
            }
        }
    }
```
But if in case of calling directly from code the output will be ArrayIterator
Contains the same data.

## Available indexes

* address
* age
* birthdate
* email
* location
* name
* nameparts - sepated name (first, middle, last)
* Occupation
* phone
* school

* username
* url

## Special Indexes
Special inputs are normal inputs with only different data structure.
The input data type must be the same data type of normal indexes and the output will be exactly the same output data type.

* nameparts - sepated name (first, middle, last)

#### Inputs
The input expected to be something like the following:
```
    nameparts = [
        [
            "input" => "Wael Salah Elbadry-Ahmed",
            "splitted" => [
                [
                    "firstName" => "wael",
                    "middleName" => "salah",
                    "lastName" => "elbadry-ahmed"
                ],
                [
                    "firstName" => "wael",
                    "middleName" => "salah",
                    "lastName" => "elbadry"
                ],
                [
                    "firstName" => "wael",
                    "middleName" => "salah",
                    "lastName" => "ahmed"
                ]
            ]
        ]
            ,
            "1" =>  [
                "input" =>
                "Rob t douglas",
                "splitted" =>  [
                    [
                        "firstName" => "rob",
                        "middleName" => "t",
                        "lastName" => "douglas"
                    ]
                ]
            ],
            "2" =>  [
                "input" => "John Smith sr",
                "splitted" =>  [
                    [
                        "firstName" => "john",
                        "middleName" => "",
                        "lastName" => "smith"
                    ]
                ]
            ]
        ]
```

#### Outputs

The out will be something like the following:
```
    {
        "nameparts": {
            "0": {
                "input": {
                    "firstName": "wael",
                    "middleName": "salah",
                    "lastName": "elbadry-ahmed"
                },
                "isValid": true,
                "normalized": {
                    "firstName": "wael",
                    "middleName": "salah",
                    "lastName": "elbadry-ahmed"
                },
                "error": null
            },
            "1": {
                "input": {
                    "firstName": "wael",
                    "middleName": "salah",
                    "lastName": "elbadry"
                },
                "isValid": true,
                "normalized": {
                    "firstName": "wael",
                    "middleName": "salah",
                    "lastName": "elbadry"
                },
                "error": null
            },
            "2": {
                "input": {
                    "firstName": "wael",
                    "middleName": "salah",
                    "lastName": "ahmed"
                },
                "isValid": true,
                "normalized": {
                    "firstName": "wael",
                    "middleName": "salah",
                    "lastName": "ahmed"
                },
                "error": null
            },
            "3": {
                "input": {
                    "firstName": "rob",
                    "middleName": "t",
                    "lastName": "douglas"
                },
                "isValid": false,
                "normalized": null,
                "error": "MiddleName must be at least 3 characters, words and . - symbols only"
            },
            "4": {
                "input": {
                    "firstName": "john",
                    "middleName": "",
                    "lastName": "smith"
                },
                "isValid": true,
                "normalized": {
                    "firstName": "john",
                    "middleName": "",
                    "lastName": "smith"
                },
                "error": null
            }
        }
    }
```
