<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class OnSearchCompleted extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'search:on-search-completed {report_id} {context?} {event?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run On Search Completed logic';

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
        $force = false;

        $this->info(date("Y-m-d H:i:s") . " - Start OnSearchCompleted for report $report_id");

        config(['state.report_id'=>$report_id]);

        $affected_rows = \DB::update("update persons set on_complete_start_minute = hour(now())+(minute(now())/60) where id = {$report_id}");

        if (!$affected_rows) {
            if (!app()->environment('local') && !class_exists('CController', false)) {
                $this->warn(date("Y-m-d H:i:s") . " - Possible duplicate! Exitting.");
                return;
            }
        }

        \DB::update("update persons set on_complete_log_stream = if(on_complete_log_stream is null,'" . trim($context, '"') . "',on_complete_log_stream) where id = {$report_id}");

        $row = \DB::select("select completed from persons where id = {$report_id}");
        
        if (!$force && !empty($row[0]->completed)) {
            if (!app()->environment('local') && !class_exists('CController', false)) {
                $this->warn(date("Y-m-d H:i:s") . " - Already completed!");
                return;
            }
        }

        $reportService = loadService('reports');
        $reportService->onSearchComplete($report_id, $context, $event);

        //\SearchApis::time_taken($report_id, 0, true, $context);


        $this->info(date("Y-m-d H:i:s") . " - Finished OnSearchCompleted for report $report_id");

        $datapointService = loadService("datapoint");
        $datasource = $datapointService->datasource();
        $datasource->publishUpdates();
        $this->info(date("Y-m-d H:i:s") . " - Socket updated.");

        if (env('AWS_KEY') && !app()->environment('local') && !class_exists('CController', false)) {
            $credentials = new \Aws\Credentials\Credentials(env('AWS_KEY'), env('AWS_SECRET'));

            $lambda = \Aws\Lambda\LambdaClient::factory(array(
                    'credentials' => $credentials,
                    'region' => env('AWS_REGION'),
                    'version' => '2015-03-31',
                    'retries' => 3,
            ));

            $message = json_encode(["type"=>"aftercomplete", "report_id"=>$report_id]);

            $result = $lambda->invoke([
                'FunctionName' => env('LAMBDA_FUNCTION_ARN'),
                'InvocationType' => 'Event',
                'LogType' => 'Tail',
                'Payload' => $message,
            ]);

            $this->info(date("Y-m-d H:i:s") . " - Call AfterSearchCompleted for report $report_id");
        }
    }
}
