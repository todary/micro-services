<?php
namespace Skopenow\PeopleData\Workers;

class JobList implements JobListInterface
{
    const STRATEGY_SERIAL = 1;
    const STRATEGY_PARALLEL = 2;

    public $worker = null;
    public $key = "";
    public $inputs = "";
    public $jobs = [];
    protected $jobCursor = 0;
    public $strategy = self::STRATEGY_SERIAL;
    protected $finishedJobsCount = 0;
    protected $failedJobsCount = 0;
    protected $onAllFinished;

    public function __construct(WorkerInterface $worker, string $key, array $inputs, string $strategy, callable $onAllFinished)
    {
        $this->worker = $worker;
        $this->key = $key;
        $this->inputs = $inputs;
        $this->onAllFinished = $onAllFinished;

        switch ($strategy) {
            case 'parallel':
                $this->strategy = self::STRATEGY_PARALLEL;
                break;
            
            default:
                $this->strategy = self::STRATEGY_SERIAL;
                break;
        }
    }

    public function addJob(Job $job)
    {
        $job->list = $this;
        $this->jobs[$job->id] = $job;
    }

    public function onJobFinished(Job $job)
    {
        $this->finishedJobsCount++;

        if ($this->strategy == self::STRATEGY_SERIAL) {
            if ($job->results) {
                ($this->onAllFinished)($job->results, $job->key, $this);
            } else {
                $job = next($this->jobs);
                if ($job === false) {
                    ($this->onAllFinished)([], null, $this);
                } else {
                    $this->worker->startJob($job);
                }
            }
        } else if ($this->finishedJobsCount + $this->failedJobsCount >= count($this->jobs)) {
            $allResults = [];
            foreach ($this->jobs as $currentJob) {
                $allResults = array_merge($allResults, $currentJob->results);
            }
            ($this->onAllFinished)($allResults, "*", $this);
        }
    }

    public function ping()
    {
        foreach ($this->jobs as $job) {
            $job->ping();
        }
    }

    public function start()
    {
        \Log::info("JOB List started", [$this->key, $this->inputs]);

        if ($this->strategy == self::STRATEGY_SERIAL && count($this->jobs)>1) {
            $job = reset($this->jobs);
            $this->worker->startJob($job);
        } else {
            foreach ($this->jobs as $job) {
                $this->worker->startJob($job);
            }
        }
    }

    public function pause()
    {
    }

    public function terminate()
    {
    }
}
