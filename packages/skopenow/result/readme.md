# Result Service
###### Version 1.0.0

## Usage
####  1- Save Result 
```php
$resultDataObj = new ResultData();
$resultObj = loadService("result");
$output = $resultObj->save($resultDataObj);
```
###### Output:
```php
{
    "http://facebook.com": {
        "action" : "save",  //string
        "resultId" : 58987, //integer
        "reason" : 33554689 //integer
    }
}
```
#### 2- Update
You can update data by sending url or result id one of them at least
```php
$resultDataObj = new ResultData();
$resultObj = loadService("result");
$resultId = $resultObj->update(Array $data, $resultId = null, $url = null);
```
###### Output:
Output is result id
#### 3- Update Identities
You can update the same identities for array of results
```php
$resultDataObj = new ResultData();
$resultObj = loadService("result");
$output = $resultObj->updateIdentities(Array results, array $identities);
```
###### Output:
array of results ids with the status of update
```php
$output[$result->id] = true;
```
#### 4- Update By Criteria
You can update results by criteria
```php
$resultDataObj = new ResultData();
$resultObj = loadService("result");
$output = $resultObj->updateByCriteria(Array $data, DBCriteriaInterface $criteria);
```
#### 5- After Save
You can run aftersave event 
```php
$resultDataObj = new ResultData();
$resultObj = loadService("result");
$status = $resultObj->afterSave(ResultData $result);
```
#### 6- Delete Results
You can delete results by ids
```php
$resultsIds = [1,2,3];
$deleteType = 1;
$resultObj = loadService("result");
$output = $resultObj->delete(array $resultsIds, int $deleteType);
```
#### 7- Visible Results
You can show or hide results by ids
```php
$resultsIds = [1,2,3];
$isVisible = 1;
$resultObj = loadService("result");
$output = $resultObj->visibleResults(array $resultsIds, int $isVisible);
```
###### Output:
Output is bool
#### 8- Get Result
You can get result by id or url
```php
$resultObj = loadService("result");
$output = $resultObj->getResult($id=null, $url=nul);
```
###### Output:
Output object of ResultData
#### 9- Get Result By Id
You can get result by id
```php
$resultObj = loadService("result");
$output = $resultObj->getResultById($id);
```
###### Output:
Output object of ResultData
#### 10- Get Result By url
You can get result by url
```php
$resultObj = loadService("result");
$output = $resultObj->getResultByUrl($url);
```
###### Output:
Output object of ResultData
#### 9- Get Results
```php
$resultObj = loadService("result");
$output = $resultObj->getResults($criatiria);
```
###### Output:
array of ResultData Object
#### 10- Save Rejected Results
```php
$resultObj = loadService("result");
$output = $resultObj->saveRejected(ResultData $resultData, int $reason);
```
###### Output:
Output is bool
#### 11- Get Rejected Results
You can get rejected results according to report id or reason
```php
$resultObj = loadService("result");
$output = $resultObj->getRejectedResults($reportId = null, $reason = null);
```
###### Output:
array of ResultData Object
#### 12- Delete Rejected Results
```php
$resultObj = loadService("result");
$output = $resultObj->deleteRejected($reportId = null);
```
###### Output:
Output is bool
#### 13- Create Default Siblings
```php
$resultObj = loadService("result");
$output = $resultObj->createDefaultSiblings(ResultData $resultData);
```
###### Output:
Output is bool
#### 14- Update Default Siblings
```php
$resultObj = loadService("result");
$output = $resultObj->updateSiblings(array $data, DBCriteriaInterface $criteria);
```
###### Output:
Output is bool
