<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class AfterSearchCompleted extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'search:after-search-completed {report_id} {context?} {event?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run After Search Completed logic';

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
        $report_id = $this->argument("report_id");
        $context = $this->argument("context");
        $event = $this->argument("event");

        $this->info(date("Y-m-d H:i:s") . " - Start OnSearchCompleted for report $report_id");

        config(['state.report_id'=>$report_id]);
        $reportService = loadService('reports');
        $reportService->afterSearchComplete($report_id, $context, $event);

        $datapointService = loadService("datapoint");
        $datasource = $datapointService->datasource();
        $datasource->publishUpdates();
        $this->info(date("Y-m-d H:i:s") . " - Socket updated.");

        /*
        \SearchApis::setPersonID($report_id);
        $person = \Persons::model()->findByPk($report_id);
        \SearchApis::afterSearchCompleted($person);
        */

        $this->info(date("Y-m-d H:i:s") . " - Finish OnSearchCompleted for report $report_id");
    }
}
