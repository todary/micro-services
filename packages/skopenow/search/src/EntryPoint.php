<?php

namespace Skopenow\Search;

use Skopenow\Search\Models\SearchResultInterface;
use Skopenow\Search\Models\CriteriaInterface;
use Skopenow\Search\Models\SearchListInterface;
use Skopenow\Search\Models\Criteria;

class EntryPoint
{
    private $sources = [];

    public function __construct()
    {
        $this->sources = require __DIR__.'/../config/sources.php';
    }

    public function fetch(string $source, CriteriaInterface $criteria): SearchListInterface
    {
        $sourceInfo = $this->sources[$source];
        $className = $sourceInfo['fetcher'];
        $fullClassName = "Skopenow\Search\Fetching\Fetchers\\$className";

        $fetcher = new $fullClassName($criteria);
        $fetcher->execute();

        return $fetcher->getOutput();
    }

    public function manage(string $source, CriteriaInterface $criteria): SearchListInterface
    {
        $sourceInfo = $this->sources[$source];
        $className = $sourceInfo['fetcher'];
        $fullClassName = "Skopenow\Search\Fetching\Fetchers\\$className";
        $fetcher = new $fullClassName($criteria);
        
        $className = $sourceInfo['manager'];
        $fullClassName = "Skopenow\Search\Managing\Managers\\$className";
        $manager = new $fullClassName($fetcher);

        return $manager->execute();
    }

    public function processResults(string $source, array $results)
    {
        $criteria = new Criteria;
        $criteria->results = $results;

        return $this->manage($source, $criteria);
    }


    public function runSearch($report, $combinationLevel)
    {
        $criteria = Criteria::fromCombination($combinationLevel);
        return $this->manage($combinationLevel['source'], $criteria);
    }

    public function runOnSourceCompleted(string $managerClassName)
    {
        $managerClassName::onSourceCompleted();
    }
}
