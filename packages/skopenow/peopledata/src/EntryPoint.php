<?php
namespace Skopenow\PeopleData;

use Skopenow\PeopleData\Workers\SyncWorker;
use Skopenow\PeopleData\Workers\BackgroundProcessWorker;

/**
 * Class EntryPoint
 * The Entry point for the PeopleData Package
 *
 * @package Skopenow\PeopleData
 */
class EntryPoint
{
    /**
     * These method works as the search commander
     *
     * @param  array $criteria presents the criteria that will used in search
     * @return array $result presents the output from tloxp
     */
    public function search(array $criteria)
    {
        $invoker = new SearchInvoker($criteria);

        // $worker = SyncWorker::class;
        $worker = BackgroundProcessWorker::class;

        $results = $invoker->run(new ResultMerger, new ResultNormalizer, $worker);
        return $results;
    }
}
