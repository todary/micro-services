<?php

namespace App\Listeners;

use App\Events\AfterResultUpdateEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Skopenow\Result\AfterSave ;

class AfterResultUpdateListener /*implements ShouldQueue*/
{

    /**
     * [$relationshipService the relationships service entry point]
     * @var [type]
     */
    protected $relationshipService ;

    /**
     * [$datapointService The data point service entry point]
     * @var [type]
     */
    protected $datapointService ;
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
    public function handle(AfterResultUpdateEvent $event)
    {
        $result = $event->getResult() ;
        config([
            "state.report_id"   => $event->getReportId(),
        ]);
        // Load services
        $relationshipService = new \stdClass();//loadService('relationship') ;
        $datapointService = loadService("datapoint") ;
        $resultService = loadService("result");

        $resultService->afterResultUpdate($result);
    }
}
