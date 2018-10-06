<?php
namespace Skopenow\PeopleData\Workers;

use App\Models\ApiAccount;
use Skopenow\PeopleData\Criteria;
use Skopenow\PeopleData\Clients\ClientInterface;
use Skopenow\PeopleData\Clients\CurlClient;
use Skopenow\PeopleData\Clients\SoapClient;
use Skopenow\PeopleData\SearchInvokerInterface;

abstract class AbstractWorker implements WorkerInterface
{
    protected $invoker;
    protected $jobLists = [];
    protected $outputs = [];
    protected $finished = false;
    protected $groupsCount = 0;
    protected $finishedGroupsCount = 0;

    public function __construct(SearchInvokerInterface $invoker)
    {
        $this->invoker = $invoker;
    }

    public function build(string $trialName, array $planGroup, callable $onFinished)
    {
        foreach ($planGroup as $pkey => $plan) {
            $planKey = $trialName . '_' . $pkey;
            $this->groupsCount++;

            foreach ($plan as $key => $input) {
                $this->createJobList($planKey, $key, $input, function (JobListOutputInterface $output) use ($onFinished, $planKey) {

                    \Log::info("Job List Return", [$planKey]);

                    if ($output->results) {
                        foreach ($output->results as $result) {
                            $result->plan = $planKey;
                        }
                    }

                    $this->outputs[$planKey][] = $output;

                    if (count($this->outputs[$planKey] == count($this->jobLists[$planKey]))) {
                        \Log::info("Worker group returned", [$this->outputs]);

                        $finished = false;

                        $nextKey = null;
                        if ($output->results) {
                            $finished = true;
                        } else {
                            $nextList = next($this->jobLists[$planKey]);
                            if ($nextList === false) {
                                $finished = true;
                            } else {
                                $nextKey = key($this->jobLists[$planKey]);
                            }
                        }

                        if ($finished) {
                            $this->finishedGroupsCount++;
                            \Log::info("Finished groups {$this->finishedGroupsCount} out of {$this->groupsCount}.", [$this->outputs]);
                            $onFinished($this->outputs[$planKey]);
                            if ($this->groupsCount <= $this->finishedGroupsCount) {
                                $this->finished = true;
                            } else {
                                $nextPlanKey = false;
                                $foundCurrentPlan = false;
                                foreach ($this->jobLists as $planName => $lists) {
                                    if ($foundCurrentPlan) {
                                        $nextPlanKey = $planName;
                                        break;
                                    }

                                    if ($planName == $planKey) {
                                        $foundCurrentPlan = true;
                                    }
                                }

                                if (!$output->results && $nextPlanKey !== false) {
                                    $this->run($nextPlanKey);
                                } else {
                                    $this->finished = true;
                                }
                            }
                        } else {
                            $this->run($planKey, $nextKey);
                        }
                    }
                });
            }
        }
    }

    protected function createJobList(string $planKey, string $key, $input, callable $onListFinished)
    {
        $apis = $input['apis'];
        $strategy = $input['strategy']??'serial';

        $this->jobLists[$planKey][] = $jobList = new JobList($this, $key, $input, $strategy, function (array $results, $key, JobListInterface $jobList) use ($onListFinished) {

            \Log::info("JOB List returned", [$jobList->key, $jobList->inputs]);

            $output = new JobListOutput;
            $output->key = $key;
            $output->results = $results;
            $onListFinished($output);
        });

        foreach ($apis as $key => $api) {
            $jobList->addJob(new Job($key, $api, $input, $this->invoker));
        }

        /*
        switch ($strategy) {
            case 'parallel':
                $allResults = [];
                foreach ($apis as $key => $api) {
                    $this->jobs []= $job = $this->call($api, $input, function (array $results) use (&$allResults) {
                        $allResults = array_merge($allResults, $results);
                    });
                }

                // wait
                echo "All Wait...\n";
                $onReply($allResults, $key);
                break;
            default:
                $allResults = [];
                foreach ($apis as $key => $api) {
                    $this->jobs []= $job = $this->call($api, $input, function (array $results) use (&$allResults) {
                        $allResults = array_merge($allResults, $results);
                    });

                    echo "Single Wait...\n";
                    // wait

                    if (!empty($results)) {
                        break;
                    }
                }

                $onReply($allResults, $key);
                break;
        }
        */
    }

    public function isFinished()
    {
        foreach ($this->jobLists as $planJobLists) {
            foreach ($planJobLists as $jobList) {
                $jobList->ping();
            }
        }
        return $this->finished;
    }

    abstract public function run($planKey = null, $listKey = null);

    abstract public function startJob(Job $job);

    abstract public function pingJob(Job $job);
}
