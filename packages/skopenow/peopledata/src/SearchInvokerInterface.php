<?php

namespace Skopenow\PeopleData;

use App\Models\ApiAccount;
use Skopenow\PeopleData\Clients\ClientInterface;
use Skopenow\PeopleData\Clients\CurlClient;
use Skopenow\PeopleData\Clients\SoapClient;
use Skopenow\PeopleData\Workers\WorkerInterface;

interface SearchInvokerInterface
{
    public function __construct(array $searchInputs);

    public function run(ResultMergerInterface $merger, ResultNormalizerInterface $normalizer, string $workerClass);

    public function getApiAccount($api): ApiAccount;

    public function getApiClient($api, $account = null): ClientInterface;
}
