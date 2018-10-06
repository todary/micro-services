<?php

namespace App\Listeners;

use App\Events\AfterResultSaveEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;


class OnVerifiedResultSaveListener
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
     * @param  AfterResultSaveEvent  $event
     * @return void
     */
    public function handle($event)
    {
        $report = loadService('reports');
        $data = $event->getResult();
        if (!$data->Run_Main_Result_Event) {
            return false;
        }
        $name = $data->names->getArrayCopy()[0];
        $social_profile_id = $data->social_profile_id;
        $result_id = $data->id;
        $is_relative = true;
        $verified = true;
        $related_to = [$result_id];
        $state = config('state');
        $data = compact('name', 'social_profile_id', 'result_id', 'is_relative', 'related_to', 'state');
        $report->handleMainResult($data);
    }
}
