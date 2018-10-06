# Scoring Service

Scoring service is a service responsible for manage the scoring of all
results found by the search.


## Overview

* [Methods](#methods)
* [Inputs](#inputs)
* [Outputs](#outputs)
* [Available Indexes](#available-indexes)
* [Special Indexes](#special-indexes)

## Methods

There are two public methods you can call (init , rescore) .

1. Init method is the main method resposible scoring the results
   depending on the matching status returning from the matching service .

2. Rescoring method responsible for rescoring results with the data found
   during the search , you may send it the matched status data as the first parameter
   or you can send the new scoring data with the third argument as false .


## Inputs

1. Matching data : you should send the matching status with the next format
```
     $data[
      	matchingData => [
      		name	=> [
              status => bool ,
          		identities => array [
          			fn	=> bool (true , false) ,
          			mn	=> bool (true, false),
          			ln	=> bool (true, false),
          			otherName	=> bool (true, false),
          		],
          		matchWith => string "Ahmed Samir" ,
      		],
      		location => [
              status => bool ,
          		identities => array [
          			exct-sm => bool ,
          			exct-bg => bool ,
          		]
          		matchWith => string  "Oyster Bay ,NY" ,
      		],
      	]
      	resultsCount => int ,
      	main_source	 => string ,
      	source	=> string ,
      	isProfile	=> bool ,
      	isRelative	=> bool ,
     ]
```

2. scoreData
```
$oldScore [
	identityScore => float ,
	listCountScore => float ,
	sourceTypeScore => float ,
	resultTypeScore => float ,
	identities => [
  		fn ,
  		ln ,
  		em ,
	]
]
```

## Outputs

1. The score data of the result :

```
$scoreData[
    listCountScore  =>  float ,
    resultTypeScore =>  float ,
    sourceTypeScore =>  float ,
    identityScore   =>  float ,
    identities      =>  array(fn , ln ,em ,ex) ,
    identifiers     =>  array(rob douglas , oyster bay , ny) ,
    flags           =>  int ,
    finalScore      =>  float ,
]

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


## Available indexes
* data
  * matchingData
  * resultsCount
  * main_source
  * source
  * isProfile
  * isRelative
  
* ScoringData
  * identityScore
  * listCountScore
  * sourceTypeScore
  * resultTypeScore
  * identities
