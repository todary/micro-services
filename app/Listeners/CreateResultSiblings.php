<?php

namespace App\Listeners;

use App\Events\AfterResultSaveEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CreateResultSiblings /*implements ShouldQueue*/
{
 
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
     * @param  ExampleEvent  $event
     * @return void
     */
    public function handle(/*AfterResultSaveEvent*/ $event)
    {
        $result = $event->getResult() ;
        config([
            "state.report_id"   => $event->getReportId(),
        ]);
        
        $resultService = loadService('result');
        $resultService->createDefaultSiblings($result);
    }
}
