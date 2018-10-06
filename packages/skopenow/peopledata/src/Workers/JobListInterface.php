<?php
namespace Skopenow\PeopleData\Workers;

interface JobListInterface
{
    public function __construct(WorkerInterface $worker, string $key, array $inputs, string $strategy, callable $onAllFinished);

    public function addJob(Job $job);
    public function onJobFinished(Job $job);

    public function ping();

    public function start();
    public function pause();
    public function terminate();
}
