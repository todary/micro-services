<?php
namespace Skopenow\PeopleData\Workers;

use Skopenow\PeopleData\Workers\WorkerOutput;
use Skopenow\PeopleData\WorkerOutputInterface;

class BackgroundProcessWorker extends AbstractWorker
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
    }

    public function startJob(Job $job)
    {
        $command = "";
        // $command .= "nohup ";
        $command .= "php";
        $command .= " " . base_path('artisan');

        $command .= " search:run-peopledata-job";

        $command .= " " . escapeshellarg($job->id);
        $command .= " " . escapeshellarg($job->key);
        $command .= " " . escapeshellarg($job->api);
        $command .= " " . escapeshellarg(json_encode($job->input));
        $command .= " " . escapeshellarg(get_class($job->invoker));

        // $command .= " > /dev/null 2>/dev/null &";

        $descriptorspec = array(
           0 => array("pipe", "r"),
           1 => array("pipe", "w"),
           2 => array("pipe", "r")
        );

        \Log::info("Starting process for job " . $job->id, [$command]);
        $process = proc_open($command, $descriptorspec, $pipes);
        $job->status = Job::STATUS_RUNNING;
        $job->handler = $process;
        $job->stream = $pipes[1];
    }

    public function pingJob(Job $job)
    {
        if (!$job->handler) {
            return;
        }

        $status = proc_get_status($job->handler);
        if (!$status['running']) {
            \Log::info("Process returned for job " . $job->id);

            $job->status = Job::STATUS_FINISHED;
            $return = unserialize(stream_get_contents($job->stream));
            $job->results = $return['results'];

            if ($job->list) {
                $job->list->onJobFinished($job);
            }

            $job->handler = null;
        }
    }
}
