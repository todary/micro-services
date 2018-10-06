<?php

namespace App\Providers;

use Laravel\Lumen\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\SomeEvent' => [
            'App\Listeners\EventListener',
        ],

        // add the after save event listener .
        'App\Events\AfterSaveEvent' => [
            'App\Listeners\AfterSaveListener',
        ],

        'App\Events\OnSourceCompletedEvent' => [
            'App\Listeners\OnSourceCompletedListener',
        ],

        'App\Events\OnDatapointFoundEvent' => [
            'App\Listeners\OnDatapointFoundListener',
        ],

        'App\Events\OnDatapointSaveEvent' => [
            'App\Listeners\AfterDatapointSaveListener',
            'App\Listeners\OnDatapointSaveListener',
        ],

        'App\Events\OnDatapointUpdateEvent' => [
            'App\Listeners\OnDatapointUpdateListener',
        ],

        'App\Events\AfterResultSaveEvent' => [
            'App\Listeners\AfterResultSaveListener',
            'App\Listeners\OnMainResultListener',
            'App\Listeners\CaptureScreenshotListener',
            'App\Listeners\CreateResultSiblings',
        ],

        'App\Events\AfterResultUpdateEvent' => [
            'App\Listeners\AfterResultUpdateListener',
            'App\Listeners\OnMainResultListener',
            // 'App\Listeners\CreateResultSiblings',
        ],

        'App\Events\OnVerifiedResultSaveEvent' => [
            'App\Listeners\OnVerifiedResultSaveListener',
        ],

        // 'App\Events\DataPointSaveQueuedEvent'   =>  [
        //     'App\Listeners\DataPointSaveQueuedListener',
        // ],

    ];
}
