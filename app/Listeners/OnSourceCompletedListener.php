<?php

namespace App\Listeners;

use App\Events\OnSourceCompletedEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class OnSourceCompletedListener // implements ShouldQueue
{
    /**
     * The name of the queue the job should be sent to.
     *
     * @var string|null
     * /
    public $queue = 'listeners';
    */

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  ExampleEvent  $event
     * @return void
     */
    public function handle(OnSourceCompletedEvent $event)
    {
        config(['state.report_id' => $event->getReportId()]);
        $search = loadService('search');
        $search->runOnSourceCompleted($event->getManagerClassName());
    }
}
