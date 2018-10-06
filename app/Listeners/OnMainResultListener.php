<?php

namespace App\Listeners;

use App\Events\AfterResultSaveEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Skopenow\Result\Verify\CheckVerifiedResults;


class OnMainResultListener
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
        \Log::info('BRAIN: OnMainResultListener');
        $report = loadService('reports');
        $data = $event->getResult();
        if (!$data->Run_Main_Result_Event) {
            \Log::info('BRAIN: OnMainResultListener Run_Main_Result_Event ('.$data->url.') === false');
            return false;
        }
        $names = [];
        foreach ($data->names->getArrayCopy() as $name) {
            $names[] = $name->data;
        }
        // $name = $data->names->getArrayCopy()[0]->data['full_name']??'';
        $image = $data->image;
        $social_profile_id = $data->social_profile_id;
        $result_id = $data->id;
        $flags = $data->getFlags();
        $verified = $this->checkIsVerified($flags??0);
        $is_relative = $data->getIsRelative();
        $saveStatus = $data->getSaveStatus();
        $action = '';
        if (!empty($saveStatus)) {
            $action = reset($saveStatus)['action'];
        }
        $main_source = $data->mainSource;
        $related_to = [$result_id];
        $state = config('state');
        $data = compact('data', 'names', 'image', 'social_profile_id', 'result_id', 'is_relative', 'related_to', 'state', 'main_source', 'action', 'verified', 'flags');
        \Log::debug('BRAIN: OnMainResultListener Data: ', $data);
        $report->handleMainResult($data);
    }

    public function checkIsVerified(int $flags)
    {
        $CheckVerified = new CheckVerifiedResults();
        $status = $CheckVerified->check($flags);
        return $status['status'];
    }
}
