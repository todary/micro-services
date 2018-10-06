# Location Service
###### Version 1.0.0
## Introduction
Location package use to manage function related with address and cites
## Installation
1- Update Composer file
```php
"psr-4": {
    "App\\": "app/",
    "Skopenow\\Location\\": "packages/Skopenow/locatioon/src"
}
```
2- Update you bootstrap App
```php
$app->register(Skopenow\Location\providers\LocationServiceProvider::class);
```
And then we run this command from main folder:
```php
composer dump-autoload 
```
## Usage
#### 1- Internally
```php
$inputs = [];
$locationService = loadService("location");
$output = $locationService->MethodName($inputs);
```
#### 2-Via API
Use url to call the service ``` project url/location/Methodname ```
Input must be json object has array of inputs
Routes are
1- Distance function ``` location/distance ```.
2- Find city function ``` location/city ```
3- Find address ``` location/address ```
4- Get lat and Long ``` location/latLng ```
5- Is location in USA ``` location/located ```
6- Get Nearst Cities ``` location/nearest ```
7- Get zipcodes ``` location/zipcodes ```
8- Get Satae from Abv ``` location/stateAbv ```
9- Get State name ``` location/stateName ```
10- Get state from area Code``` location/statebyarea ```
11- Normalize state  ``` location/normalizestate ```
output will be also json object has array of formatted output object
## Inputs & Outputs
Inputs is array acording to the function structure
##### 1- Calculate Distance:
###### input:
```php
$firstloc = ["lat"=>"","lng"=>""];
$secondloc = ["lat"=>"","lng"=>""];
$locationService = loadService("location");
$output = $locationService->calculateDistance($firstloc,$secondloc);
```
###### output:
```php
arrayIterator(["distance"=>11111]);
```
##### 2- Find Cities
###### input:
```php
$inputs = ["oyster bay, NY"];
$locationService = loadService("location");
$output = $locationService->findCities($inputs);
```
###### output:
output will be array of cities with key of the input string
```php
ArrayIterator([
    "oyster bay, NY"=>[
        "name"=>"oyster bay",
        "latLng"=>["lat"=>29, "lng"=>29],
        "zipCode"=>null,
        "population"=>null,
        "size"=>null
    ]
]);
```
##### 3- Find Address
###### input:
```php
$inputs = ["71 Pilgrim Avenue Chevy Chase, MD 20815"];
$locationService = loadService("location");
$output = $locationService->findAddress($inputs);
```
###### output:
output will be array of cities with key of the input string
```php
ArrayIterator([
    "Chevy Chase, MD"=>[
        "city" => "Chevy Chase, MD",
        "country" => "",
        "zip" => "",
        "address" => "71 Pilgrim Avenue Chevy Chase, MD 20815",
        "lat" => "",
        "lon" => "",
    ]
]);
```
##### 4- Find Coordinates
###### input:
```php
$inputs = ["71 Pilgrim Avenue Chevy Chase, MD 20815"];
$locationService = loadService("location");
$output = $locationService->findLatLng($inputs);
```
###### output:
output will be array of cities with key of the input string
```php
ArrayIterator([
    "71 Pilgrim Avenue Chevy Chase, MD 20815"=>[
        "lat" => "",
        "lon" => "",
    ]
]);
```
##### 5- Is Located In USA
###### input:
```php
$inputs = ["Alaska", "Cairo"];
$locationService = loadService("location");
$output = $locationService->isLocatedInUS($inputs);
```
###### output:
output will be array of cities with key of the input string
```php
ArrayIterator([
    "Alaska"=>true,
    "Cairo"=>false
]);
```
##### 6- Find Nearest Cities
###### input:
```php
$inputs = \ArrayIterator(["lat"=>30,"lng"=>29]);
$locationService = loadService("location");
$output = $locationService->findNearestCities($inputs);
```
###### output:
output will be array of cities with key of the input string
```php
ArrayIterator([
    "location"=>["lat"=>30, "lng"=>29, "radius"=>0.014492753623188],
    "cities"=>["Alaska","Oregon"]
]);
```
##### 7- Get zipcodes
###### input:
```php
$inputs = \ArrayIterator(["Alaska"]);
$locationService = loadService("location");
$output = $locationService->getCityZipCodes($inputs);
```
###### output:
output will be array of cities with key of the input string
```php
ArrayIterator(["Alaska"=>[1,2,3]);
```
##### 8- Get Satae from Abv
###### input:
```php
$inputs = \ArrayIterator(["Alaska"]);
$locationService = loadService("location");
$output = $locationService->testGetStateAbv($inputs);
```
###### output:
output will be array of cities with key of the input string
```php
ArrayIterator(["Alaska"=>"AK");
```
##### 9- Get State name 
###### input:
```php
$inputs = \ArrayIterator(["AK"]);
$locationService = loadService("location");
$output = $locationService->getStateName($inputs);
```
###### output:
output will be array of cities with key of the input string
```php
ArrayIterator(["AK"=>"Alaska");
```
##### 10- Get state from area Code
###### input:
```php
$inputs = \ArrayIterator(["304"]);
$locationService = loadService("location");
$output = $locationService->getStateNameByAreaCode($inputs);
```
###### output:
output will be array of cities with key of the input string
```php
ArrayIterator(["304"=>"West Virginia");
```
##### 10- Normalize state
###### input:
```php
$inputs = \ArrayIterator(["New York City"]);
$locationService = loadService("location");
$output = $locationService->normalizeStateName($inputs);
```
###### output:
output will be array of cities with key of the input string
```php
ArrayIterator(["New York City"=>"New York City, New York");
```
