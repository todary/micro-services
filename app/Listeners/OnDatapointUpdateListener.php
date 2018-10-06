<?php

namespace App\Listeners;

use App\Events\OnDatapointUpdateEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class OnDatapointUpdateListener
{
    /**
     * The name of the queue the job should be sent to.
     *
     * @var string|null
     */
    public $queue = 'listeners';

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
     * @param  OnDatapointSaveEvent $event
     * @return void
     */
    public function handle(OnDatapointUpdateEvent $event)
    {
        \Log::info('BRAIN: OnDatapointUpdate Event run with type ' . $event->type);
        $report = loadService('reports');
        $data = [
            'input' => $event->input,
            'type' => $event->type,
            'datapointKey' => $event->datapointKey,
            'entityId' => $event->entityId,
            'state' => $event->state,
            'oldData' => $event->oldData
        ];
        $report->handleUpdatedDatapointCombination($data);
    }
}
