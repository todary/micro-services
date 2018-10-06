<?php

use App\Events\OnDatapointSaveEvent;
use Illuminate\Support\Facades\Event;


class DataPointEventTest extends \TestCase
{
    /**
     * Test order shipping.
     */
    public function testDataPointSaveEvent()
    {
        // Event::fake();
        // Event::assertDispatched(OnDatapointSaveEvent::class, function ($e) {
        // });
        config(['state.report_id' => 60016]);
        $input = ['Mohammed Attya'];
        $type = 'name';
        $datapointKey = 'sgfdlkflkdsglkfda';
        $entityId = 10;
        event(new OnDatapointSaveEvent($input, $type, $datapointKey, $entityId));
    }
}
