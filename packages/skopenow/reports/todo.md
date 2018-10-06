##Todo report service
- combinations order combination maker
- After search complete
    + update children data with parent results $resultsBridge->setChildsDataFromParents()
    + if report is_rescan => send email rescan completed
    +  generate pdf, update downloads count
    +  get data from api SearchApiController::onSearchCompleted()
- Too many services are used inside this service needs another look
- CombinationsGenerator setData needs another look
- Remove LinkedinCombinationsGenerator outside Generators folder
- Errors returned from validation needs to set code for required fields instead of textual error
- Tests needs to be written but we need to mock all services used
- SearchManager::fillReportInfo bad db tables access (results, main_source) needs to be replaced by appropriate service