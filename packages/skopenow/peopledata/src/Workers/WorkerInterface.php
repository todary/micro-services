<?php
namespace Skopenow\PeopleData\Workers;

use Skopenow\PeopleData\SearchInvokerInterface;

interface WorkerInterface
{
    public function __construct(SearchInvokerInterface $invoker);
    public function build(string $trialName, array $planGroup, callable $onFinished);
    public function run($planKey = null, $listKey = null);
    public function startJob(Job $job);
    public function pingJob(Job $job);
}
