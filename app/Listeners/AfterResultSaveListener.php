<?php

namespace App\Listeners;

use App\Events\AfterResultSaveEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Skopenow\Result\AfterSave ;

class AfterResultSaveListener /*implements ShouldQueue*/
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
    public function handle(AfterResultSaveEvent $event)
    {
        $result = $event->getResult() ;
        config([
            "state.report_id"   => $event->getReportId(),
        ]);
        // Load services
        $relationshipService = new \stdClass();//loadService('relationship') ;
        $datapointService = loadService("datapoint") ;
        $resultService = loadService("result");

        $resultService->afterResultSave($result);
        //$AfterSave = new AfterSave($result , $datapointService , $relationshipService) ;

        // $dataPoints = $this->datapointService->getDataPoints();
        // $AfterSave->process() ;
    }
}
