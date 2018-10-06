<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Skopenow\Search\Models\Criteria;

class RunPeopleDataJob extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'search:run-peopledata-job {id} {key} {api} {input} {invoker}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run PeopleData Job';

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
        set_time_limit(290);
        ini_set('max_execution_time', 290);

        $id = $this->argument("id");

        \Log::info("Start job $id");

        $key = $this->argument("key");
        $api = $this->argument("api");
        $input = json_decode($this->argument("input"), true);
        $invokerClass = $this->argument("invoker");
        $invoker = new $invokerClass([]);

        $job = new \Skopenow\PeopleData\Workers\Job($key, $api, $input, $invoker);
        \Log::info("Run Job");
        $job->start();
        \Log::info("End Job");

        $return = [];
        $return['results'] = $job->results;
        echo serialize($return);
    }
}
