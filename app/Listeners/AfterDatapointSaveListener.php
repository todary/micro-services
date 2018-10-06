<?php

namespace App\Listeners;

use App\Events\OnDatapointSaveEvent;

class AfterDatapointSaveListener
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
     * @param  OnDatapointSaveEvent  $event
     * @return void
     */
    public function handle(OnDatapointSaveEvent $event)
    {
        //
    }
}
