<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Skopenow\Search\Models\Criteria;

class RunCombination extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'search:run-combination {report_id} {comb_id} {level_id}  {is_force=0} {context?} {event?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run combination level';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info(date("Y-m-d H:i:s") . " - Start Run Combination");

        set_time_limit(290);
        ini_set('max_execution_time', 290);

        $report_id = $this->argument("report_id");
        $comb_id = $this->argument("comb_id");
        $level_id = $this->argument("level_id");
        $is_force = $this->argument("is_force");
        $context = $this->argument("context");
        $event = $this->argument("event");

        config([
            'state.report_id' => $report_id,
            'state.combination_id' => $comb_id,
            'state.combination_level_id' => $level_id,
        ]);

        $this->info(date("Y-m-d H:i:s") . " - Requesting combination " . print_r($this->arguments(), true));

        $combinationService = loadService('combinations');
        $combinationLevel = $combinationService->getCombinationLevelById($level_id);

        if (!$combinationLevel) {
            $this->warn(date("Y-m-d H:i:s") . " - Combination not found!");
            return;
        }

        if (!$is_force && !app()->environment('local') && !class_exists('CController', false) && $combinationLevel['is_completed']) {
            $this->warn(date("Y-m-d H:i:s") . " - Combination already completed");
            return;
        }

        /*
        $this->info(date("Y-m-d H:i:s") . " - Current start time " . $combinationLevel['start_time']);

        $start_time = strtotime($combinationLevel['start_time']);
        if ($is_force) {
            $this->info(date("Y-m-d H:i:s") . " - Force mode is active.");
        } else if ($combinationLevel['start_time'] && time()-$start_time<150) {
            if (!app()->environment('local')) {
                $this->warn(date("Y-m-d H:i:s") . " - Possible duplicate! Exitting.");
                return;
            }
        }
        */

        if ($is_force) {
            $this->info(date("Y-m-d H:i:s") . " - Force mode is active.");
        } else {
            $affected_rows = \DB::update("update combination_level set started=1, start_minute=hour(now())+(minute(now())/60) where report_id = {$report_id} and id={$level_id}");

            if (!$affected_rows) {
                if (!app()->environment('local') && !class_exists('CController', false)) {
                    $this->warn(date("Y-m-d H:i:s") . " - Possible duplicate! Exitting.");
                    return;
                }
            }
        }

        if ($combinationLevel['log_stream']) {
            $this->info(date("Y-m-d H:i:s") . " - Previous log stream {$combinationLevel['log_stream']}");
        }

        \DB::update("update combination_level set start_time=if(start_time is null,NOW(),start_time), trials=trials + 1, log_stream='" . trim($context, '"') . "' where report_id = {$report_id} and id=$level_id");

        $reportService = loadService('reports');
        $report = $reportService->getReport();

        if (!$report) {
            $this->warn(date("Y-m-d H:i:s") . " - Report not found!");
            return;
        }


        //event(new \App\Events\OnSourceCompletedEvent('myspace'));

        $combinationService->onLevelStart($level_id);

        $service = loadService('search');
        $output = $service->runSearch($report, $combinationLevel);

        $status = !!$output->getResults()->count() || !!$output->getDataPoints();

        $combinationService->onLevelEnd($level_id, $status);

        $this->info(date("Y-m-d H:i:s") . " - Combination completed.");

        $datapointService = loadService("datapoint");
        $datasource = $datapointService->datasource();
        $datasource->publishUpdates();
        $this->info(date("Y-m-d H:i:s") . " - Socket updated.");

        if (\DB::transactionLevel()) {
            $error = "Active transaction detected after combination is finished!";
            notifyDev($error);
        }
        dump($output);
    }
}
