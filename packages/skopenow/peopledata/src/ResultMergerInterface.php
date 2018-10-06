<?php
namespace Skopenow\PeopleData;

interface ResultMergerInterface
{
    public function mergeAll(array $results, ResultMatcherInterface $matcher): array;

    public function merge(OutputModel $result1, OutputModel $result2);
}
