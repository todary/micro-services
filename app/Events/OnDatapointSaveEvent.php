<?php

namespace App\Events;

class OnDatapointSaveEvent extends Event
{
    public $input;
    public $type;
    public $datapointKey;
    public $entityId;
    public $state;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($input, string $type, string $datapointKey, int $entityId)
    {
        $this->input = $input;
        $this->type = $type;
        $this->datapointKey = $datapointKey;
        $this->entityId = $entityId;
        $this->state = config('state');
    }
}
