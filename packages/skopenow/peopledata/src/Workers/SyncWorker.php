<?php
namespace Skopenow\PeopleData\Workers;

use Skopenow\PeopleData\Workers\WorkerOutput;
use Skopenow\PeopleData\WorkerOutputInterface;

class SyncWorker extends AbstractWorker
{
    public function run($planKey = null, $listKey = null)
    {
        if ($planKey === null) {
            reset($this->jobLists);
            $planKey = key($this->jobLists);
        }
        if ($planKey === false) {
            return;
        }


        if ($listKey === null) {
            reset($this->jobLists[$planKey]);
            $listKey = key($this->jobLists[$planKey]);
        }
        if ($listKey === false) {
            return;
        }

        \Log::info("Running worker $planKey -> $listKey", [$this->jobLists[$planKey][$listKey]]);

        $this->jobLists[$planKey][$listKey]->start();


        /*
        $executePlans = [];

        if ($planKey === null) {
            reset($this->jobLists);
            $planKey = key($this->jobLists);
            if ($planKey === false) {
                return;
            }

            // $executePlans = array_keys($this->jobLists);

            $executePlans[] = $planKey;
        } else {
            $executePlans[] = $planKey;
        }

        if (!$executePlans) {
            return;
        }

        foreach ($executePlans as $executePlan) {
            if ($listKey === null) {
                reset($this->jobLists[$executePlan]);
                $listKey = key($this->jobLists[$executePlan]);
            }

            if ($listKey === false) {
                return;
            }

            if (!isset($this->jobLists[$executePlan][$listKey])) {
                continue;
            }

            \Log::info("Running worker $executePlan -> $listKey", [$this->jobLists[$executePlan]]);

            // $this->jobLists[$executePlan][$listKey]->start();
        }
        */
    }

    public function startJob(Job $job)
    {
        $job->start();
    }

    public function pingJob(Job $job)
    {
    }
}
