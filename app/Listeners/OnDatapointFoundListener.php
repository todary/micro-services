<?php

namespace App\Listeners;

use App\Events\OnDatapointFoundEvent;
use Illuminate\Contracts\Queue\ShouldQueue;

class OnDatapointFoundListener implements ShouldQueue
{
    /**
     * The name of the connection the job should be sent to.
     *
     * @var string|null
     */
    public $connection = 'skopenow-database';

    /**
     * The name of the queue the job should be sent to.
     *
     * @var string|null
     */
    public $queue = 'datapoints';

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Handle the event.
     *
     * @param  OnDatapointFoundEvent  $event
     * @return void
     */
    public function handle(OnDatapointFoundEvent $event)
    {
        config(['state'=>$event->state]);
        // dump($event->data);
        $event->type->addEntry($event->data);
    }
}
