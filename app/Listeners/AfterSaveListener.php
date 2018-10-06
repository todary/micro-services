<?php

namespace App\Listeners;

use App\Events\AfterSaveEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Skopenow\Result\AfterSave ;

class AfterListenerListener implements ShouldQueue
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
        
    }

    /**
     * Handle the event.
     *
     * @param  ExampleEvent  $event
     * @return void
     */
    public function handle(AfterSaveEvent $event)
    {
        $result = $event->getResult() ;
        config([
            "state.report_id"   => $result->person_id,
            "state.result_id"   => $result->id,
        ]);
        // Load services
        $this->relationshipService = loadService('relationship') ;
        $this->datapointService = loadService("datapoint") ;

        $AfterSave = new AfterSave($result , $this->datapointService , $this->relationshipService) ;

        $dataPoints = $this->datapointService->getDataPoints();
        $AfterSave->process() ;

    }
}
