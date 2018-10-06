<?php

namespace App\Listeners;

use App\Events\OnDatapointSaveEvent;
use App\Jobs\DataPointSaveJob;
use App\Events\DataPointSaveQueuedEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class OnDatapointSaveListener
{
    /**
     * The name of the queue the job should be sent to.
     *
     * @var string|null
     */
    // public $queue = 'listeners';
    // public static $queue;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        // self::$queue = new \SplQueue;
        // $this->queue->setIteratorMode(\SplDoublyLinkedList::IT_MODE_DELETE);
    }

    /**
     * Handle the event.
     *
     * @param  OnDatapointSaveEvent $event
     * @return void
     */
    public function handle(OnDatapointSaveEvent $event)
    {
        if (config('flags.initiating_report')) {
            \Log::info('BRAIN: OnDatapoint save Event Queued event');
            $queue = config('datapointQueue');
            if (!$queue) {
                $queue = new \SplQueue;
                config(['datapointQueue' => $queue]);
            }
            $queue->enqueue($event);
            return;
        }
        $this->runEvent($event);

    }
    private function runEvent(OnDatapointSaveEvent $event)
    {
        \Log::info('BRAIN: OnDatapoint Event run with type ' . $event->type);
        $report = loadService('reports');
        $data = [
            'input' => $event->input,
            'type' => $event->type,
            'datapointKey' => $event->datapointKey,
            'entityId' => $event->entityId,
            'state' => $event->state,
        ];

        $report->handleInsertedDatapointCombination($data);
    }
}
