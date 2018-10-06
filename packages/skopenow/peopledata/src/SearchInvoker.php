<?php

namespace Skopenow\PeopleData;

use App\Models\ApiAccount;
use Skopenow\PeopleData\Clients\ClientInterface;
use Skopenow\PeopleData\Clients\CurlClient;
use Skopenow\PeopleData\Clients\SoapClient;
use Skopenow\PeopleData\Workers\WorkerInterface;

class SearchInvoker implements SearchInvokerInterface
{
    protected $searchInputs = [];

    public function __construct(array $searchInputs)
    {
        $this->searchInputs = $searchInputs;
    }

    public function run(ResultMergerInterface $merger, ResultNormalizerInterface $normalizer, string $workerClass)
    {
        $return = ['results'=>[]];
        \Log::info("Search invoker started");

        $workers = [];

        foreach ($this->searchInputs as $groupName => $groups) {
            \Log::info("Createing worker #" . count($workers), $groups);
            $workers []= $worker = new $workerClass($this);

            foreach ($groups as $trialName => $planGroup) {
                $worker->build($trialName, $planGroup, function (array $outputs) use (&$return, $worker, $groupName, $trialName) {
                    foreach ($outputs as $output) {
                        foreach ($output->results as $result) {
                            $result->group = $groupName;
                            $result->trial = $trialName;
                            $return['results'][] = $result;
                        }
                    }
                });
            }

            $worker->run();
        }

        \Log::info("Waiting for workers to finish...");

        $allFinished = false;
        do {
            foreach ($workers as $k => $worker) {
                if (!$worker->isFinished()) {
                    \Log::info("Worker $k not fnished yet. Waiting...");
                    \Log::debug("Worker $k", [$worker]);
                    sleep(1);
                    continue 2;
                }
            }

            $allFinished = true;
        } while (!$allFinished);

        \Log::info("All workers finished");
        
        $groupedResults = [];
        \Log::info("Normalizing results");
        foreach ($return['results'] as $result) {
            $normalizer->normalize($result);

            if ($result->group && $result->trial) {
                $groupedResults[$result->group][$result->trial][$result->source][] = $result;
            }
        }

        \Log::info("Merging results");
        if (!empty($return['results'])) {
            $return['results'] = $merger->mergeAll($return['results'], new ResultMatcher($this->searchInputs, $groupedResults));
        }
        \Log::info("Merged results", $return['results']);

        \Log::info("Normalizing results");
        foreach ($return['results'] as $result) {
            $normalizer->normalize($result, 'after_merge');
        }

        if ($return['results']) {
            $rankedResults = [];
            foreach ($return['results'] as $result) {
                $rankedResults[$result->result_rank][] = $result;
            }
            krsort($rankedResults);
            $return['results'] = array_merge(...$rankedResults);
        }

        \Log::debug("Last results: " . count($return['results']), $return['results']);

        \Log::info("Search invoker finished");

        return $return;
    }

    public function getApiAccount($api): ApiAccount
    {
        $apiClass = "Skopenow\\PeopleData\\Sources\\" . ucfirst($api);
        if (is_subclass_of($apiClass, "Skopenow\\PeopleData\\Sources\\AbstractSearchFetcher")) {
            return new ApiAccount;
        }

        $account = getApiAccount($api);

        return $account;
    }

    public function getApiClient($api, $account = null): ClientInterface
    {
        $client = null;

        switch ($api) {
            case 'tloxp':
                $client = new SoapClient(__DIR__ . '/Resources/tloxp.wsdl', [
                    "proxy_host" => $account->associated_proxy_ip,
                    "proxy_port" => $account->associated_proxy_port,
                    "proxy_login" => $account->associated_proxy_username,
                    "proxy_password" => $account->associated_proxy_password,
                    "features" => SOAP_SINGLE_ELEMENT_ARRAYS,
                    "trace" => true,
                    "connection_timeout" => app()->environment(['production'])?6:6,
                    ]);
                break;

            default:
                $client = new CurlClient;
                break;
        }

        return $client;
    }
}
