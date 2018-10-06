# Acceptance Service
###### Version 1.0.0

## Usage
#### 1- Internally
```php
$flags = 8192;
$resultData = new \App\Models\ResultData($url);
$acceptanceObj = loadService("acceptance");
$output = $acceptanceObj->checkAcceptance($resultData,  $flags);
```
Output   will be array of 
```php
[
    "acceptance"=>[
        "acceptance"=>true,
        "reason" => 45
    ],
    "visible"=>[
        "visible"=>true,
        "reason"=>0
    ]
]
```
#### 2-Via API
Use url to call the service ``` project url/acceptance ```
Input must be json object has array of inputs
```php
{
    'resultData' = new \App\Models\ResultData($url);
    'flags'=>''
}
```
output will be also json object has array of formatted output object
```php
{
    "acceptance":
    {
    "acceptance":true,
    "reason":0
    },
    "visible":{
        "acceptance":true,
         "reason":0
    }
}
```
