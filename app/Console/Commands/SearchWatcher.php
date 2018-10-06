<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SearchWatcher extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'search:watch';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Watch combinations and reports';

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
        $trials = 3;
        $start_time = time();
        
        $this->info(date("Y-m-d H:i:s") . " - Search Watcher started");

        $credentials = new \Aws\Credentials\Credentials(env('AWS_KEY'), env('AWS_SECRET'));

        $lambda = \Aws\Lambda\LambdaClient::factory(array(
                'credentials' => $credentials,
                'region' => env('AWS_REGION'),
                'version' => '2015-03-31',
                'retries' => 3,
        ));

        $counter = 0;
        while (true) {
            $counter++;

            $combs = \DB::select('CALL APP_SearchWatcher_GetCombinations(?)', [env('APP_VERSION')]);

            foreach ($combs as $comb) {
                $this->info(date("Y-m-d H:i:s") . " - Combination run {$comb->report_id}, {$comb->id}, {$comb->level_id}");

                \DB::select('CALL APP_SearchWatcher_OnCombinationExecute(?,?,?)', [$comb->report_id, $comb->id, $comb->level_id]);


                $message = json_encode(["type"=>"start", "id"=>$comb->id, "source"=>$comb->source, "report_id"=>$comb->report_id, "is_new"=>1, "level_id"=>$comb->level_id]);

                $result = $lambda->invoke([
                    'FunctionName' => env('LAMBDA_FUNCTION_ARN'),
                    'InvocationType' => 'Event',
                    'LogType' => 'Tail',
                    'Payload' => $message,
                ]);
            }

            if (time() - $start_time >= (3 * 60 * 60)) { // Maximum 3 hours
                // exit();
            }

            if ($counter % 3 == 0) {
                $reports = \DB::select('CALL APP_SearchWatcher_GetReports(?)', [env('APP_VERSION')]);

                foreach ($reports as $report) {
                    $this->info(date("Y-m-d H:i:s") . " - Complete run {$report->id}");

                    $message = json_encode(["type" => "complete", "report_id"=>$report->id, "diff"=>$report->diff, "is_new"=>$report->is_new]);

                    $result = $lambda->invoke([
                        'FunctionName' => env('LAMBDA_FUNCTION_ARN'),
                        'InvocationType' => 'Event',
                        'LogType' => 'Tail',
                        'Payload' => $message,
                    ]);
                }
            }
                
            sleep(1);
        }
    }
}
