<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Listeners\DataPointSaveQueuedListener;

class SaveReportData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'search:save-report-data {report_id} {context?} {event?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run Accept suggestion';

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

        $this->info(date("Y-m-d H:i:s") . " - Start SaveReportData for report $report_id");

        config(['state.report_id'=>$report_id]);

        $reportService = loadService('reports');
        $reportData = $reportService->getReport();
        $suggestion_data = [];
        if (!empty($reportData['model']) && !empty($reportData['model']->init_data)) {
            $suggestion_data = json_decode($reportData['model']->init_data, true);
        }

        $init_log = [];
        $init_log['context'] = $context;
        $init_log['event'] = $event;
        \DB::update("update persons set init_log=? where id=?", [json_encode($init_log), $report_id]);

        $data = $reportService->acceptSuggestion($report_id, $suggestion_data);
        $reportService->addDatapoint($data);
        config(['flags.initiating_report' => false]);


        $reportService->startSearch($report_id);

        // TODO: Generate results combination

        $queue = config('datapointQueue');
        if ($queue && !config('flags.initiating_report')) {
            \Log::debug('BRAIN: OnDatapoint Queued Events', [config('datapointQueue')]);
            foreach ($queue as $queuedEvent) {
                \Log::info('BRAIN: OnDatapoint save Event running Queued event');
                event($queuedEvent);
            }
        }

        if (!empty($data['emails'])) {
            $reportService->addEmailUsernamesDatapoint($data['emails'], $data['source']);
        }
        // start datapoint queues
        // \Job::dispatch()->onQueue('datapoint_save');

        // dd('save report');

        if (!empty($data['profiles'])) {
            $reportService->generateResultsCombination($data['profiles'], config('state.report_id'));
        }
        $datapointService = loadService("datapoint");
        $datasource = $datapointService->datasource();
        $datasource->publishUpdates();
        $this->info(date("Y-m-d H:i:s") . " - Finish SaveReportData for report $report_id");
    }
}
