<?php
namespace App\Listeners;


class OnDatapointMatchingListener
{

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
    public function subscribe($events)
    {
        $events->listen(
        	'App\Events\OnDatapointSaveEvent',
        	'App\Listeners\UserEventSubscriber@onDataPointSave'
        );

        $events->listen(
        	'App\Events\OnDatapointUpdateEvent',
        	'App\Listeners\UserEventSubscriber@onDatapointUpdate'
        );
    }

    public function onDataPointSave($event)
    {
    	dd('event',$event);
    }

    public function onDatapointUpdate($event)
    {
    	dd('event',$event);
    }

}