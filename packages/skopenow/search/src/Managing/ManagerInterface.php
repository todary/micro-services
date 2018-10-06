<?php

namespace Skopenow\Search\Managing;

use Skopenow\Search\Fetching\FetcherInterface;
use App\DataTypes\DataTypeInterface;
use Skopenow\Search\Models\SearchResultInterface;
use Skopenow\Search\Models\SearchListInterface;

interface ManagerInterface
{
    public function __construct(FetcherInterface $fetcher);

    public function execute(): SearchListInterface;

    public static function processDataPoint(string $type, DataTypeInterface $dataPoint, ManagerInterface $manager);

    public static function processResult(SearchResultInterface $result, ManagerInterface $manager);

    public static function onSourceCompleted();
}
