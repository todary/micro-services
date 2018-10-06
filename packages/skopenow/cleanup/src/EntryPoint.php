<?php

/**
 * cleanup service is where the results will be filtered and cleanedup .
 *
 * @author Ahmed Samir <ahmedsamir732@gmail.com>
 */
namespace Skopenow\Cleanup;

use App\Models\Result;
use Skopenow\Cleanup\FilterResults\FilterResultsInterface;
use Skopenow\Cleanup\FiltrationModel;
use Skopenow\Cleanup\Helpers\CommonHelpers;
use Skopenow\Cleanup\Helpers\FilterProcessor;
use Skopenow\Cleanup\Helpers\UpdateFiltrationResults;
use Skopenow\Cleanup\Helpers\SearchForChilds;
use Skopenow\Cleanup\ShowInvisible\ShowInvisibleInterface;
use Skopenow\Cleanup\ShowInvisible\ShowLessThanThree;
use Skopenow\Cleanup\ShowInvisible\showInvisibleUsernames;

class EntryPoint
{

    use CommonHelpers;

    protected $filtrationModel;
    /**
     * [__construct load the class dependencies]
     */
    public function __construct($allResults, $relationships, array $pendingResults = [])
    {
        $this->filtrationModel = new FiltrationModel();
        $this->init($allResults, $relationships, $pendingResults);
        // dd($this->filtrationModel);
    }

    public function __get($name)
    {
        return $this->filtrationModel->getAttribute($name);
    }

    public function __set($name, $value)
    {
        $this->filtrationModel->setAttribute($name, $value);
    }

    protected function init($allResults, $relationships, $pendingResults)
    {
        $this->allResults = $allResults;
        $this->pendingResults = $pendingResults;
        $this->relationships = new Helpers\Relationships($relationships);
        $this->resultService = loadService("result");
        $this->scoringFlags = loadData("scoringFlags");
    }

    public function process()
    {
        $this->filterResults();
        // dd(array_column($this->resultsToBeVisible, 'url'), array_column($this->resultsToBeDeleted, 'url'));
        ## start the show invisible results .
        $this->showInvisibleResults();
        // dd(array_column($this->resultsToBeVisible, 'url'), array_column($this->resultsToBeDeleted, 'url'));
        $this->applyChanges();
        
        ## check for childs results and match with parent.
        $parentChilds = $this->searchForChilds();
        $this->saveParentChilds($parentChilds);
        // dd($this->filtrationModel);


        return $this->filtrationModel;
    }

    protected function applyChanges()
    {
        dump([
            'resultsToBeVisible' => $this->resultsToBeVisible,
            'resultsToBeDeleted' => $this->resultsToBeDeleted,
        ]);
        $this->resultService->visibleResults(array_column($this->resultsToBeVisible, 'id'), 0);

        $this->resultService->visibleResults(array_column($this->resultsToBeDeleted, 'id'), 1);
    } 

    protected function filterResults()
    {
        $this->getFiltrationObjects();
        ## get Results will never be deleted .
        $this->filtrationRunner($this->filtrationObjects);
        // dd($this->resultsToBeVisible, $this->resultsToBeDeleted);
        ## start shown still invisible results .
        $this->updateResults($this->allResults, $this->resultsToBeVisible, $this->resultsToBeDeleted);

        return $this;
    }

    protected function getFiltrationObjects()
    {
        $filtrationObjects = array(
            "NeverDeletedResults" => new FilterResults\NeverDeletedResults($this->allResults, $this->resultService),
            "VerifiedResults" => new FilterResults\VerifiedResults($this->allResults, $this->resultService),
            "OnlyOneRelative" => new FilterResults\OnlyOneRelativeResults($this->allResults, $this->resultService),
        );
        $this->filtrationModel->setAttribute("filtrationObjects", $filtrationObjects);
    }

    protected function showInvisibleResults()
    {
        $showInvisibleObjects = array(
            "showLessThanThree" => new ShowLessThanThree($this->filtrationModel),
            "showInvisibleUsernames" => new showInvisibleUsernames($this->filtrationModel),
        );
        $this->showInvisibleRunner($showInvisibleObjects);
    }

    protected function addResultsToBeVisible(array $resultsIds)
    {
        $this->resultsToBeVisible = array_merge($this->resultsToBeVisible, $resultsIds);
        $this->resultsToBeVisible = $this->uniqueResults($this->resultsToBeVisible);
    }

    protected function addResultsToBeDeleted(array $resultsIds)
    {
        $this->resultsToBeDeleted = array_merge($this->resultsToBeDeleted, $resultsIds);
        $this->resultsToBeDeleted = $this->uniqueResults($this->resultsToBeDeleted);
    }

    public function filtrationRunner(array $filtrationObjects)
    {
        foreach ($filtrationObjects as $filtrationObject) {
            if ($filtrationObject instanceof FilterResultsInterface) {
                $filterProcessor = new FilterProcessor($filtrationObject, $this->relationships);
                $this->runFilterProcess($filterProcessor);
            }
        }
    }

    protected function runFilterProcess(FilterProcessor $filterProcessor)
    {
        $data = $filterProcessor->process();
        $this->addResultsToBeVisible($data['matchedResults']);
        $this->addResultsToBeDeleted($data['nonMatchedResults']);
        return $this;
    }

    protected function showInvisibleRunner(array $showInvisibleObjects)
    {
        foreach ($showInvisibleObjects as $showInvisibleObject) {
            if ($showInvisibleObject instanceof ShowInvisibleInterface) {
                $showInvisibleProcessor = new Helpers\ShowInvisibleProcessor($showInvisibleObject);
                $this->runShowInvisibleProcess($showInvisibleProcessor);
            }
        }
    }

    protected function runShowInvisibleProcess($showInvisibleProcessor)
    {
        $toBeVisibleResults = $showInvisibleProcessor->process();
        $this->addResultsToBeVisible($toBeVisibleResults);
    }

    public function updateResults()
    {
        $updateFiltrationResults = new UpdateFiltrationResults($this->allResults, $this->resultsToBeVisible, $this->resultsToBeDeleted);
        $updateFiltrationResults->update();

        $this->allResults = $updateFiltrationResults->getAllResults();
        $this->resultsToBeVisible = $updateFiltrationResults->getResultsToBeVisible();
        $this->resultsToBeDeleted = $updateFiltrationResults->getResultsToBeDeleted();
    }

    protected function searchForChilds()
    {
        $searchForChilds = new SearchForChilds($this->filtrationModel);
        $parentChilds = $searchForChilds->search();
        return $parentChilds;
    }

    protected function saveParentChilds($parentChilds)
    {
        $status = false;
        $resultService = loadService('result');
        foreach ($parentChilds as $parentChild) {
            $status = $resultService->saveParentChilds($parentChild['parent'], $parentChild['childs']);
        }

        return $status;
    }

}
