# Formatter Package
###### Version 1.0.0
## Introduction
Formatter package use to format names, websites, emails, phones, and Addresses
## Installation
1- Update Composer file
```php
"psr-4": {
    "App\\": "app/",
    "Skopenow\\Formatter\\": "packages/Skopenow/Formatter/src"
}
```
2- Update you bootstrap App
```php
$app->register(Skopenow\Formatter\providers\FormatterServiceProvider::class);
```
And then we run this command from main folder:
```php
composer dump-autoload 
```
## Usage
#### 1- Internally
```php
$inputs = ["names"=>["<h3>   some one Name<h3>"]];
$namesInputs = new \ArrayIterator($inputs);
$formaterObj = app("FromaterServices");
$output = $formaterObj->format($namesInputs);
```
Notes: It can be added many types of inputs
```php
$inputs = ["names"=>["<h3>   some one Name<h3>"],"emails"=>["SOMEONE@SITE<COM"]]; 
```
#### 2-Via API
Use url to call the service ``` project url/formatter ```
Input must be json object has array of inputs
```php
{
    "names": [
        "<h3>someone name</h3>",
        "someone else"
    ]
}
```
output will be also json object has array of formatted output object
```php
{
    "names": [
        {
            "original": "<h3>someone name</h3>",
            "formatted": "Someone Name"
        },
        {
            "original": "someone else",
            "formatted": "Someone Else"
        }
    ]
}
```
## Inputs
Inputs is array iterators has key "Formatting Method you want to make" one of [names, phones, websites, emails, or address] and its value is array of inputs you want to format
###### Example:-
names take array of strings
``` ["names"=>["someone name","someone other"]] ```
emails also take array of string
```["emails"=>["someone@company.com","other@company.net"]] ```
websites also take array of string
``` ["websites"=>["site.com","http://site2.net"]] ```
phones also take array of string
``` ["phones"=>["site.com","http://site2.net"]] ```
addresses take array of the adress
``` $address[add] = "15 John kennedy lane" //short address```
``` $address[city] = "New York, NY" //Name of city```
``` $address[zpc] = "10001" //zip code```
``` $address[country] = "USA" //Countery name ```
addresses takes array of the previous array
``` ["addresses"=>[$address1,$address2]] ```
## Output
It will be array iterators with array of the requested methods has the original and formatted inputs
###### Example:-
names:
```php
ArrayIterator Object(
    [storage:ArrayIterator:private] => Array(
        [names] => ArrayIterator Object(
            [storage:ArrayIterator:private] => Array(
                [0] => Array(
                    [original] => some one  Name     
                    [formatted] => Some One  Name
                )
            )
        )
    )
)
```
emails:
```php
ArrayIterator Object(
    [storage:ArrayIterator:private] => Array(
        [emails] => ArrayIterator Object(
            [storage:ArrayIterator:private] => Array(
                [0] => Array(
                    [original] => Someone@company.com
                    [formatted] => someone@company.com
                )
                [1] => Array(
                    [original] => Other@company.net    
                    [formatted] => other@company.net
                )
            )
        )
    )
)
```
websites:
```php
ArrayIterator Object(
    [storage:ArrayIterator:private] => Array(
        [websites] => ArrayIterator Object(
            [storage:ArrayIterator:private] => Array(
                [0] => Array(
                    [original] => facebook.com
                    [formatted] => http://facebook.com
                )
                [1] => Array(
                    [original] => https://google.com
                    [formatted] => https://google.com
                )
            )
        )
    )
)
```
phones:
```php
ArrayIterator Object(
    [storage:ArrayIterator:private] => Array(
        [phones] => ArrayIterator Object(
            [storage:ArrayIterator:private] => Array(
                [0] => Array(
                    [original] => 00999999999999
                    [formatted] => (009) 999-99999999
                )
                [1] => Array(
                    [original] => Tel:00999999999999
                    [formatted] => (009) 999-99999999
                )
            )
        )
    )
)
```
addresses:
```php
ArrayIterator Object(
    [storage:ArrayIterator:private] => Array(
        [addresses] => ArrayIterator Object(
            [storage:ArrayIterator:private] => Array(
                [0] => Array(
                    [original] => Array(
                        [add] => 15 John kennedy lane
                        [city] => New York, NY
                        [country] => USA
                        [zpc] => 10001
                    )
                    [formatted] => 15 John kennedy ln, New York, NY 10001, USA
                )
            )
      )
    )
)
```